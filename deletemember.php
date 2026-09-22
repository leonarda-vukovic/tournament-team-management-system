<?php
    $serverName   = 'localhost';
    $userName     = 'root';
    $userPassword = '';
    $databaseName = 'codinghackathon';

    $dbConnection = new mysqli($serverName, $userName, $userPassword, $databaseName);

    $message = "";

    //getting all members for dropdown
    $membersQuery = "SELECT * from TeamMembers";
    $membersResult = mysqli_query($dbConnection, $membersQuery);

    //delete member
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $selectedMemberID = $_POST["memberID"];
        $deleteQuery = "DELETE from TeamMembers WHERE MemberID = $selectedMemberID";
        if (mysqli_query($dbConnection, $deleteQuery))
            $message = "Member deleted successfully";
        else
            $message = "Error deleting member";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coding Hackathon - Delete Member</title>
    <style>
        .errorMessage { color: #FF0000; }
    </style>
</head>
<body>
    <h4>Coding Hackathon - Delete Member</h4>

    <!-- I reused this part from listteams.php to show teams and members so that admin can see from which team they are deleting a member -->
    <?php
        $teamsQuery = "SELECT * from Teams";
        $teamsResult = mysqli_query($dbConnection, $teamsQuery);

        if (mysqli_num_rows($teamsResult) == 0)
            echo "<p>No teams have been created yet.</p>";
        else
        {
            while($row = mysqli_fetch_assoc($teamsResult))
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
                    echo "<tr><td>No members in this team yet</td></tr>";
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
      </table>
      <br>
      <span class="errorMessage"><?php echo $message;?></span>
      <br>
      <input type="submit" value="Delete Member">
      &nbsp;&nbsp;
      <a href="mainmenu.php">Back to Main Menu</a>
    </form>
</body>
</html>