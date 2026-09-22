<?php
    $serverName   = 'localhost';
    $userName     = 'root';
    $userPassword = '';
    $databaseName = 'codinghackathon';
    $dbConnection = new mysqli($serverName, $userName, $userPassword, $databaseName);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coding Hackathon - List Teams</title>
  </head>
  <body>
    <h4>Coding Hackathon - Teams and Members</h4>

    <?php
        $teamsQuery = "SELECT * from Teams";
        $teamsResult = mysqli_query($dbConnection, $teamsQuery);

        if (mysqli_num_rows($teamsResult) == 0)
            echo "<p>No teams have been created yet.</p>";
        else
        {
            while($row = mysqli_fetch_assoc($teamsResult)) //for each row/team we list the members
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

                $membersQuery = "SELECT * from TeamMembers WHERE TeamID = $teamID";
                $membersResult = mysqli_query($dbConnection, $membersQuery);

                if (mysqli_num_rows($membersResult) == 0)
                    echo "<tr><td colspan='9'>No members in this team yet</td></tr>"; //this message will spread across all columns of the table, so I set colspan to 9 because there are 9 columns in the table
                else
                {
                    while($member = mysqli_fetch_assoc($membersResult))
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
    <br>
    <a href="mainmenu.php">Back to Main Menu</a>

  </body>
</html>