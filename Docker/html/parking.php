<?php
session_start();
require("db.inc.php");

if (!isset($_SESSION["user"])) {
    header("Location: index.php");
}

// theform
if (isset($_POST["submit_parking"])) {
    
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    $car = $_POST["car_reg"];
    $type = $_POST["permit_type"];
    $doc_id = $_SESSION["id"];
    
    // Insert with Pending status
    $sql = "INSERT INTO parking_permit (doctor_id, car_reg, permit_type, status) 
            VALUES ('$doc_id', '$car', '$type', 'Pending')";

    if (mysqli_query($conn, $sql)) {
        // Audit Log
        $audit_user = $_SESSION["user"];
        $log_sql = "INSERT INTO audit_log (user_id, username, action_type, description) 
                    VALUES ('$doc_id', '$audit_user', 'PARKING REQUEST', 'Requested $type permit for car $car')";
        mysqli_query($conn, $log_sql);
        
        // show new status
        header("Location: parking.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request Parking</title>
    <link rel="stylesheet" href="css/main.css">
    <style> main { display: block; height: auto; padding: 0; } </style>
    
    <script>
        function updateFee() {
            var type = document.getElementById("permit_select").value;
            var price = "£0.00";
            
            if (type == "Monthly") {
                price = "£20.00";
            } else if (type == "Yearly") {
                price = "£200.00";
            }
            
            document.getElementById("fee_box").value = price;
        }
    </script>
</head>
<body>

    <header>
        <a href="doctor_home.php" class="logo">
            <span class="logo-box">NHS</span> 
            Nottingham University Hospitals <br> NHS Trust
        </a>
        <div class="nav-links">
            <a href="doctor_home.php" class="nav-item">Dashboard</a>
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
        <h1>Request Parking Permit</h1>
        <p><a href="doctor_home.php">Back to Dashboard</a></p>
        <hr/>

        <form method="POST" style="background:#f9f9f9; padding:20px; border:1px solid #ddd; border-radius:4px; margin-bottom: 40px; max-width: 600px;">
            <h3 style="margin-top:0;">New Request</h3>
            
            <label>Car Registration Number:</label><br>
            <input type="text" name="car_reg" required placeholder="e.g. AB12 CDE">
            <br>

            <label>Permit Type:</label><br>
            <select name="permit_type" id="permit_select" onchange="updateFee()" required>
                <option value="">-- Select Option --</option>
                <option value="Monthly">Monthly Pass</option>
                <option value="Yearly">Yearly Pass</option>
            </select>
            <br>
            
            <label>Fee Payable:</label><br>
            <input type="text" id="fee_box" value="£0.00" readonly style="background:#e8f4fd; font-weight:bold; color:#005eb8;">
            <br>

            <input type="submit" name="submit_parking" value="Submit Request">
        </form>

        <h3>My Permit Requests</h3>
        <?php
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        $my_id = $_SESSION["id"];
        
        $sql = "SELECT * FROM parking_permit WHERE doctor_id = '$my_id' ORDER BY request_date DESC";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            echo "<table style='width:100%;'>";
            echo "<tr style='background:#005eb8; color:white;'> <th style='padding:10px;'>Date</th> <th>Car</th> <th>Type</th> <th>Status</th> </tr>";
            
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr style='border-bottom:1px solid #ccc;'>";
                echo "<td style='padding:10px;'>".$row['request_date']."</td>";
                echo "<td>".$row['car_reg']."</td>";
                echo "<td>".$row['permit_type']."</td>";
                
                $status = $row['status'];
                $color = 'black';
                if($status == 'Approved') $color = '#007f3b';
                if($status == 'Rejected') $color = '#d5281b';
                
                echo "<td style='font-weight:bold; color:$color;'>$status</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>You have not made any request yet.</p>";
        }
        mysqli_close($conn);
        ?>

    </div>

    <footer>
        Nottingham University Hospitals NHS Trust © 2025
    </footer>

</body>
</html>