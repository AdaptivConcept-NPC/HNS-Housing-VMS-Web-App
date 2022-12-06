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

  function mysql_fix_string($dbconn, $string) {
      if(get_magic_quotes_gpc()) $string = stripslashes($string);
      return $dbconn->real_escape_string($string);
  }

  $output_visitor_signedinby = 1;

  //reu this update code if a post request is made to save the updated data
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Initialize Global Variable
    $post_visit_id = mysql_fix_string($dbconn, $_POST['visit-id']);
    $post_Visitor_Name = mysql_fix_string($dbconn, $_POST['update-visitor-name-input']);
    $post_Visitor_Surname = mysql_fix_string($dbconn, $_POST['update-visitor-surname-input']);
    $post_Visitor_IDNumber = mysql_fix_string($dbconn, $_POST['update-visitor-idnum-input']);
    $post_Visitor_PhoneNumber = mysql_fix_string($dbconn, $_POST['update-visitor-phone-input']);
    $post_Visitor_Gender = mysql_fix_string($dbconn, $_POST['update-visitor-gender-input']);
    $post_Visitor_Nationality = mysql_fix_string($dbconn, $_POST['update-visitor-nationality-input']);
    $post_Visitor_SignedInBy = mysql_fix_string($dbconn, $_POST['update-visitor-sib-input']);
    $post_Visitor_StartDate = mysql_fix_string($dbconn, $_POST['update-visitor-startdate-input']);
    $post_Visitor_EndDate = mysql_fix_string($dbconn, $_POST['update-visitor-enddate-input']);

    if (isset($post_visit_id)) {
      try {
        //Update Record
        $query = "UPDATE `visitors_log` SET `visitors_name`='$post_Visitor_Name',`visitors_surname`='$post_Visitor_Surname',`visitors_id_number`='$post_Visitor_IDNumber',`visitors_phone_number`='$post_Visitor_PhoneNumber',`visitors_gender`='$post_Visitor_Gender',`visitors_nationality`='$post_Visitor_Nationality',`signed_in_by`='$post_Visitor_SignedInBy',`visit_start_date`='$post_Visitor_StartDate',`visit_end_date`='$post_Visitor_EndDate' WHERE `visitors_log_id` = $post_visit_id";

        $result = $dbconn->query($query);

        if(!$result) die("Database Access Failed. Please contact the System Administrator.");

        //$result->close();
        //$dbconn->close();

        echo '<div class="alert alert-success fw-bold">success</div>';
        //Navigate to the Success Notification
        header("Location: edit-success.html");
      } catch (\Throwable $err) {
        //throw $err;
        echo '<div class="alert alert-danger fw-bold">An Error has Occured: [ '.$err.' ]</div>';
      }
    }
  } else {
    //Get the visit details of the id GET Param
    //Initialize Global Variable
    $visit_id = mysql_fix_string($dbconn, $_GET['id']);

    try {
      //Get the visit record of the id GET Param
      $query = "SELECT * FROM visitors_log WHERE visitors_log_id=$visit_id";

      $result = $dbconn->query($query);

      if(!$result) die("A Fatal Error has occured. Please reload the page, and if the problem persists, please contact the system administrator.");

      $rows = $result->num_rows;

      if($rows<=0) {
          //there is no result so notify user that the account cannot be found
          echo '<div class="alert alert-info">Ooops, this visitation cannot be found. Please search for another visit or contact the System Administrator if this an error or the issue persists.</div>';
      } else {
        for ($j = 0; $j < $rows ; ++$j) {
          $row = $result->fetch_array(MYSQLI_ASSOC);

          $output_visitor_id = htmlspecialchars($row['visitors_log_id']);
          $output_visitor_name = htmlspecialchars($row['visitors_name']);
          $output_visitor_surname = htmlspecialchars($row['visitors_surname']);
          $output_visitor_idnum = htmlspecialchars($row['visitors_id_number']);
          $output_visitor_phone = htmlspecialchars($row['visitors_phone_number']);
          $output_visitor_gender = htmlspecialchars($row['visitors_gender']);
          $output_visitor_nationality = htmlspecialchars($row['visitors_nationality']);
          $output_visitor_signedinby = htmlspecialchars($row['signed_in_by']);
          $output_visitor_startdate = htmlspecialchars($row['visit_start_date']);
          $output_visitor_enddate = htmlspecialchars($row['visit_end_date']);
        }

        $result->close();
        $dbconn->close();
      }
    } catch (\Throwable $err) {
      //throw $err;
      die("An Error has Occured: [ ".$err." ]");
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Edit Visitation Information | Happy Events Equipment Rentals</title>
    <!-- Required Meta Tags -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />

    <link rel="stylesheet" href="../styles/style.css" />

    <!--fontawesome-->
    <script src="https://kit.fontawesome.com/a2763a58b1.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Unica+One&display=swap" rel="stylesheet" />
  </head>
  <body>
    <div class="container" style="margin-bottom: 100px">
      <!-- Update visit Form -->
      

      <div id="admin-update-visit-info-form-container" class="shadow p-4 my-4 trans-container">
        <h1 class="mt-4 text-center">Update visit Account Details</h1>
        
        <form action="edit-visit.php?id=<?php echo $visit_id;?>" method="post" id="visit-registration-form" class="basic-form-style p-4 shadow fs-3">

              <div class="mb-3" hidden>
                <label for="visit-id" class="form-label">visit ID</label>
                <input type="number" class="form-control rounded-pill" id="visit-id" name="visit-id" value="<?php echo $output_visitor_id ;?>" />
              </div>
              <div class="mb-3">
                <label for="update-visitor-name-input" class="form-label">Visitors Name</label>
                <input type="text" class="form-control rounded-pill" id="update-visitor-name-input" name="update-visitor-name-input" required value="<?php echo $output_visitor_name ;?>" />
              </div>
              <div class="mb-3">
                <label for="update-visitor-surname-input" class="form-label">Visitors Surname</label>
                <input type="text" class="form-control rounded-pill" id="update-visitor-surname-input" name="update-visitor-surname-input" required value="<?php echo $output_visitor_surname ;?>" />
              </div>
              <div class="mb-3">
                <label for="update-visitor-idnum-input" class="form-label">Visitors ID Number</label>
                <input type="number" class="form-control rounded-pill" id="update-visitor-idnum-input" name="update-visitor-idnum-input" required value="<?php echo $output_visitor_idnum ;?>" />
              </div>
              <div class="mb-3">
                <label for="update-visitor-phone-input" class="form-label">Visitors Phone Number</label>
                <input type="number" class="form-control rounded-pill" id="update-visitor-phone-input" name="update-visitor-phone-input" required value="<?php echo $output_visitor_phone ;?>" />
              </div>
              <div class="mb-3">
                <label for="update-visitor-gender-input" class="form-label">Visitors Gender</label>
                <select class="form-select form-select-lg mb-3" id="update-visitor-gender-input" name="update-visitor-gender-input" aria-label=".form-select-lg example" required value="<?php echo $output_visitor_gender ;?>" >
                  <option selected>-- Select --</option>
                  <option value="Male">Male</option>
                  <option value="Male">Female</option>
                  </select>
              </div>
              <div class="mb-3">
                <label for="update-visitor-nationality-input" class="form-label">Visitors Nationality</label>
                <select class="form-select form-select-lg mb-3" id="update-visitor-nationality-input" name="update-visitor-nationality-input" required value="<?php echo $output_visitor_nationality ;?>" >
                  <option selected>-- Select --</option>
                  <option value="Afghanistan">Afghanistan</option>
                  <option value="Albania">Albania</option>
                  <option value="Algeria">Algeria</option>
                  <option value="Andorra">Andorra</option>
                  <option value="Angola">Angola</option>
                  <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                  <option value="Argentina">Argentina</option>
                  <option value="Armenia">Armenia</option>
                  <option value="Australia">Australia</option>
                  <option value="Austria">Austria</option>
                  <option value="Azerbaijan">Azerbaijan</option>
                  <option value="Bahamas">Bahamas</option>
                  <option value="Bahrain">Bahrain</option>
                  <option value="Bangladesh">Bangladesh</option>
                  <option value="Barbados">Barbados</option>
                  <option value="Belarus">Belarus</option>
                  <option value="Belgium">Belgium</option>
                  <option value="Belize">Belize</option>
                  <option value="Benin">Benin</option>
                  <option value="Bhutan">Bhutan</option>
                  <option value="Bolivia">Bolivia</option>
                  <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                  <option value="Botswana">Botswana</option>
                  <option value="Brazil">Brazil</option>
                  <option value="Brunei">Brunei</option>
                  <option value="Bulgaria">Bulgaria</option>
                  <option value="Burkina Faso">Burkina Faso</option>
                  <option value="Burundi">Burundi</option>
                  <option value="Cabo Verde">Cabo Verde</option>
                  <option value="Cambodia">Cambodia</option>
                  <option value="Cameroon">Cameroon</option>
                  <option value="Canada">Canada</option>
                  <option value="Central African Republic">Central African Republic</option>
                  <option value="Chad">Chad</option>
                  <option value="Chile">Chile</option>
                  <option value="China">China</option>
                  <option value="Colombia">Colombia</option>
                  <option value="Comoros">Comoros</option>
                  <option value="Congo">Congo</option>
                  <option value="Costa Rica">Costa Rica</option>
                  <option value="Côte d'Ivoire">Côte d"Ivoire</option>
                  <option value="Croatia">Croatia</option>
                  <option value="Cuba">Cuba</option>
                  <option value="Cyprus">Cyprus</option>
                  <option value="Czech Republic (Czechia)">Czech Republic (Czechia)</option>
                  <option value="Denmark">Denmark</option>
                  <option value="Djibouti">Djibouti</option>
                  <option value="Dominica">Dominica</option>
                  <option value="Dominican Republic">Dominican Republic</option>
                  <option value="DR Congo">DR Congo</option>
                  <option value="Ecuador">Ecuador</option>
                  <option value="Egypt">Egypt</option>
                  <option value="El Salvador">El Salvador</option>
                  <option value="Equatorial Guinea">Equatorial Guinea</option>
                  <option value="Eritrea">Eritrea</option>
                  <option value="Estonia">Estonia</option>
                  <option value="Eswatini">Eswatini</option>
                  <option value="Ethiopia">Ethiopia</option>
                  <option value="Fiji">Fiji</option>
                  <option value="Finland">Finland</option>
                  <option value="France">France</option>
                  <option value="Gabon">Gabon</option>
                  <option value="Gambia">Gambia</option>
                  <option value="Georgia">Georgia</option>
                  <option value="Germany">Germany</option>
                  <option value="Ghana">Ghana</option>
                  <option value="Greece">Greece</option>
                  <option value="Grenada">Grenada</option>
                  <option value="Guatemala">Guatemala</option>
                  <option value="Guinea">Guinea</option>
                  <option value="Guinea-Bissau">Guinea-Bissau</option>
                  <option value="Guyana">Guyana</option>
                  <option value="Haiti">Haiti</option>
                  <option value="Holy See">Holy See</option>
                  <option value="Honduras">Honduras</option>
                  <option value="Hungary">Hungary</option>
                  <option value="Iceland">Iceland</option>
                  <option value="India">India</option>
                  <option value="Indonesia">Indonesia</option>
                  <option value="Iran">Iran</option>
                  <option value="Iraq">Iraq</option>
                  <option value="Ireland">Ireland</option>
                  <option value="Israel">Israel</option>
                  <option value="Italy">Italy</option>
                  <option value="Jamaica">Jamaica</option>
                  <option value="Japan">Japan</option>
                  <option value="Jordan">Jordan</option>
                  <option value="Kazakhstan">Kazakhstan</option>
                  <option value="Kenya">Kenya</option>
                  <option value="Kiribati">Kiribati</option>
                  <option value="Kuwait">Kuwait</option>
                  <option value="Kyrgyzstan">Kyrgyzstan</option>
                  <option value="Laos">Laos</option>
                  <option value="Latvia">Latvia</option>
                  <option value="Lebanon">Lebanon</option>
                  <option value="Lesotho">Lesotho</option>
                  <option value="Liberia">Liberia</option>
                  <option value="Libya">Libya</option>
                  <option value="Liechtenstein">Liechtenstein</option>
                  <option value="Lithuania">Lithuania</option>
                  <option value="Luxembourg">Luxembourg</option>
                  <option value="Madagascar">Madagascar</option>
                  <option value="Malawi">Malawi</option>
                  <option value="Malaysia">Malaysia</option>
                  <option value="Maldives">Maldives</option>
                  <option value="Mali">Mali</option>
                  <option value="Malta">Malta</option>
                  <option value="Marshall Islands">Marshall Islands</option>
                  <option value="Mauritania">Mauritania</option>
                  <option value="Mauritius">Mauritius</option>
                  <option value="Mexico">Mexico</option>
                  <option value="Micronesia">Micronesia</option>
                  <option value="Moldova">Moldova</option>
                  <option value="Monaco">Monaco</option>
                  <option value="Mongolia">Mongolia</option>
                  <option value="Montenegro">Montenegro</option>
                  <option value="Morocco">Morocco</option>
                  <option value="Mozambique">Mozambique</option>
                  <option value="Myanmar">Myanmar</option>
                  <option value="Namibia">Namibia</option>
                  <option value="Nauru">Nauru</option>
                  <option value="Nepal">Nepal</option>
                  <option value="Netherlands">Netherlands</option>
                  <option value="New Zealand">New Zealand</option>
                  <option value="Nicaragua">Nicaragua</option>
                  <option value="Niger">Niger</option>
                  <option value="Nigeria">Nigeria</option>
                  <option value="North Korea">North Korea</option>
                  <option value="North Macedonia">North Macedonia</option>
                  <option value="Norway">Norway</option>
                  <option value="Oman">Oman</option>
                  <option value="Pakistan">Pakistan</option>
                  <option value="Palau">Palau</option>
                  <option value="Panama">Panama</option>
                  <option value="Papua New Guinea">Papua New Guinea</option>
                  <option value="Paraguay">Paraguay</option>
                  <option value="Peru">Peru</option>
                  <option value="Philippines">Philippines</option>
                  <option value="Poland">Poland</option>
                  <option value="Portugal">Portugal</option>
                  <option value="Qatar">Qatar</option>
                  <option value="Romania">Romania</option>
                  <option value="Russia">Russia</option>
                  <option value="Rwanda">Rwanda</option>
                  <option value="Saint Kitts & Nevis">Saint Kitts & Nevis</option>
                  <option value="Saint Lucia">Saint Lucia</option>
                  <option value="Samoa">Samoa</option>
                  <option value="San Marino">San Marino</option>
                  <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                  <option value="Saudi Arabia">Saudi Arabia</option>
                  <option value="Senegal">Senegal</option>
                  <option value="Serbia">Serbia</option>
                  <option value="Seychelles">Seychelles</option>
                  <option value="Sierra Leone">Sierra Leone</option>
                  <option value="Singapore">Singapore</option>
                  <option value="Slovakia">Slovakia</option>
                  <option value="Slovenia">Slovenia</option>
                  <option value="Solomon Islands">Solomon Islands</option>
                  <option value="Somalia">Somalia</option>
                  <option value="South Africa">South Africa</option>
                  <option value="South Korea">South Korea</option>
                  <option value="South Sudan">South Sudan</option>
                  <option value="Spain">Spain</option>
                  <option value="Sri Lanka">Sri Lanka</option>
                  <option value="St. Vincent & Grenadines">St. Vincent & Grenadines</option>
                  <option value="State of Palestine">State of Palestine</option>
                  <option value="Sudan">Sudan</option>
                  <option value="Suriname">Suriname</option>
                  <option value="Sweden">Sweden</option>
                  <option value="Switzerland">Switzerland</option>
                  <option value="Syria">Syria</option>
                  <option value="Tajikistan">Tajikistan</option>
                  <option value="Tanzania">Tanzania</option>
                  <option value="Thailand">Thailand</option>
                  <option value="Timor-Leste">Timor-Leste</option>
                  <option value="Togo">Togo</option>
                  <option value="Tonga">Tonga</option>
                  <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                  <option value="Tunisia">Tunisia</option>
                  <option value="Turkey">Turkey</option>
                  <option value="Turkmenistan">Turkmenistan</option>
                  <option value="Tuvalu">Tuvalu</option>
                  <option value="Uganda">Uganda</option>
                  <option value="Ukraine">Ukraine</option>
                  <option value="United Arab Emirates">United Arab Emirates</option>
                  <option value="United Kingdom">United Kingdom</option>
                  <option value="United States">United States</option>
                  <option value="Uruguay">Uruguay</option>
                  <option value="Uzbekistan">Uzbekistan</option>
                  <option value="Vanuatu">Vanuatu</option>
                  <option value="Venezuela">Venezuela</option>
                  <option value="Vietnam">Vietnam</option>
                  <option value="Yemen">Yemen</option>
                  <option value="Zambia">Zambia</option>
                  <option value="Zimbabwe">Zimbabwe</option>
                </select>
              </div>
              <div class="mb-3" hidden>
                <label for="update-visitor-sib-input" class="form-label">Visitor Signed-In By</label>
                <input type="number" class="form-control rounded-pill" id="update-visitor-sib-input" name="update-visitor-sib-input" required  value="<?php echo $output_visitor_signedinby ;?>" />
              </div>
              <div class="mb-3">
                <label for="update-visitor-startdate-input" class="form-label">Visit Start Date</label>
                <input type="date" class="form-control rounded-pill" id="update-visitor-startdate-input" name="update-visitor-startdate-input" required value="<?php echo $output_visitor_startdate ;?>" />
              </div>
              <div class="mb-3">
                <label for="update-visitor-enddate-input" class="form-label">Visit End Date</label>
                <input type="date" class="form-control rounded-pill" id="update-visitor-enddate-input" name="update-visitor-enddate-input" required value="<?php echo $output_visitor_enddate ;?>" />
              </div>

              <div class="text-center">
                <button type="submit" class="btn btn-info btn-block rounded-0 fw-bold">
                  <span class="fs-1 m-4">Update Visitors Info <i class="fas fa-save"></i></span>
                </button>
              </div>
            </form>
      </div>
      <!-- ./ Update visit  Form -->
    </div>

    <div class="w-100 text-center fw-bold fixed-bottom my-nav py-4" style="font-size: 10px">
      <p class="m-0 p-0">Crafted by Thabang Mposula (8008999) &copy; 2021 | Systems Development 3 (HSYD300-1) SA1</p>
    </div>

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  </body>
</html>
