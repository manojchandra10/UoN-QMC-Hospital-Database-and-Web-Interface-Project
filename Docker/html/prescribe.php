<?php
/**
 * * Code sources and references used:
 * - Database Interaction inspired and derived from coursework notes and labs
 * - W3Schools 
 * - Parts of Buttons, Navbar and Nav Menu ideas were taken from my personal website (manojchandra.co.uk) and https://current-weather-hl4m.onrender.com/ and few other random websites
 * - Branding/Styling Inspiration: QMC Nottingham University Hospitals official public website (Blue/White color scheme, header structure etc)
 */
session_start();
require("db.inc.php");

//common dashboard for both admin and doctor (add patient or add a new pateint and prescibe)
$dashboard_page = "doctor_home.php";
$dashboard_name = "Dashboard";

if (isset($_SESSION["user"]) && $_SESSION["user"] == 'jelina') {
    $dashboard_page = "admin_home.php";
    $dashboard_name = "Dashboard";
}

$success = false;
$success_message = "";
$error_message = "";

// Security
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
}

// form prescribe button from dashboard
$p_nhs=""; $p_fname=""; $p_lname=""; $p_phone=""; $p_addr=""; $p_age=""; $p_gender="";
$is_existing_patient = false;

if (isset($_GET["pid"])) {
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    $target = mysqli_real_escape_string($conn, $_GET["pid"]);
    $res = mysqli_query($conn, "SELECT * FROM patient WHERE NHSno='$target'");
    
    if($r = mysqli_fetch_assoc($res)) {
        $p_nhs = $r['NHSno'];
        $p_fname = $r['firstname'];
        $p_lname = $r['lastname'];
        $p_phone = $r['phone'];
        $p_addr = $r['address'];
        $p_age = $r['age'];
        $p_gender = $r['gender'];
        $is_existing_patient = true;
    }
    mysqli_close($conn);
}

