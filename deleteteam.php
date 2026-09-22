<?php
    $serverName   = 'localhost';
    $userName     = 'root';
    $userPassword = '';
    $databaseName = 'codinghackathon';
    $dbConnection = new mysqli($serverName, $userName, $userPassword, $databaseName);

    $message = "";

    $teamsQuery = "SELECT * from Teams";
    $teamsResult = mysqli_query($dbConnection, $teamsQuery);

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $selectedTeamID = (int) $_POST["teamID"];

        //deleting members of team first
        $deleteMembersQuery = "DELETE from TeamMembers WHERE TeamID = $selectedTeamID";
        mysqli_query($dbConnection, $deleteMembersQuery);

        //then deleting the team
        $deleteTeamQuery = "DELETE from Teams WHERE TeamID = $selectedTeamID";
        if (mysqli_query($dbConnection, $deleteTeamQuery))
            $message = "Team deleted successfully";
        else
            $message = "Error deleting team, please try again";
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coding Hackathon - Delete Team</title>
    <style>
      .errorMessage { color: #FF0000; }
    </style>
  </head>
  <body>
    <h4>Coding Hackathon - Delete Team</h4>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
      <table>
        <tr>
          <td>Select Team to Delete:</td>
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
        </tr>
      </table>
      <br>
      <span class="errorMessage"><?php echo $message;?></span>
      <br>
      <input type="submit" value="Delete Team">
      &nbsp;&nbsp;
      <a href="mainmenu.php">Back to Main Menu</a>
    </form>


  </body>
</html>