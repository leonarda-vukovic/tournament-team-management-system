<?php
    $serverName   = 'localhost';
    $userName     = 'root';
    $userPassword = '';
    $databaseName = 'codinghackathon';
    $dbConnection = new mysqli($serverName, $userName, $userPassword, $databaseName);

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        //loading members from file, used code from class
        $filePointer = fopen("members.txt", "r");
        if ($filePointer)
        {
            $counter = 0;
            while(!feof($filePointer))
            {
                $lineFromFile = fgets($filePointer);
                $memberIDToInsert = (int) $lineFromFile;
                $insertMember = "INSERT into Members values($memberIDToInsert)";
                $counter += 1;
                if (!mysqli_query($dbConnection, $insertMember))
                    echo "Error inserting record " . $counter . "<br>";     
            }
            fclose($filePointer);
            header("Location: initialize.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coding Hackathon -Load Fully-Paid Members</title>
    <style>
      .errorMessage { color: #FF0000; }
    </style>
  </head>
  <body>
    <h4>Coding Hackathon - Load Fully-Paid Members</h4>

    <p>Click the button below to load fully-paid members from file.</p>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
        <input type="submit" value="Load Fully-Paid Members"> 
    </form>

  </body>
</html>