<?php
    $serverName   = 'localhost';
    $userName     = 'root';
    $userPassword = '';
    $databaseName = 'codinghackathon';
    $dbConnection = new mysqli($serverName, $userName, $userPassword, $databaseName); //here we are connecting to the database

    //initializing some variabless 
    $teamNameError = "";
    $teamName = "";
    $validInputs = true;

    //first we need to read from the database whats the max number of teams
    $query1 = "SELECT * from Settings"; //just saving the query in a variable 
    $queryResult = mysqli_query($dbConnection, $query1); //saving the result in a variable, data not readable yet
    $settingsRow = mysqli_fetch_assoc($queryResult); //fetching the result as an associative array
    /*$settings = [ "MembersPerTeam" => 3, "MaxTeams" => 4 ]; //this is what the settings array could look like */
    $maxTeams = $settingsRow['MaxTeams']; //getting the max teams value from the settings array

    //now we need to check how many teams currently exist in the database
    $query2 = "SELECT count(*) as teamCount from Teams"; //I added "as teamCount" so that I can easily access it
    $queryResult2 = mysqli_query($dbConnection, $query2);
    $countRow = mysqli_fetch_assoc($queryResult2);
    $currentTeams = $countRow['teamCount'];

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (empty($_POST["teamName"])) //checking if its empty
        {
            $teamNameError = "Please enter a name of the team";
            $validInputs = false;
        }
        else
        {
            $teamName = $_POST["teamName"]; //storing user input in a variable
            if (!preg_match("/^[a-zA-Z ]*$/", $teamName)) //I decided to allow only words in team names
            {
                $teamNameError = "Enter alphabetic characters only!";
                $validInputs = false;
            }
            else
            {
                $query3 = "SELECT * from Teams WHERE TeamName = '$teamName'"; 
                $queryResult3 = mysqli_query($dbConnection, $query3);
                if (mysqli_num_rows($queryResult3) > 0) //there is supposed to be zero rows as result of the query3 ,if there is at least one row it means the team name already exists
                {
                    $teamNameError = "That Team Name already exists, please choose another one";
                    $validInputs = false;
                }
            }
        }

        //after validation we can insert the team into the database if there is still space for more teams
        if ($validInputs)
        {
            if ($currentTeams >= $maxTeams)
            {
                $teamNameError = "Maximum number of teams already reached";
            }
            else
            {
                $insertTeam = "INSERT into Teams (TeamName) values('$teamName')";
                if (mysqli_query($dbConnection, $insertTeam))
                    $teamNameError = "Team created successfully";
                else
                    $teamNameError = "Error creating team, please try again";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Coding Hackathon - Create Team</title>
    <style>
      .errorMessage { color: #FF0000; }
    </style>
  </head>
  <body>
    <h4>Coding Hackathon - Create Team</h4>

    <p>Teams created: <?php echo $currentTeams; ?> / <?php echo $maxTeams; ?></p>
    <p class="errorMessage"><?php echo $teamNameError; ?></p>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
      <table>
        <tr>
          <td>Team Name:</td>
          <td><input type="text" name="teamName" value="<?php echo $teamName; ?>"></td>
        </tr>
      </table>
      <br>
      <input type="submit" value="Create Team">
      &nbsp;&nbsp;
      <a href="mainmenu.php">Back to Main Menu</a>
    </form>
  </body>
</html>