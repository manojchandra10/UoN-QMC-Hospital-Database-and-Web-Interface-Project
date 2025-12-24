<?php
/**
 * * Code sources and references:
 * - Database Interaction and connections, forms etc derived from coursework material notes and labs
 * - W3Schools and bootstrap
 * - UI ideas Inspiration: QMC Nottingham University Hospitals official public website (Blue/White color scheme, header structure etc)
 */

session_start();
if (!isset($_SESSION["user"])) { header("Location: index.php"); }

// Common dashboard for both doctor and admin
$dashboard_page = "doctor_home.php";
$dashboard_name = "Dashboard";

if ($_SESSION["user"] == 'jelina') {
    $dashboard_page = "admin_home.php";
    $dashboard_name = "Dashboard";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="css/main.css">
    <style> main { display: flex; flex-direction: column; flex: 1; } </style>
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
        <!-- <div class="nav-container"> -->
        <div class="nav-links">
            <a href="<?php echo $dashboard_page; ?>" class="nav-item"><?php echo $dashboard_name; ?></a>
            <a href="parking.php" class="nav-item">Parking at QMC</a>
            <a href="update_profile.php" class="nav-item">My Profile</a>
            <a href="register_new_patient.php" class="nav-item">Register <br> New Patient</a>
            <a href="logout.php" class="nav-btn btn-logout">Log Out</a>
        </div>
    </header>
        <!-- HAMBURGER MENU FOR Smaller SCreen SIZES -->
    <!-- <nav id="hamburger-nav">
        <div class="logo-box">NHS</div>
        <div class="hamburger-menu">
            <div class="hamburger-icon" onclick="toggleMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div> -->
            <!-- <div class="menu-links"> -->
                <!-- <li><a href="doctor.php" onclick="toggleMenu()">Dashboard</a></li> -->
                <!-- <li><a href="parking.php" onclick="toggleMenu()" target="_blank">Parking at QMC</a>
                </li>
                <li><a href="update_profile" onclick="toggleMenu()" target="_blank">
                    My Profile
                    </a>
                </li>
                <li><a href="logout.php" target="_blank" onclick="toggleMenu()">
                    Log Out
                    </a> -->
                <!-- </li> -->
                <!-- <li><a href="#linkedin" onclick="toggleMenu()">LinkedIn</a></li> -->
            <!-- </div>
        </div>
    </nav> -->

    <main>
        <!-- <div class="dashboard-container"> -->
        <div class="dashboard-mode" style="flex:1; margin: 40px auto;">
            <h1>Welcome, Dr. <?php echo $_SESSION["name"]; ?></h1>
            <p>Queen's Medical Centre Staff Portal</p>
            <hr style="margin: 30px 0; border: 0; border-top: 1px solid #ddd;">
            
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                <h3 style="color:#005eb8; margin:0;">Search for a Patient</h3>
                <a href="register_new_patient.php" style="background:#007f3b; color:white; padding:12px 20px; border-radius:4px; text-decoration:none; font-weight:bold; font-size:14px; box-shadow: 0 2px 0 #005a2b;">
                    + Register New Patient
                </a>
            </div>

            <div style="background:#f0f4f5; padding:20px; border-left: 5px solid #005eb8;">
                <form method="POST">
                    <label><b>Patient Name or NHS Number:</b></label>
                    <div style="display:flex; gap:15px; align-items: flex-start;">
                        <input type="text" name="search_text" placeholder="e.g. Zoya" style="margin:0; flex:1;">
                        <input type="submit" value="Search" style="width:auto; margin:0; padding:12px 30px;">
                    </div>
                </form>
            </div>

            <br>

            <?php
            if (isset($_POST["search_text"])) {
                require("db.inc.php");
                $conn = mysqli_connect($servername, $username, $password, $dbname);
                $search = $_POST["search_text"];
                $sql = "SELECT * FROM patient WHERE firstname LIKE '%$search%' OR lastname LIKE '%$search%' OR NHSno LIKE '%$search%'";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    echo "<p>Found " . mysqli_num_rows($result) . " patient(s):</p>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        $nhs = $row["NHSno"];
                        echo "<div style='border:1px solid #ccc; padding:20px; margin-bottom:15px; border-radius:4px;'>";
                        echo "<div style='display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;'>";
                        echo "<h2 style='margin:0; color:#212b32;'>" . $row["firstname"] . " " . $row["lastname"] . "</h2>";
                        
                        // Admit button and prescribe botton
                        echo "<div style='display:flex; gap:10px;'>";
                        echo "<a href='patientwardadmission.php?pid=$nhs' style='background:#003087; color:white; padding:10px 15px; border-radius:4px; text-decoration:none; font-weight:bold; font-size:14px;'>Admit</a>";
                        echo "<a href='prescribe.php?pid=$nhs' style='background:#005eb8; color:white; padding:10px 15px; border-radius:4px; text-decoration:none; font-weight:bold; font-size:14px;'>Prescribe Test</a>";
                        echo "</div>";
                        
                        echo "</div>";
                        echo "NHS No: <b>" . $row["NHSno"] . "</b> | Phone: " . $row["phone"];
                        echo "<div style='margin-top:15px; padding:10px; background:#f9f9f9; font-size:14px;'>";
                        
                        // Ward
                        echo "<b>Admissions:</b> ";
                        $w_sql = "SELECT * FROM wardpatientaddmission JOIN ward ON wardpatientaddmission.wardid=ward.wardid WHERE pid='$nhs'";
                        $w_res = mysqli_query($conn, $w_sql);
                        if(mysqli_num_rows($w_res)>0){ while($w=mysqli_fetch_assoc($w_res)){ echo $w['wardname']." (".$w['date']."), "; }} else { echo "None"; }
                        
                        // Tests
                        echo "<br><b>Tests:</b> ";
                        $t_sql = "SELECT * FROM patient_test JOIN test ON patient_test.testid=test.testid WHERE pid='$nhs'";
                        $t_res = mysqli_query($conn, $t_sql);
                        if(mysqli_num_rows($t_res)>0){ while($t=mysqli_fetch_assoc($t_res)){ echo $t['testname']." (".$t['date']."), "; }} else { echo "None"; }
                        
                        // patientexaminations
                        echo "<br><b>Patient Examinations:</b> ";
                        $exam_sql = "SELECT * FROM patientexamination 
                                    JOIN doctor ON patientexamination.doctorid = doctor.staffno 
                                    WHERE patientid='$nhs' ORDER BY date DESC";
                        $exam_res = mysqli_query($conn, $exam_sql);

                        if (mysqli_num_rows($exam_res) > 0) {
                            while ($ex = mysqli_fetch_assoc($exam_res)) {
                                echo "Dr. " . $ex['lastname'] . " (" . $ex['date'] . "), ";
                            }
                        } else {
                            echo "None";
                        }
                        echo "</div></div>";
                    }
                } else {
                    echo "<p style='color:#d5281b; font-weight:bold;'>No patients found.</p>";
                }
            }
            ?>
        </div>
    </main>

    <footer>
        Nottingham University Hospitals NHS Trust © 2025
    </footer>

</body>
</html>