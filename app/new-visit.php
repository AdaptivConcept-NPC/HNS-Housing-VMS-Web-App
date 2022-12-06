<?php
  session_start();
  include("../scripts/config.php");

  //Connection Test==============================================>
      // Check connection
      /*if ($dbconn->connect_error) {
          die("<div class='p-4 alert alert-danger'>Connection failed: " . $db->connect_error) . "</div>";
      } else {
          die("Connected successfully");
      }*/
      
  //end of Connection Test============================================>

  //Initialize Global Variable
  $Visitor_Name = mysql_fix_string($dbconn, $_POST['new-visitor-name-input']);
  $Visitor_Surname = mysql_fix_string($dbconn, $_POST['new-visitor-surname-input']);
  $Visitor_IDNumber = mysql_fix_string($dbconn, $_POST['new-visitor-idnum-input']);
  $Visitor_PhoneNumber = mysql_fix_string($dbconn, $_POST['new-visitor-phone-input']);
  $Visitor_Gender = mysql_fix_string($dbconn, $_POST['new-visitor-gender-input']);
  $Visitor_Nationality = mysql_fix_string($dbconn, $_POST['new-visitor-nationality-input']);
  $Visitor_SignedInBy = mysql_fix_string($dbconn, $_POST['new-visitor-sib-input']);
  $Visitor_StartDate = mysql_fix_string($dbconn, $_POST['new-visitor-startdate-input']);
  $Visitor_EndDate = mysql_fix_string($dbconn, $_POST['new-visitor-enddate-input']);

  /*echo "Name: ".$Visitor_Name."<br>";
  echo "Surname: ".$Visitor_Surname."<br>";
  echo "ID Num: ".$Visitor_IDNumber."<br>";
  echo "Phone Num: ".$Visitor_PhoneNumber."<br>";
  echo "Gender: ".$Visitor_PhoneNumber."<br>";
  echo "Nationality: ".$Visitor_Nationality."<br>";
  echo "SIB: ".$Visitor_SignedInBy."<br>";
  echo "Start Date: ".$Visitor_StartDate."<br>";
  echo "End Date: ".$Visitor_EndDate."<br>";*/

  function mysql_fix_string($dbconn, $string) {
      if(get_magic_quotes_gpc()) $string = stripslashes($string);
      return $dbconn->real_escape_string($string);
  }

  try {
    //(`visitors_log_id`, `visitors_name`, `visitors_surname`, `visitors_id_number`, `visitors_phone_number`, `visitors_gender`, `visitors_nationality`, `signed_in_by`, `visit_start_date`, `visit_end_date`) 
    $insertquery = "INSERT INTO visitors_log VALUES (NULL,'$Visitor_Name','$Visitor_Surname','$Visitor_IDNumber','$Visitor_PhoneNumber','$Visitor_Gender','$Visitor_Nationality','$Visitor_SignedInBy','$Visitor_StartDate','$Visitor_EndDate')";

    $result = $dbconn->query($insertquery);

    if(!$result) die("Database Access Failed. Please contact the System Administrator. Error: [ ".$dbconn -> error." ]");

    header("Location: visit-register-app.html?return=new_record_saved_success");
  } catch (\Throwable $err) {
    //throw $err;
    echo "Error( $err )";
  }
  
?>