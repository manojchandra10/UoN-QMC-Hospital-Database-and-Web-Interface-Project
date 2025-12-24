<?php
/**
 * * Code sources and references:
 * - Database Interaction derived from  coursework notes and labs
 * - W3Schools and bootstrap
 * - Branding/Styling Inspiration: QMC Nottingham University Hospitals official public website (Blue/White color scheme, header structure etc)
 */
session_start();
if (!isset($_SESSION["user"])) { header("Location: index.php"); }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
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
            
            <a href="admin_mnage_parking.php" class="nav-item">Manage Parking</a>
            <a href="add_doctor.php" class="nav-item">Add a Doctor</a>
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
                <!-- <li><a href="admin_home.php" onclick="toggleMenu()">Dashboard</a></li> -->
                <!-- <li><a href="admin_mnage_parking.php" onclick="toggleMenu()" target="_blank">
                    Manage Parking
                    </a>
                </li>
                <li><a href="logout.php" target="_blank" onclick="toggleMenu()">
                    Log Out
                    </a>  -->
                <!-- </li> -->
                <!-- <li><a href="#linkedin" onclick="toggleMenu()">LinkedIn</a></li> -->
            <!-- </div>
        </div>
    </nav> -->

    <div class="dashboard-mode">
        <h1>Admin Dashboard</h1>
        <p>Administrator: <b><?php echo $_SESSION["name"]; ?></b></p>
        <hr style="margin: 30px 0; border: 0; border-top: 1px solid #ddd;">

        <h3>System Management</h3>
        <ul style="list-style:none; padding:0;">
            <li style="margin-bottom:10px;">
                <a href="audit_trail.php" style="display:block; background:#f2f2f2; padding:15px; border-left:5px solid #005eb8; text-decoration:none; color:#333;">
                    <span style="font-weight:bold; color:#005eb8;"><img src="assets/audit-log.png" style="width:14px; height:14px; margin-right:5px;">View Audit Log</span><br>
                    Monitor system access and security logs.
                </a>
            </li>
            <li style="margin-bottom:10px;">
                <a href="admin_mnage_parking.php" style="display:block; background:#f2f2f2; padding:15px; border-left:5px solid #005eb8; text-decoration:none; color:#333;">
                    
                    <span style="font-weight:bold; color:#005eb8;"><img src="assets/parking.png" style="width:13px; height:13px; margin-right:5px;">Manage Parking Requests</span><br>
                    Approve or reject staff parking permits.
                </a>
            </li>
            <li style="margin-bottom:10px;">
                <a href="add_doctor.php" style="display:block; background:#f2f2f2; padding:15px; border-left:5px solid #005eb8; text-decoration:none; color:#333;">
                    <span style="font-weight:bold; color:#005eb8;"><img src="assets/doctor.png" style="width:13px; height:13px; margin-right:5px;">Add New Doctor</span><br>
                    Create a new account for medical staff.
                </a>
            </li>
            <li style="margin-bottom:10px;">
                <a href="register_new_patient.php" style="display:block; background:#f2f2f2; padding:15px; border-left:5px solid #005eb8; text-decoration:none; color:#333;">
                    <span style="font-weight:bold; color:#005eb8;"><img src="assets/patient.png" style="width:13px; height:13px; margin-right:5px;">Register New Patient</span><br>
                    Add a new patient record to the system.
                </a>
            </li>
            <li style="margin-bottom:10px;">
                <a href="prescribe.php" style="display:block; background:#f2f2f2; padding:15px; border-left:5px solid #005eb8; text-decoration:none; color:#333;">
                    <span style="font-weight:bold; color:#005eb8;"><img src="assets/prescription.png" style="width:13px; height:13px; margin-right:5px;">Prescribe a Patient</span><br>
                    Admins can do everything a doctor can do.
                </a>
            </li>

        </ul>
        
        <!-- <h3>Prescribe a Patient</h3>
        <p><i>As an admin, you can access standard doctor tools:</i></p>
        <a href="doctor_home.php" style="background:#005eb8; color:white; padding:10px 15px; border-radius:4px; text-decoration:none;">Go to Doctor Dashboard</a> -->
    </div>

    <footer>
        Nottingham University Hospitals NHS Trust © 2025
    </footer>

</body>
</html>