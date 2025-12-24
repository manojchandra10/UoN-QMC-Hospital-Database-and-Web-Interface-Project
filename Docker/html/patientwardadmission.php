<?php
/**
 * Source and references:
 * 
 */
session_start();
require("db.inc.php");

if (!isset($_SESSION["user"])) { header("Location: index.php"); }

$dashboard_page = "doctor_home.php";
$dashboard_name = "Dashboard";

if ($_SESSION["user"] == 'jelina') {
    $dashboard_page = "admin_home.php";
    $dashboard_name = "Dashboard";
}

$message = "";
$patient_id = "";
//admitting the patients 
// Patient ID from URL
if (isset($_GET["pid"])) {
    $patient_id = $_GET["pid"];
}

// Form
if (isset($_POST["save_admission"])) {
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    $pid     = $_POST["pid"];
    $ward_id = $_POST["ward_choice"];
    $doc_id  = $_SESSION["id"];
    $date    = date("Y-m-d");
    $time    = date("H:i:s");
    
    $sql = "INSERT INTO wardpatientaddmission (pid, wardid, consultantid, date, time, status) 
            VALUES ('$pid', '$ward_id', '$doc_id', '$date', '$time', 1)";

    if (mysqli_query($conn, $sql)) {
        // Audit Log
        $audit_user = $_SESSION["user"];
        $log = "INSERT INTO audit_log (user_id, username, action_type, description) 
                VALUES ('$doc_id', '$audit_user', 'ADMIT PATIENT', 'Admitted patient $pid to Ward $ward_id')";
        mysqli_query($conn, $log);

        $message = "<div style='background:#e6fffa; color:#007f3b; padding:15px; border-left:5px solid #007f3b; font-weight:bold; margin-bottom:20px;'>
                    Patient admitted sucessfully!.
                    </div>";
    } else {
        $message = "<h3 style='color:red;'>Error: " . mysqli_error($conn) . "</h3>";
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admit Patient</title>
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

    <div class="dashboard-mode">
        <h1>Admit Patient to Ward</h1>
        <p><a href="<?php echo $dashboard_page; ?>">Back to Dashboard</a></p>
        <hr/>

        <?php echo $message; ?>

        <form method="POST" style="background:#fff; padding:30px; border:1px solid #ddd; border-radius:4px; max-width:600px;">
            
            <label style="font-weight:bold;">Patient NHS Number:</label><br>
            <input type="text" name="pid" value="<?php echo $patient_id; ?>" readonly style="background:#f0f4f5; color:#555;">
            <br><br>

            <label style="font-weight:bold;">Select Ward:</label><br>
            <select name="ward_choice" style="background:white;">
                <?php
                // connectmysql and show ward list
                $conn = mysqli_connect($servername, $username, $password, $dbname);
                $sql = "SELECT * FROM ward";
                $result = mysqli_query($conn, $sql);
                
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row["wardid"] . "'>" . $row["wardname"] . "</option>";
                }
                mysqli_close($conn);
                ?>
            </select>
            <br><br>

            <input type="submit" name="save_admission" value="Confirm Admission" style="padding:15px; font-size:18px;">
        </form>

    </div>

    <footer>
        Nottingham University Hospitals NHS Trust © 2025
    </footer>

</body>
</html>