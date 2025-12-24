<?php
/**
 * * Code sources and references:
 * - Database Interaction derived from coursework notes and labs
 * - W3Schools and bootstrap
 * - Branding/Styling Inspiration: QMC Nottingham University Hospitals official public website (Blue/White color scheme, header structure etc)
 */
session_start();
require("db.inc.php");

if (!isset($_SESSION["user"])) { header("Location: index.php"); }

$msg = "";

if (isset($_POST["submit_doctor"])) {
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // get data
    $staffno = $_POST["staffno"];
    $fname   = $_POST["firstname"];
    $lname   = $_POST["lastname"];
    $user    = $_POST["username"];
    $pass    = $_POST["password"];
    $addr    = $_POST["address"];
    $spec    = $_POST["specialisation"]; 
    $pay     = $_POST["pay"]; 
    $status  = $_POST["status"]; 

    $sql = "INSERT INTO doctor (staffno, firstname, lastname, specialisation, pay, consultantstatus, address, username, password) 
            VALUES ('$staffno', '$fname', '$lname', '$spec', '$pay', '$status', '$addr', '$user', '$pass')";

    if (mysqli_query($conn, $sql)) {
        // Audit
        $audit_user = $_SESSION["user"];
        $admin_id = $_SESSION["id"];
        $log = "INSERT INTO audit_log (user_id, username, action_type, description) VALUES ('$admin_id', '$audit_user', 'ADD DOCTOR', 'Created account for Dr. $lname ($staffno)')";
        mysqli_query($conn, $log);
        
        $msg = "<p style='color:green; font-weight:bold;'>Doctor account created successfully!</p>";
    } else {
        $msg = "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Doctor</title>
    <link rel="stylesheet" href="css/main.css">
    <style> main { display: block; height: auto; padding: 0; } </style>
</head>
<body>

    <header>
        <a href="admin_home.php" class="logo">
            <span class="logo-box">NHS</span> 
            <div style="display: flex; flex-direction: column; line-height: 1.1;">
                <span>Queen's Medical Centre</span>
                <span>Nottingham University Hospital</span>
                <span style="font-size: 10px;">NHS Trust</span>
            </div>
        </a>
        <div class="nav-links">
            <a href="admin_home.php" class="nav-item">Dashboard</a>
            
            <a href="logout.php" class="nav-btn btn-logout">Log Out</a>
        </div>
    </header>

    <div class="dashboard-mode">
        <h1>Add New Doctor</h1>
        <p><a href="admin_home.php">Back to Admin Dashboard</a></p>
        <hr/>

        <?php echo $msg; ?>

        <form method="POST" style="background:#f9f9f9; padding:20px; border:1px solid #ddd; border-radius:4px; max-width:600px;">
            <label>Staff No (e.g. DOC99):</label><br><input type="text" name="staffno" required><br>
            <label>First Name:</label><br><input type="text" name="firstname" required><br>
            <label>Last Name:</label><br><input type="text" name="lastname" required><br>
            
            <div style="display:flex; gap:10px;">
                <div style="flex:1;"><label>Username:</label><br><input type="text" name="username" required></div>
                <div style="flex:1;"><label>Password:</label><br><input type="text" name="password" required></div>
            </div>

            <label>Address:</label><br><input type="text" name="address" required><br>
            <label>Pay (£):</label><br><input type="number" name="pay" value="45000" required><br>

            <label>Specialisation:</label><br>
            <select name="specialisation">
                <option value="0">None</option><option value="1">Specialist</option>
            </select><br>

            <label>Status:</label><br>
            <select name="status">
                <option value="0">Junior</option><option value="1">Senior</option>
            </select><br>

            <input type="submit" name="submit_doctor" value="Create Account" style="margin-top:20px;">
        </form>
    </div>

    <footer>
        Nottingham University Hospitals NHS Trust © 2025
    </footer>

</body>
</html>