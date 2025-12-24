<?php
/**
 * * Code sources and references:
 * - Database Interaction derived from coursework material and Labs (e.g. login check etc)
 * - W3Schools
 * - Branding/Styling Inspiration: QMC Nottingham University Hospitals official public website (Blue/White color scheme, header structure etc)
 */

session_start();
error_reporting(E_ALL);
ini_set('display_errors',1);

$loginerror = FALSE;

if (isset($_POST["username"]) && isset($_POST["password"]))
{
    require("db.inc.php");

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if(mysqli_connect_errno()) {
       echo "Connection fail: ".mysqli_connect_error();
       die();
    }

    $user = $_POST["username"];
    $pass = $_POST["password"];

    $query = "SELECT * from doctor WHERE username='$user' AND password='$pass'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) 
    {
        $row = mysqli_fetch_assoc($result);

        $_SESSION["user"] = $user;
        $_SESSION["id"] = $row["staffno"];
        $_SESSION["name"] = $row["firstname"];

        // AUDIT TRAIL
        $auditID = $row["staffno"];
        $auditUser = $row["username"];
        $auditSQL = "INSERT INTO audit_log (user_id, username, action_type, description) VALUES ('$auditID', '$auditUser', 'LOGIN', 'User Logged In')";
        mysqli_query($conn, $auditSQL);

        mysqli_close($conn);

        if ($user == 'jelina') {
             header("Location: admin_home.php");
        } else {
             header("Location: doctor_home.php");
        }
        exit();
    }
    else 
    {
        $loginerror = TRUE;
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - NHS Nottingham</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>

    <header>
        <!-- <a href="#" class="logo">
            <span class="logo-box">NHS</span> 
            Nottingham University Hospitals <br> NHS Trust
        </a> -->
        <a href="#" class="logo">
            <span class="logo-box">NHS</span> 
            <div style="display: flex; flex-direction: column; line-height: 1.1;">
                <span>Queen's Medical Centre</span>
                <span>Nottingham University Hospital</span>
                <span style="font-size: 10px;">NHS Trust</span>
            </div>
        </a>
        <div class="nav-links">
            <a href="#" class="nav-btn btn-login">Log in</a> 
        </div>
    </header>
    <!-- HAMBURGER MENU FOR Smaller SCreen SIZES for responsive screens-->
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
                <li><a href="#" target="_blank" onclick="toggleMenu()">
                    Log In
                    </a> -->
                <!-- </li> -->
                <!-- <li><a href="#linkedin" onclick="toggleMenu()">LinkedIn</a></li> -->
            <!-- </div>
        </div>
    </nav> -->

    <main>
        <div class="login-card">
            <h1 style="color:#005eb8; margin-top:0;">Sign in</h1>
            <p style="margin-bottom:20px;">Please enter your username and password to access the QMC System.</p>

            <?php
               if ($loginerror) {
                  echo "<div style='background:#ffe6e6; border-left:5px solid #d5281b; padding:10px; margin-bottom:15px; color:#d5281b; font-weight:bold;'>Invalid Username or Password</div>";
               }
            ?>

            <form method="POST">
               <label style="font-weight:bold;">Username</label>
               <input name="username" type="text" required/>

               <label style="font-weight:bold;">Password</label>
               <input name="password" type="password" required/>

               <input type="Submit" value="Log in"/>
            </form>
        </div>
    </main>

    <footer>
        Nottingham University Hospitals NHS Trust © 2025
    </footer>

</body>
</html>