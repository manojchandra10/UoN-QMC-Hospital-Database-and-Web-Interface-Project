<?php
/**
 * * Code sources and references:
 * - Database connections and interaction and derived from course materials notes and labs
 * - W3Schools
 *
 * - Branding/Styling Inspiration: QMC Nottingham University Hospitals official public website (Blue/White color scheme, header structure etc)
 */
session_start();
require("db.inc.php");

if (!isset($_SESSION["user"])) {header("Location: index.php");}

//common dashboard for both admin and doctor
$dashboard_page = ($_SESSION["user"] == 'jelina') ? "admin_home.php" : "doctor_home.php";

$msg = "";

if (isset($_POST["submit_patient"])) {
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // fetch data
    $nhs    = $_POST["nhs_no"];
    $fname  = $_POST["fname"];
    $lname  = $_POST["lname"];
    $phone  = $_POST["phone"];
    $addr   = $_POST["address"];
    $age    = $_POST["age"];
    $gender = $_POST["gender"];

    // check if patient exists
    $check = mysqli_query($conn, "SELECT * FROM patient WHERE NHSno = '$nhs'");
    
    if (mysqli_num_rows($check) > 0) {
        $msg = "<div style='background:#ffe6e6; color:#d5281b; padding:10px; border-left:5px solid #d5281b; font-weight:bold; margin-bottom:20px;'>
                Error: A patient with NHS Number $nhs already exists!
                </div>";
    } else {
        // Insert info for new patient
        $sql = "INSERT INTO patient (NHSno, firstname, lastname, phone, address, age, gender) 
                VALUES ('$nhs', '$fname', '$lname', '$phone', '$addr', '$age', '$gender')";
        
        if (mysqli_query($conn, $sql)) {
            // Audit
            $audit_user = $_SESSION["user"];
            $doc_id = $_SESSION["id"];
            $log = "INSERT INTO audit_log (user_id, username, action_type, description) VALUES ('$doc_id', '$audit_user', 'ADD PATIENT', 'Registered new patient $fname $lname ($nhs)')";
            mysqli_query($conn, $log);

            $msg = "<div style='background:#e6fffa; color:#007f3b; padding:15px; border-left:5px solid #007f3b; font-weight:bold; margin-bottom:20px; text-align:center;'>
                    Patient Registered Successfully!.<br>
                    <a href='prescribe.php?pid=$nhs' style='color:#005eb8; text-decoration:underline;'>Click here to Prescribe a Test</a>
                    </div>";
        } else {
            $msg = "<p style='color:red;'>Database Error: " . mysqli_error($conn) . "</p>";
        }
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register New Patient</title>
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
            <a href="<?php echo $dashboard_page; ?>" class="nav-item">Dashboard</a>
            <a href="logout.php" class="nav-btn btn-logout">Log Out</a>
        </div>
    </header>

    <div class="dashboard-mode">
        <h1>Register New Patient</h1>
        <p><a href="<?php echo $dashboard_page; ?>">Back to Dashboard</a></p>
        <hr/>

        <?php echo $msg; ?>

        <form method="POST" style="background:#fff; padding:30px; border:1px solid #ddd; border-radius:4px; max-width:700px; margin:0 auto;">
            
            <h3 style="margin-top:0; color:#005eb8; border-bottom:1px solid #eee; padding-bottom:10px;">Patient Details</h3>

            <div style="display:flex; gap:20px;">
                <div style="flex:1;">
                    <label style="font-weight:bold;">NHS Number:</label><br>
                    <input type="text" name="nhs_no" required>
                </div>
                <div style="flex:1;">
                    <label>Phone:</label><br>
                    <input type="text" name="phone" required>
                </div>
            </div>

            <div style="display:flex; gap:20px;">
                <div style="flex:1;">
                    <label>First Name:</label><br>
                    <input type="text" name="fname" required>
                </div>
                <div style="flex:1;">
                    <label>Last Name:</label><br>
                    <input type="text" name="lname" required>
                </div>
            </div>

            <label>Address:</label><br>
            <input type="text" name="address" required>

            <div style="display:flex; gap:20px;">
                <div style="flex:1;">
                    <label>Age:</label><br>
                    <input type="number" name="age" required>
                </div>
                <div style="flex:1;">
                    <label>Gender:</label><br>
                    <select name="gender" required style="padding: 10px; width: 100%; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="" disabled selected>Select Gender</option>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                    </select>
                </div>
            </div>

            <hr style="margin: 30px 0;">

            <input type="submit" name="submit_patient" value="Register Patient" style="padding:15px; font-size:18px;">
        </form>
    </div>

    <footer>
        Nottingham University Hospitals NHS Trust © 2025
    </footer>

</body>
</html>