// prescribtion form submission
if (isset($_POST["save_prescription"])) {
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // patient Data
    $nhs    = $_POST["nhs_no"];
    $fname  = $_POST["fname"];
    $lname  = $_POST["lname"];
    $phone  = $_POST["phone"];
    $addr   = $_POST["address"];
    $age    = $_POST["age"];
    $gender = $_POST["gender"];
    
    // fecthing the test data
    $test_id = $_POST["test_choice"] ?? ""; 
    $new_test = trim($_POST["new_test_name"]);

    $doctor_id = $_SESSION["id"];
    $date      = date("Y-m-d");

    if (empty($test_id) && empty($new_test)) {
        $error_message = "Select a test from the list or type a new test name.";
    } 
    else {
        // adding a new test if not exist already in the list above
        if (!empty($new_test)) {
            
            // if it already exists
            $check_test = mysqli_query($conn, "SELECT * FROM test WHERE testname = '$new_test'");
            if (mysqli_num_rows($check_test) > 0) {
                $row = mysqli_fetch_assoc($check_test);
                $test_id = $row['testid'];
            } else {
                // get new ID and insert
                $max = mysqli_query($conn, "SELECT MAX(testid) as maxid FROM test");
                $row = mysqli_fetch_assoc($max);
                $new_id = $row['maxid'] + 1;
                
                $insert_test = "INSERT INTO test (testid, testname) VALUES ($new_id, '$new_test')";
                mysqli_query($conn, $insert_test);
                
                $test_id = $new_id; 
            }
        }

        // add new patient
        $check_pat = mysqli_query($conn, "SELECT * FROM patient WHERE NHSno = '$nhs'");
        
        if (mysqli_num_rows($check_pat) == 0) {
            // iif patient not in DB. Insert new ones
            $add_pat = "INSERT INTO patient (NHSno, firstname, lastname, phone, address, age, gender) 
                        VALUES ('$nhs', '$fname', '$lname', '$phone', '$addr', '$age', '$gender')";
            
            if(mysqli_query($conn, $add_pat)) {
                // audit the new patient
                $audit_user = $_SESSION["user"];
                $log_pat = "INSERT INTO audit_log (user_id, username, action_type, description) 
                            VALUES ('$doctor_id', '$audit_user', 'ADD PATIENT', 'Added new patient $fname $lname ($nhs)')";
                mysqli_query($conn, $log_pat);
            } else {
                $error_message = "Error adding patient: " . mysqli_error($conn);
            }
        }

        // prescribe
        if (empty($error_message)) {
            $sql = "INSERT INTO patient_test (pid, testid, doctorid, date, report) 
                    VALUES ('$nhs', '$test_id', '$doctor_id', '$date', 'Pending')";

            if (mysqli_query($conn, $sql)) {
                // audit prescription
                $audit_user = $_SESSION["user"];
                $log_sql = "INSERT INTO audit_log (user_id, username, action_type, description) 
                            VALUES ('$doctor_id', '$audit_user', 'PRESCRIBE', 'Prescribed Test ID $test_id for Patient $nhs')";
                mysqli_query($conn, $log_sql);

                $success = true;
                $success_message = "Prescription saved successfully for <b>$fname $lname</b>.";
            } else {
                if (mysqli_errno($conn) == 1062) {
                    $error_message = "This patient is already prescribed this test for today.";
                } else {
                    $error_message = "Database Error: " . mysqli_error($conn);
                }
            }
        }
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Prescribe Test</title>
    <link rel="stylesheet" href="css/main.css">
    <style> main { display: block; height: auto; padding: 0; } </style>
</head>
<body>

    <header>
        <a href="<?php echo $dashboard_page; ?>" class="logo">
            <span class="logo-box">NHS</span> 
            <div style="display: flex; flex-direction: column; line-height: 1.1;">
                <span>Queen's Medical Centre</span>
                <span>Nottingham University Hospital</span>
                <span style="font-size: 10px;">NHS Trust</span>
            </div>
        </a>
        <div class="nav-links">
            <a href="<?php echo $dashboard_page; ?>" class="nav-item"><?php echo $dashboard_name; ?></a>
            <a href="logout.php" class="nav-btn btn-logout">Log Out</a>
        </div>
    </header>
    <!-- <div class="dashboard-container"> -->
    <div class="dashboard-mode" style="display:flex; flex-direction:column; align-items:center;">

        <?php if ($success): ?>
            <div class="login-card" style="text-align:center;">
                <h1 style="color:#007f3b; margin-top:0;"> Prescription Done!</h1>
                <p style="font-size: 1.2em; font-weight: normal; margin-bottom: 25px;"><?php echo $success_message; ?></p>
                <a href="<?php echo $dashboard_page; ?>" style="background:#005eb8; color:white; padding:15px 30px; border-radius:4px; text-decoration:none; display:inline-block; font-size:18px;">
                    Return to Dashboard
                </a>
            </div>
        <?php else: ?>
            
            <h1 style="align-self: flex-start;">Prescribe a Test</h1>
            <p style="align-self: flex-start;"><a href="<?php echo $dashboard_page; ?>">Back to Dashboard</a></p>
            <hr style="width:100%;"/>

            <?php if ($error_message): ?>
                <div style="width:100%; max-width:700px; background:#ffe6e6; border-left:5px solid #d5281b; padding:15px; margin-bottom:20px; color:#d5281b; font-weight:bold;">
                     <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" style="background:#fff; padding:30px; border:1px solid #ddd; border-radius:4px; width: 100%; max-width: 700px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                
                <h3 style="margin-top:0; color:#005eb8; border-bottom:1px solid #eee; padding-bottom:10px;">Patient Details</h3>

                <div style="display:flex; gap:20px;">
                    <div style="flex:1;">
                        <label style="font-weight:bold;">NHS Number:</label><br>
                        <input type="text" name="nhs_no" value="<?php echo $p_nhs; ?>" required <?php if($is_existing_patient) echo "readonly style='background:#f0f4f5;'"; ?>>
                    </div>
                    <div style="flex:1;">
                        <label>Phone:</label><br>
                        <input type="text" name="phone" value="<?php echo $p_phone; ?>" required>
                    </div>
                </div>

                <div style="display:flex; gap:20px;">
                    <div style="flex:1;">
                        <label>First Name:</label><br>
                        <input type="text" name="fname" value="<?php echo $p_fname; ?>" required>
                    </div>
                    <div style="flex:1;">
                        <label>Last Name:</label><br>
                        <input type="text" name="lname" value="<?php echo $p_lname; ?>" required>
                    </div>
                </div>

                <label>Address:</label><br>
                <input type="text" name="address" value="<?php echo $p_addr; ?>" required>

                <div style="display:flex; gap:20px;">
                    <div style="flex:1;">
                        <label>Age:</label><br>
                        <input type="number" name="age" value="<?php echo $p_age; ?>" required>
                    </div>
                    <div style="flex:1;">
                        <label>Gender:</label><br>
                        <input type="text" name="gender" value="<?php echo $p_gender; ?>" required>
                    </div>
                </div>

                <h3 style="margin-top:30px; color:#005eb8; border-bottom:1px solid #eee; padding-bottom:10px;">Select Test</h3>
                
                <label style="font-weight:bold;">Select Existing Test:</label><br>
                <select name="test_choice" style="background:#fff; width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
                    <option value="" disabled selected>Select a Test...</option>
                    <?php
                    $conn = mysqli_connect($servername, $username, $password, $dbname);
                    $sql = "SELECT * FROM test ORDER BY testname ASC";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row["testid"] . "'>" . $row["testname"] . "</option>";
                    }
                    mysqli_close($conn);
                    ?>
                </select>

                <div style="margin: 10px 0; color:#777;">If a test is not in the list:</div>

                <label style="font-weight:bold; color:#005eb8;">Create New Test Type:</label><br>
                <input type="text" name="new_test_name" placeholder="e.g. MRI Scan (write down a new test type to add to dropdown list)" style="border-color:#005eb8;">
                
                <hr style="margin: 30px 0;">

                <input type="submit" name="save_prescription" value="Prescribe" style="padding:15px; font-size:18px;">
            </form>
        <?php endif; ?>

    </div>

    <footer>
        Nottingham University Hospitals NHS Trust © 2025
    </footer>

</body>
</html>