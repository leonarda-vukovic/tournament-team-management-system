<?php
    $serverName   = 'localhost';
    $userName     = 'root';
    $userPassword = '';
    $databaseName = 'codinghackathon';
    $dbConnection = new mysqli($serverName, $userName, $userPassword, $databaseName);

    $teamNameError = "";
    $teamName = "";
    $validInputs = true;

    //for the dropdown menu
    $teamsQuery = "SELECT * from Teams";
    $teamsResult = mysqli_query($dbConnection, $teamsQuery);

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $selectedTeamID = (int) $_POST["teamID"]; //getting the team ID of the team we want to edit from the dropdown menu
        if (empty($_POST["teamName"]))
        {
            $teamNameError = "Please enter a name of the team";
            $validInputs = false;
        }
        else
        {
            $teamName = $_POST["teamName"];
            if (!preg_match("/^[a-zA-Z ]*$/", $teamName)) //making sure the new team name  also satisfies the validation rules ( only letters allowed and not empty)
            {
                $teamNameError = "Enter alphabetic characters only!";
                $validInputs = false;
            }
            else
            {
                // check if name already taken by another team
                $checkQuery = "SELECT * from Teams WHERE TeamName = '$teamName' 
                               AND TeamID != $selectedTeamID";
                $checkResult = mysqli_query($dbConnection, $checkQuery);
                if (mysqli_num_rows($checkResult) > 0)
                {
                    $teamNameError = "That Team Name already exists, please choose another one";
                    $validInputs = false;
                }
            }
        }

        if ($validInputs) //if eveerything is correct we can update the team name in the database
        {
            $updateQuery = "UPDATE Teams SET TeamName = '$teamName' 
                            WHERE TeamID = $selectedTeamID";
            if (mysqli_query($dbConnection, $updateQuery))
                $teamNameError = "Team name updated successfully";
            else
                $teamNameError = "Error updating team name, please try again";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coding Hackathon - Edit Team</title>
    <style>
      .errorMessage { color: #FF0000; }
    </style>
  </head>
  <body>
    <h4>Coding Hackathon - Edit Team Name</h4>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
      <table>
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
        </tr>
        <tr>
          <td>New Team Name:</td>
          <td><input type="text" name="teamName" value="<?php echo $teamName;?>"></td>
          <td><span class="errorMessage"><?php echo $teamNameError;?></span></td>
        </tr>
      </table>
      <br>
      <input type="submit" value="Edit Team Name">
      &nbsp;&nbsp;
      <a href="mainmenu.php">Back to Main Menu</a>
    </form>


  </body>
</html>