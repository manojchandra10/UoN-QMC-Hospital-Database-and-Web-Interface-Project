<?php
session_start();
require("db.inc.php");

if (!isset($_SESSION["user"])) {
    header("Location: index.php");
}

$conn = mysqli_connect($servername, $username, $password, $dbname);
$id = $_SESSION["id"];

// the current details
$sql = "SELECT * FROM doctor WHERE staffno = '$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$current_address = $row["address"];
$current_pass = $row["password"];

$msg = "";

if (isset($_POST["update_btn"])) {
    $new_address = $_POST["my_address"];
    $new_pass = $_POST["my_password"];
    
    $update_sql = "UPDATE doctor SET address = '$new_address', password = '$new_pass' WHERE staffno = '$id'";
    
    if (mysqli_query($conn, $update_sql)) {
        // Audit
        $audit_user = $_SESSION["user"];
        $log_sql = "INSERT INTO audit_log (user_id, username, action_type, description)
                    VALUES ('$id', '$audit_user', 'UPDATE PROFILE', 'User updated password or address')";
        mysqli_query($conn, $log_sql);
        
        $msg = "<p style='color:green; font-weight:bold;'>Profile Updateded Successfuly!</p>";
        $current_address = $new_address;
        $current_pass = $new_pass;
    } else {
        $msg = "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Profile</title>
    <link rel="stylesheet" href="css/main.css">
    <style> main { display: block; height: auto; padding: 0; } </style>
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

    <div class="dashboard-mode">
        <h1>My Profile</h1>
        <p><a href="doctor_home.php">Back to Dashboard</a></p>
        <hr/>
        
        <?php echo $msg; ?>

        <form method="POST" style="max-width:600px;">
            <label>Staff Number:</label><br>
            <input type="text" value="<?php echo $id; ?>" readonly style="background:#eee;"><br>

            <label>First Name:</label><br>
            <input type="text" value="<?php echo $_SESSION["name"]; ?>" readonly style="background:#eee;"><br>

            <label>Address:</label><br>
            <input type="text" name="my_address" value="<?php echo $current_address; ?>" required><br>

            <label>Password:</label><br>
            <input type="text" name="my_password" value="<?php echo $current_pass; ?>" required><br>

            <input type="submit" name="update_btn" value="Update Profile">
        </form>
    </div>

    <footer>
        Nottingham University Hospitals NHS Trust © 2025
    </footer>

</body>
</html>