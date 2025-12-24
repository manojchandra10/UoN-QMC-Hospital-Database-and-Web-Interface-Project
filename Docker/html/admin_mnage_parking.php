<?php
session_start();
require("db.inc.php");

if (!isset($_SESSION["user"])) { header("Location: index.php"); }

$dashboard_page = "admin_home.php";
$dashboard_name = "Dashboard";

// parking permit Approve or reject form with permit number
if (isset($_POST["process_request"])) {
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    
    $req_id = $_POST["req_id"];
    $action = $_POST["action_choice"];
    $permit_num = $_POST["permit_num"];
    $reason = $_POST["reject_reason"];
    
    // query update
    $sql = "UPDATE parking_permit 
            SET status='$action', permit_number='$permit_num', admin_comment='$reason' 
            WHERE request_id='$req_id'";
            
    if(mysqli_query($conn, $sql)) {
        // Audit
        $audit_user = $_SESSION["user"];
        $admin_id = $_SESSION["id"];
        $log = "INSERT INTO audit_log (user_id, username, action_type, description) VALUES ('$admin_id', '$audit_user', 'PARKING UPDATE', 'Updated request $req_id to $action')";
        mysqli_query($conn, $log);
    }
    mysqli_close($conn);
    
    header("Location: admin_mnage_parking.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Parking</title>
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
        <div class="nav-links">
            <a href="<?php echo $dashboard_page; ?>" class="nav-item"><?php echo $dashboard_name; ?></a>

            <a href="logout.php" class="nav-btn btn-logout">Log Out</a>
        </div>

    </header>

    <main>
        <div class="dashboard-mode" style="margin: 40px auto; flex:1;">
            <h1>Manage Parking Requests</h1>
            <p><a href="admin_home.php">Back to Admin Dashboard</a></p>
            <hr/>

            <h3>Pending Requests</h3>
            
            <?php
            $conn = mysqli_connect($servername, $username, $password, $dbname);
            
            $sql = "SELECT parking_permit.*, doctor.firstname, doctor.lastname 
                    FROM parking_permit 
                    JOIN doctor ON parking_permit.doctor_id = doctor.staffno
                    WHERE parking_permit.status = 'Pending'";
                    
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                echo "<table style='width:100%; border-collapse:collapse;'>";
                echo "<tr style='background:#f2f2f2;'><th style='padding:10px; border:1px solid #ccc;'>Doctor</th> <th style='padding:10px; border:1px solid #ccc;'>Car Details</th> <th style='padding:10px; border:1px solid #ccc;'>Action</th></tr>";
                
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row["request_id"];
                    
                    echo "<tr>";
                    // Doctor Information
                    echo "<td style='border:1px solid #ccc; padding:10px;'><b>" . $row["firstname"] . " " . $row["lastname"] . "</b><br>ID: " . $row["doctor_id"] . "</td>";
                    
                    // Car info
                    echo "<td style='border:1px solid #ccc; padding:10px;'>Reg: " . $row["car_reg"] . "<br>Type: " . $row["permit_type"] . "</td>";
                    
                    // form
                    echo "<td style='border:1px solid #ccc; padding:10px;'>";
                    echo "<form method='POST' style='display:flex; flex-direction:column; gap:10px;'>";
                    echo "<input type='hidden' name='req_id' value='$id'>";
                    
                    echo "<select name='action_choice' required style='margin:0;'>
                            <option value='Approved'>Approve</option>
                            <option value='Rejected'>Reject</option>
                          </select>";
                          
                    echo "<input type='text' name='permit_num' placeholder='Permit # (If Approved)' style='margin:0;'>";
                    echo "<input type='text' name='reject_reason' placeholder='Reason (If Rejected)' style='margin:0;'>";
                    
                    echo "<input type='submit' name='process_request' value='Update Status' style='margin:0; padding:8px;'>";
                    echo "</form>";
                    echo "</td>";
                    
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No pending requests.</p>";
            }
            mysqli_close($conn);
            ?>
        </div>
    </main>

    <footer>
        Nottingham University Hospitals NHS Trust © 2025
    </footer>

</body>
</html>