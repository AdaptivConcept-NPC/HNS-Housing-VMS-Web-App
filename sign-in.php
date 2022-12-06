<?php
    session_start();
    include("scripts/config.php");

    //Connection Test==============================================>
        // Check connection
        /*if ($dbconn->connect_error) {
            die("<div class='p-4 alert alert-danger'>Connection failed: " . $db->connect_error) . "</div>";
        } else {
            die("Connected successfully");
        }*/
        
    //end of Connection Test============================================>
    
    //Declaring variables
    $username = mysql_fix_string($dbconn,$_POST['signInInputUsername']);
    $password = mysql_fix_string($dbconn,$_POST['signInInputPassword']);

    function mysql_fix_string($dbconn, $string) {
        if(get_magic_quotes_gpc()) $string = stripslashes($string);
        return $dbconn->real_escape_string($string);
    }

    $query = "SELECT * FROM admin_user_settings WHERE admin_user_username = '$username' AND admin_user_password = '$password'";
    $result = $dbconn->query($query);
    if(!$result) die("A Fatal Error has occured. Please try again and if the problem persists, please contact the system administrator.");

    $rows = $result->num_rows;

    if($rows==0) {
        //there is no result so notify user that the account cannot be found
        echo "The Username and Password you have provided may be incorrect or may not exist. Please check your inputs and try again.";
    } else {
        for ($j = 0; $j < $rows ; ++$j) {
            $row = $result->fetch_array(MYSQLI_ASSOC);

            //user id to pass to the Visitation Log Page as a GET param, which will be used as the value of sign in by field when capturing a new visit
            $user_id = htmlspecialchars($row['admin_user_settings_id']);
        }

        $result->close();
        $dbconn->close();

        //navigate visitation register/log
        header("Location: app/visit-register-app.html?userauth=true&id=$user_id");

    }

    
?>