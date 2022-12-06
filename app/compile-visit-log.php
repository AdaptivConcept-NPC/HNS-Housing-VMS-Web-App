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
$entryPointVal = mysql_fix_string($dbconn, $_GET['entry']);
//echo $entryPointVal."<br>";

function mysql_fix_string($dbconn, $string) {
    if(get_magic_quotes_gpc()) $string = stripslashes($string);
    return $dbconn->real_escape_string($string);
}

if (isset($entryPointVal)) {
  
  if ($entryPointVal == "pageinit") {
    //initializing the Shops Product List
    try {
      //compile list
      $query = "SELECT * FROM visitors_log ORDER BY visitors_log_id DESC";

      $result = $dbconn->query($query);

      if(!$result) die("A Fatal Error has occured. Please reload the page, and if the problem persists, please contact the system administrator. Error: [ ".$dbconn -> error. " ]");

      $rows = $result->num_rows;
      //echo $rows."<br>";
      $comp_visit_log_list = "";

      if($rows<=0) {
          //there is no result so notify user that the account cannot be found
          echo '<tr><td colspan="17"><h1 class="fw-bold text-center my-4">No visitations to display.</h1></td></tr>';
      } else {
        for ($j = 0; $j < $rows ; ++$j) {
          $row = $result->fetch_array(MYSQLI_ASSOC);

          $visitor_id = htmlspecialchars($row['visitors_log_id']);
          $visitor_name = htmlspecialchars($row['visitors_name']);
          $visitor_surname = htmlspecialchars($row['visitors_surname']);
          $visitor_idnum = htmlspecialchars($row['visitors_id_number']);
          $visitor_phone = htmlspecialchars($row['visitors_phone_number']);
          $visitor_gender = htmlspecialchars($row['visitors_gender']);
          $visitor_nationality = htmlspecialchars($row['visitors_nationality']);
          $visitor_signedinby = htmlspecialchars($row['signed_in_by']);
          $visitor_startdate = htmlspecialchars($row['visit_start_date']);
          $visitor_enddate = htmlspecialchars($row['visit_end_date']);

          $comp_visit_log_list .= '<tr>
              <td class="text-center table-secondary">
                <button class="btn btn-secondary p-3 rounded-pill mb-2 shadow" onclick="showModal('."'$visitor_id'".')" style="font-size: 30px"><i class="fas fa-edit"></i></button>
                <span style="font-size: 10px">Edit</span>
              </td>
              <td class="text-center table-danger">
                <button class="btn btn-danger p-3 rounded-pill mb-2 shadow" onclick="deleteVisitRecord('."'$visitor_id'".')" style="font-size: 30px"><i class="fas fa-trash-alt"></i></button>
                <span style="font-size: 10px">Delete</span>
              </td>

              <td scope="row" class="fw-bold">'.$visitor_id.'</td>
              <td>'.$visitor_name.'</td>
              <td>'.$visitor_surname.'</td>
              <td>'.$visitor_idnum.'</td>
              <td>'.$visitor_phone.'</td>
              <td>'.$visitor_gender.'</td>
              <td>'.$visitor_nationality.'</td>
              <td>'.$visitor_signedinby.'</td>
              <td>'.$visitor_startdate.'</td>
              <td>'.$visitor_enddate.'</td>
            </tr>';
        }

        $result->close();
        $dbconn->close();
        
        //navigate user to the Equipment Catalogue
      }

      echo $comp_visit_log_list;
    } catch (\Throwable $err) {
      //throw $err;
      echo "An Error has Occured: [ ".$err." ]";
    }
  }
}
?>