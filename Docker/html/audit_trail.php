<?php
session_start();
require("db.inc.php");

// Security Check
if (!isset($_SESSION["user"]) || $_SESSION["user"] != 'jelina') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Audit Trail</title>
    <link rel="stylesheet" href="css/main.css">
    <style> 
        main { display: block; height: auto; padding: 0; } 
        
        /* NHS Table Style */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
        th { background-color: #005eb8; color: white; padding: 12px; text-align: left; }
        td { border-bottom: 1px solid #ccc; padding: 10px; color: #333; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        tr:hover { background-color: #e6f2ff; }
    </style>
</head>
<body>

    <header>
        <a href="admin_home.php" class="logo">
            <span class="logo-box">NHS</span> 
            Nottingham University Hospitals <br> NHS Trust
        </a>
        <div class="nav-links">
            <a href="admin_home.php" class="nav-item">Dashboard</a>
            <a href="logout.php" class="nav-btn btn-logout">Log Out</a>
        </div>
    </header>

    <div class="dashboard-mode">
        <h1>System Audit Log</h1>
        <p><a href="admin_home.php">Back to Admin Dashboard</a></p>
        <hr/>

        <div style="background:#f0f4f5; padding:20px; border-left: 5px solid #005eb8; margin-bottom: 20px;">
            <form method="GET" style="display:flex; align-items:center; gap:10px;">
                <label style="font-weight:bold; margin:0;">Filter by Username:</label>
                <input type="text" name="user_filter" placeholder="e.g. mceards" style="margin:0; width:auto; flex:1;">
                <input type="submit" value="Filter Log" style="margin:0; width:auto; padding:10px 20px;">
                
            </form>
        </div>

        <?php
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        $sql = "SELECT * FROM audit_log";

        // Apply Filter
        if (isset($_GET['user_filter']) && $_GET['user_filter'] != "") {
            $filter = $_GET['user_filter'];
            $sql .= " WHERE username = '$filter'";
        }
        
        $sql .= " ORDER BY timestamp DESC";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr> <th>Time</th> <th>User ID</th> <th>Username</th> <th>Action</th> <th>Description</th> </tr>";
            
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row["timestamp"] . "</td>";
                echo "<td>" . $row["user_id"] . "</td>";
                echo "<td><b>" . $row["username"] . "</b></td>";
                
                // Color code actions
                $action = $row["action_type"];
                $color = "black";
                if(strpos($action, 'LOGIN') !== false) $color = "green";
                if(strpos($action, 'UPDATE') !== false) $color = "orange";
                if(strpos($action, 'PRESCRIBE') !== false) $color = "blue";
                
                echo "<td style='color:$color; font-weight:bold;'>" . $action . "</td>";
                echo "<td>" . $row["description"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No activity found.</p>";
        }
        
        mysqli_close($conn);
        ?>

    </div>

    <footer>
        Nottingham University Hospitals NHS Trust © 2025
    </footer>

</body>
</html>