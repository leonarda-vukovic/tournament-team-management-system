<?php
    $serverName   = 'localhost';
    $userName     = 'root';
    $userPassword = '';
    $databaseName = 'codinghackathon';
    $dbConnection = new mysqli($serverName, $userName, $userPassword, $databaseName);

    $message = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        //getting the values from the form
        $membersPerTeam = (int) $_POST["membersPerTeam"];
        $maxTeams = (int) $_POST["maxTeams"];
        $insertSettings = "INSERT into Settings values($membersPerTeam, $maxTeams)";
        if (mysqli_query($dbConnection, $insertSettings))
            header("Location: mainmenu.php");
        else
            $message = "Error saving settings, please try again";
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coding Hackathon - Initialize Settings</title>
    <style>
      .errorMessage { color: #FF0000; }
    </style>
  </head>
  <body>
    <h4>Coding Hackathon - Initialize Settings</h4>

    <p>Members loaded successfully. Now select the hackathon settings.</p>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
      <table>
        <tr>
          <td>Members per Team:</td>
          <td>
            <select name="membersPerTeam">
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>Maximum Number of Teams:</td>
          <td>
            <select name="maxTeams">
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
            </select>
          </td>
        </tr>
      </table>
      <span class="errorMessage"><?php echo $message;?></span>
      <br>
      <input type="submit" value="Save Settings">
    </form>

  </body>
</html>