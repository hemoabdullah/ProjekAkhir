<?php
// Include the database connection file
include 'connection.php';

// Start the session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize inputs
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Hash the password to match the database storage format
    $hashedPassword = hash('sha256', $password);

    // Query to fetch user data from the database
    $sql = "SELECT * FROM Users WHERE Email = ? AND Password = ?";
    $params = array($email, $hashedPassword);

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        // Log the error for debugging
        error_log("Database error: " . print_r(sqlsrv_errors(), true));
        header("Location: index.php?error=1");
        exit();
    }

    // Check if user exists and password matches
    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        // Set session variables
        $_SESSION['logged_in'] = true;
        $_SESSION['email'] = $email;
        $_SESSION['level'] = $row['Level'];

        // If "Remember me" is checked, set a cookie
        if (isset($_POST['remember']) && $_POST['remember'] == 'on') {
            setcookie('email', $email, time() + (86400 * 30), "/"); // 30 days
        }

        // Update last login time
        $updateSql = "{CALL UpdateLastLogin(?)}";
        sqlsrv_query($conn, $updateSql, array($email));

        // Redirect based on user level
        if ($row['Level'] == 'admin') {
            header("Location: dashboard.php");
        } else {
            header("Location: dashboard.php");
        }
        exit();
    } else {
        // Invalid login
        header("Location: index.php?error=2");
        exit();
    }

    // Free statement
    sqlsrv_free_stmt($stmt);
    
    // Close connection
    sqlsrv_close($conn);
} else {
    // If someone tries to access login.php directly without POST data
    header("Location: index.php");
    exit();
}
?>
