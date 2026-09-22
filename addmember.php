<?php
    $serverName   = 'localhost';
    $userName     = 'root';
    $userPassword = '';
    $databaseName = 'codinghackathon';
    $dbConnection = new mysqli($serverName, $userName, $userPassword, $databaseName);

    $forenameError = $surnameError = $dobError = $emailError = $phoneError = "";
    $teamError = $memberError = "";
    $forename = $surname = $dob = $email = $phone = "";
    $validInputs = true;

    $query1 = "SELECT * from Settings"; 
    $queryResult = mysqli_query($dbConnection, $query1); 
    $settingsRow = mysqli_fetch_assoc($queryResult);
    $membersPerTeam = $settingsRow['MembersPerTeam'];
    
    //getting all available members for dropdown
    $availableMembersQuery = "SELECT MemberID from Members WHERE MemberID NOT IN (SELECT MemberID from TeamMembers)";
    $availableMembersResult = mysqli_query($dbConnection, $availableMembersQuery);

    //getting all teams for dropdown
    $teamsQuery = "SELECT * from Teams";
    $teamsResult = mysqli_query($dbConnection, $teamsQuery);

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $selectedMemberID = (int) $_POST["memberID"];
        $selectedTeamID = (int) $_POST["teamID"];

        //checking if the team hasn't hit member limit
        $countMembersQuery = "SELECT count(*) as memberCount from TeamMembers WHERE TeamID = $selectedTeamID";
        $countMembersResult = mysqli_query($dbConnection, $countMembersQuery);
        $countMembersRow = mysqli_fetch_assoc($countMembersResult);
        if ($countMembersRow['memberCount'] >= $membersPerTeam)
        {
            $teamError = "This team is already full";
            $validInputs = false;
        }

        //validating forename
        if (empty($_POST["forename"]))
        {
            $forenameError = "Please enter a Forename";
            $validInputs = false;
        }
        else
        {
            $forename = $_POST["forename"];
            if (!preg_match("/^[a-zA-Z ]*$/", $forename))
            {
                $forenameError = "Enter alphabetic characters only!";
                $validInputs = false;
            }
        }

        //validating surname
        if (empty($_POST["surname"]))
        {
            $surnameError = "Please enter a Surname";
            $validInputs = false;
        }
        else
        {
            $surname = $_POST["surname"];
            if (!preg_match("/^[a-zA-Z ]*$/", $surname))
            {
                $surnameError = "Enter alphabetic characters only!";
                $validInputs = false;
            }
        }

        //validating date of birth and age ( checking if 18 or older - similar code to  the one from the slides)
        if (empty($_POST["dob"]))
        {
            $dobError = "Please enter a date of  birth";
            $validInputs = false;
        }
        else
        {
            $dob = $_POST["dob"];
            $dateElements = explode("-", $dob); //here its "-" because the date is stored in format yyyy-mm-dd
            $userYear = (int) $dateElements[0];
            $userMonth = (int) $dateElements[1];
            $userDay = (int) $dateElements[2];
            $systemDay = (int) date('d');
            $systemMonth = (int) date('m');
            $systemYear = (int) date('Y') - 18;
            $userBirthDate = mktime(0, 0, 0, $userMonth, $userDay, $userYear);
            $systemDate = mktime(0, 0, 0, $systemMonth, $systemDay, $systemYear);
            if ($userBirthDate > $systemDate)
            {
                $dobError = "Member must be 18 years of age or older";
                $validInputs = false;
            }
        }

        //validating email
        if (empty($_POST["email"]))
        {
            $emailError = "Please enter an Email Address";
            $validInputs = false;
        }
        else
        {
            $email = $_POST["email"];
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) //using built in function to validate email format
            {
                $emailError = "Incorrect Format for Email Address";
                $validInputs = false;
            }
        }

        //validating phone number
        if (empty($_POST["phone"]))
        {
            $phoneError = "Please enter a Mobile Phone Number";
            $validInputs = false;
        }
        else
        {
            $phone = $_POST["phone"];
            if (!preg_match("/^08(5|6|7|9)\d{7}$/", $phone))
            {
                $phoneError = "Incorrect Format for Phone Number ";
                $validInputs = false;
            }
        }

        //getting experience dropdowns
        $primaryLanguage = $_POST["primaryLanguage"];
        $experienceLevel = $_POST["experienceLevel"];
        $previousHackathons = $_POST["previousHackathons"];

        if ($validInputs)
        {
            $memberDOB = date("Y-m-d", strtotime($dob));
            $insertMember = "INSERT into TeamMembers values($selectedMemberID, $selectedTeamID, '$forename', '$surname', '$memberDOB', 
                                                            '$email','$phone', '$primaryLanguage', '$experienceLevel','$previousHackathons')";
            if (mysqli_query($dbConnection, $insertMember))
                $memberError = "Member added successfully";
            else
                $memberError = "Error adding member, please try again";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coding Hackathon - Add Member to Team</title>
    <style>
      .errorMessage { color: #FF0000; }
    </style>
  </head>
  <body>
    <h4>Coding Hackathon - Add Member to Team</h4>

    <p class="errorMessage"><?php echo $memberError; ?></p>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
      <table>
        <tr>
          <td>Member ID:</td>
          <td>
            <select name="memberID">
              <?php
                while($row = mysqli_fetch_assoc($availableMembersResult)){
                    $memberID = $row['MemberID'];
                    echo "<option value='$memberID'>$memberID</option>"; //hiding the member ID in the dropdown - as shown in class
                }
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <td>Select Team:</td>
          <td>
            <select name="teamID">
              <?php
                while($row = mysqli_fetch_assoc($teamsResult)){
                  $teamID= $row['TeamID'];
                  $teamName = $row['TeamName'];
                  echo "<option value= '$teamID'>$teamName</option>";
                }  
              ?>
            </select>
          </td>
          <td><span class="errorMessage"><?php echo $teamError; ?></span></td>
        </tr>
        <tr>
          <td>Forename:</td>
          <td><input type="text" name="forename" value="<?php echo $forename; ?>"></td>
          <td><span class="errorMessage"><?php echo $forenameError; ?></span></td>
        </tr>
        <tr>
          <td>Surname:</td>
          <td><input type="text" name="surname" value="<?php echo $surname; ?>"></td>
          <td><span class="errorMessage"><?php echo $surnameError; ?></span></td>
        </tr>
        <tr>
          <td>Date of Birth:</td>
          <td><input type="date" name="dob" value="<?php echo $dob; ?>"></td>
          <td><span class="errorMessage"><?php echo $dobError; ?></span></td>
        </tr>
        <tr>
          <td>Email:</td>
          <td><input type="text" name="email" value="<?php echo $email; ?>"></td>
          <td><span class="errorMessage"><?php echo $emailError; ?></span></td>
        </tr>
        <tr>
          <td>Mobile Phone:</td>
          <td><input type="text" name="phone" value="<?php echo $phone; ?>"></td>
          <td><span class="errorMessage"><?php echo $phoneError; ?></span></td>
        </tr>
        <tr>
          <td>Primary Language:</td>
          <td>
            <select name="primaryLanguage">
              <option value="Python">Python</option>
              <option value="JavaScript">JavaScript</option>
              <option value="PHP">PHP</option>
              <option value="Java">Java</option>
              <option value="C++">C++</option>
              <option value="C#">C#</option>
              <option value="Other">Other</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>Experience Level:</td>
          <td>
            <select name="experienceLevel">
              <option value="Beginner">Beginner</option>
              <option value="Intermediate">Intermediate</option>
              <option value="Advanced">Advanced</option>
              <option value="Expert">Expert</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>Previous Hackathons:</td>
          <td>
            <select name="previousHackathons">
              <option value="0">0</option>
              <option value="1-2">1-2</option>
              <option value="3-5">3-5</option>
              <option value="5+">5+</option>
            </select>
          </td>
        </tr>
      </table>
      <br>
      <input type="submit" value="Add Member">
      &nbsp;&nbsp;
      <a href="mainmenu.php">Back to Main Menu</a>
    </form>
  </body>
</html>