<?php
    $serverName   = 'localhost';
    $userName     = 'root';
    $userPassword = '';
    $databaseName = 'codinghackathon';
    $dbConnection = new mysqli($serverName, $userName, $userPassword, $databaseName);

    $memberError = "";

    $forenameError = $surnameError = $dobError = $emailError = $phoneError = "";
    $forename = $surname = $dob = $email = $phone = "";
    $validInputs = true;

    //members dropdown
    $membersQuery = "SELECT * from TeamMembers";
    $membersResult = mysqli_query($dbConnection, $membersQuery);

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $selectedMemberID = (int) $_POST["memberID"];

        //get current data
        $query = "SELECT * FROM TeamMembers WHERE MemberID = $selectedMemberID";
        $result = mysqli_query($dbConnection, $query);
        $row = mysqli_fetch_assoc($result);

        $forename = $_POST["forename"];
        if ($forename == "") $forename = $row["MemberForename"]; //in case the user leaves this emoty we will keep the current value from the database
        else
        {
            if (!preg_match("/^[a-zA-Z ]*$/", $forename)) //same validation...
            {
                $forenameError = "Enter alphabetic characters only!";
                $validInputs = false;
            }
        }

        $surname = $_POST["surname"];
        if ($surname == "") $surname = $row["MemberSurname"];
        else
        {
            if (!preg_match("/^[a-zA-Z ]*$/", $surname))
            {
                $surnameError = "Enter alphabetic characters only!";
                $validInputs = false;
            }
        }

        $dob = $_POST["dob"];
        if ($dob == "") $dob = $row["MemberDOB"];
        else
        {
            $dateElements = explode("-", $dob);

            $userYear = (int)$dateElements[0];
            $userMonth = (int)$dateElements[1];
            $userDay = (int)$dateElements[2];

            $systemDay = (int) date('d');
            $systemMonth = (int) date('m');
            $systemYear = (int) date('Y') - 18;

            $userBirthDate = mktime(0,0,0,$userMonth,$userDay,$userYear);
            $systemDate = mktime(0,0,0,$systemMonth,$systemDay,$systemYear);

            if ($userBirthDate > $systemDate)
            {
                $dobError = "Member must be 18 years or older";
                $validInputs = false;
            }
        }

        $email = $_POST["email"];
        if ($email == "") $email = $row["MemberEmail"];
        else
        {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $emailError = "Incorrect Format for Email Address";
                $validInputs = false;
            }
        }

        $phone = $_POST["phone"];
        if ($phone == "") $phone = $row["MemberMobilePhone"];
        else
        {
            if (!preg_match("/^08(5|6|7|9)\d{7}$/", $phone))
            {
                $phoneError = "Incorrect Format for Phone Number";
                $validInputs = false;
            }
        }

        
        $primaryLanguage = $_POST["primaryLanguage"];
        if ($primaryLanguage == "") $primaryLanguage = $row["PrimaryLanguage"];

        $experienceLevel = $_POST["experienceLevel"];
        if ($experienceLevel == "") $experienceLevel = $row["ExperienceLevel"];

        $previousHackathons = $_POST["previousHackathons"];
        if ($previousHackathons == "") $previousHackathons = $row["PreviousHackathons"];

       
        if ($validInputs)
        {
            $updateQuery = "UPDATE TeamMembers
                            SET MemberForename = '$forename',
                            MemberSurname = '$surname',
                            MemberDOB = '$dob',
                            MemberEmail = '$email',
                            MemberMobilePhone = '$phone',
                            PrimaryLanguage = '$primaryLanguage',
                            ExperienceLevel = '$experienceLevel',
                            PreviousHackathons = '$previousHackathons'
                            WHERE MemberID = $selectedMemberID
            ";

            if (mysqli_query($dbConnection, $updateQuery))
                $memberError = "Member updated successfully";
            else
                $memberError = "Error updating member, please try again";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Coding Hackathon - Edit Member</title>
    <style>
        .errorMessage { color: #FF0000;}
    </style>
</head>
<body>
    <h4>Coding Hackathon - Edit Member</h4>

    <p class="errorMessage"><?php echo $memberError; ?></p>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <table>
        <tr>
          <td>Select Member:</td>
          <td>
            <select name="memberID">
              <?php
                while($row = mysqli_fetch_assoc($membersResult))
                {
                    $memberID = $row['MemberID'];
                    $fullName = $row['MemberForename'] . " " . $row['MemberSurname'];
                    echo "<option value='$memberID'>$fullName</option>";
                }
              ?>
            </select>
          </td>
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
            <td>Phone:</td>
            <td><input type="text" name="phone" value="<?php echo $phone; ?>"></td>
            <td><span class="errorMessage"><?php echo $phoneError; ?></span></td>
        </tr>

        <tr>
            <td>Primary Language:</td>
            <td>
                <select name="primaryLanguage">
                    <option value="">(no change)</option>
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
                    <option value="">(no change)</option>
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
                    <option value="">(no change)</option>
                    <option value="0">0</option>
                    <option value="1-2">1-2</option>
                    <option value="3-5">3-5</option>
                    <option value="5+">5+</option>
                </select>
            </td>
        </tr>
        </table>
        <br>
        <input type="submit" value="Edit Member">
        &nbsp;&nbsp;
        <a href="mainmenu.php">Back to Main Menu</a>

    </form>

</body>
</html>