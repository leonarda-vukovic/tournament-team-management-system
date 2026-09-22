<?php
    $serverName   = 'localhost';
    $userName     = 'root';
    $userPassword = '';
    $databaseName = 'codinghackathon';
    $dbConnection = new mysqli($serverName, $userName, $userPassword, $databaseName);

    $message = "";

    //settings
    $settingsResult = mysqli_query($dbConnection, "SELECT * from Settings");
    $settings = mysqli_fetch_assoc($settingsResult);
    $membersPerTeam = $settings['MembersPerTeam'];

    //members dropdown
    $membersQuery = "SELECT * from TeamMembers";
    $membersResult = mysqli_query($dbConnection, $membersQuery);

    //teams dropdown
    $teamsQuery = "SELECT * from Teams";
    $teamsResult = mysqli_query($dbConnection, $teamsQuery);

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $memberID = (int) $_POST["memberID"];
        $destTeamID = (int) $_POST["destTeamID"];

        //check destination team size
        $countQuery = "SELECT count(*) as c FROM TeamMembers WHERE TeamID = $destTeamID";
        $countResult = mysqli_query($dbConnection, $countQuery);
        $countRow = mysqli_fetch_assoc($countResult);

        if ($countRow['c'] >= $membersPerTeam)
        {
            $message = "That team is full.";
        }
        else
        {
            $moveQuery = " UPDATE TeamMembers
                           SET TeamID = $destTeamID
                           WHERE MemberID = $memberID  ";
            if (mysqli_query($dbConnection, $moveQuery))
                $message = "Member moved successfully";
            else
                $message = "Error moving member";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Coding Hackathon - Move Member</title>
    <style>
        .errorMessage { color: #FF0000;}
    </style>
</head>

<body>

<h4>Coding Hackathon - Move Member Between Teams</h4>

<?php
        $teamsQuery1 = "SELECT * from Teams";
        $teamsResult1 = mysqli_query($dbConnection, $teamsQuery1);

        if (mysqli_num_rows($teamsResult1) == 0)
            echo "<p>No teams have been created yet.</p>";
        else
        {
            while($row = mysqli_fetch_assoc($teamsResult1))
            {
                $teamID = $row['TeamID'];
                $teamName = $row['TeamName'];
                echo "<h5>Team: ".$teamName."</h5>";
                echo "<table border='1'>";
                echo "<tr>
                        <th>Member ID</th>
                        <th>Forename</th>
                        <th>Surname</th>
                        <th>Date of Birth</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Primary Language</th>
                        <th>Experience Level</th>
                        <th>Previous Hackathons</th>
                      </tr>";

                $teammembersQuery = "SELECT * from TeamMembers WHERE TeamID = $teamID";
                $teammembersResult = mysqli_query($dbConnection, $teammembersQuery);

                if (mysqli_num_rows($teammembersResult) == 0)
                    echo "<tr><td colspan='9'>No members in this team yet</td></tr>";
                else
                {
                    while($member = mysqli_fetch_assoc($teammembersResult))
                    {
                        echo "<tr>
                                <td>".$member['MemberID']."</td>
                                <td>".$member['MemberForename']."</td>
                                <td>".$member['MemberSurname']."</td>
                                <td>".$member['MemberDOB']."</td>
                                <td>".$member['MemberEmail']."</td>
                                <td>".$member['MemberMobilePhone']."</td>
                                <td>".$member['PrimaryLanguage']."</td>
                                <td>".$member['ExperienceLevel']."</td>
                                <td>".$member['PreviousHackathons']."</td>
                              </tr>";
                    }
                }
                echo "</table><br>";
            }
        }
    ?>

<p class="errorMessage"><?php echo $message; ?></p>

<form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
  <table>
        <tr>
          <td>Select Member:</td>
          <td>
            <select name="memberID">
              <?php
                while($row = mysqli_fetch_assoc($membersResult))
                {
                    $id = $row['MemberID'];
                    $fullName = $row['MemberForename'] . " " . $row['MemberSurname'];
                    echo "<option value='$id'>$fullName</option>";
                }
              ?>
            </select>
          </td>
        </tr>

        <tr>
          <td>Select Destination Team:</td>
          <td>
              <select name="destTeamID">
              <?php
                  while($row = mysqli_fetch_assoc($teamsResult))
                  {
                      $id = $row['TeamID'];
                      $name = $row['TeamName'];
                      echo "<option value='$id'>$name</option>";
                  }
              ?>
          </select>
        </td>
      </tr>
  </table>
  <br>
  <input type="submit" value="Move Member">
    &nbsp;&nbsp;

 <a href="mainmenu.php">Back to Main Menu</a>

</form>

</body>
</html>