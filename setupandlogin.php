<?php
    //first creating the tables if they don't already exist, then handling login 
    $serverName   = 'localhost';
    $userName     = 'root';
    $userPassword = '';
    $databaseName = 'codinghackathon';
    $dbConnection = new mysqli($serverName, $userName, $userPassword, $databaseName);
    
    if (!$dbConnection)
        echo "Error: Unable to Connect";
    else
    {
        //I decided to have 4 tables: Members, Teams, TeamMembers ( to link members to teams), and Settings (to store initialization settings)
        $createTable1 = "CREATE TABLE IF NOT EXISTS Members( MemberID int(3) unsigned Primary Key)";
        $createTable2 = "CREATE TABLE IF NOT EXISTS Teams( TeamID int(2) unsigned AUTO_INCREMENT Primary Key, TeamName varchar(30) not null unique)"; 
        $createTable3 = "CREATE TABLE IF NOT EXISTS TeamMembers( MemberID int(3) unsigned,  TeamID int(2) unsigned, MemberForename varchar(20) not null,
                                                                 MemberSurname varchar(35) not null, MemberDOB date not null, MemberEmail varchar(35) not null,
                                                                 MemberMobilePhone varchar(10) not null, PrimaryLanguage varchar(20) not null, ExperienceLevel varchar(20) not null,
                                                                 PreviousHackathons varchar(10) not null, primary key(MemberID, TeamID))";
        $createTable4 = "CREATE TABLE IF NOT EXISTS Settings( MembersPerTeam int(2) not null, MaxTeams int(2) not null)";
    
        if (!mysqli_query($dbConnection, $createTable1))
            echo "Members table failed to create<br>";
        if (!mysqli_query($dbConnection, $createTable2))
            echo "Teams table failed to create<br>";
        if (!mysqli_query($dbConnection, $createTable3))
            echo "TeamMembers table failed to create<br>";
        if (!mysqli_query($dbConnection, $createTable4))
            echo "Settings table failed to create<br>";

        //deleting any existing data in the tables, so that we start with a clean slate every time we load the login page
        $deleteQuery = "DELETE FROM Members"; 
        mysqli_query($dbConnection, $deleteQuery);
        $deleteQuery = "DELETE FROM Teams"; 
        mysqli_query($dbConnection, $deleteQuery);
        $deleteQuery = "DELETE FROM TeamMembers"; 
        mysqli_query($dbConnection, $deleteQuery);
        $deleteQuery = "DELETE FROM Settings"; 
        mysqli_query($dbConnection, $deleteQuery);
    }

    //login part:

    //some variables for handling error messages and storing user input
    $username = $password = "";
    $usernameError = $passwordError = $incorrectDetails ="";
    $validInputs = true;

    //hardcoding correct username and password as required
    $correctUsername = "ID";     
    $correctPassword = "Password"; 

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (empty($_POST["username"]))
        {
            $usernameError = "Please enter your username";
            $validInputs = false;
        }

        if (empty($_POST["password"]))
        {
            $passwordError = "Please enter your password";
            $validInputs = false;
        }

        if ($validInputs)
        {
            if ($_POST["username"] == $correctUsername && $_POST["password"] == $correctPassword)
                header("Location: loadmembers.php"); //if the user gets it correct, we send them to the initialization page
            else
            {
                $incorrectDetails = "Invalid username or password";
                $validInputs = false;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Coding Hackathon - Admin Login</title>
    <style>
      .errorMessage { color: #FF0000; }
    </style>
  </head>
  <body>
    <h4>Coding Hackathon - Admin Login</h4>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>"> <!-- we must use POST method because password is sensitive information -->
      <table>
        <tr>
          <td>Username:</td>
          <td><input type="text" name="username"></td>
          <td><span class="errorMessage"><?php echo $usernameError;?></span></td>
        </tr>
        <tr>
          <td>Password:</td>
          <td><input type="password" name="password"></td>
          <td><span class="errorMessage"><?php echo $passwordError;?></span></td>
        </tr>
      </table>
      <span class="errorMessage"><?php echo $incorrectDetails;?></span>
      <br>
      <input type="submit" value="Login">
    </form>
  </body>
</html>
