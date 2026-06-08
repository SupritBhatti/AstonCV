<?php
// Implementation for forgot password functionality
session_start();
require_once('connectdb.php');
if (isset($_POST['submitted'])) {
    // Handle forgot password logic here
        $email = isset($_POST['email']) ? $_POST['email'] : false;
        $newpassword = isset($_POST['newpassword']) ? password_hash($_POST['newpassword'], PASSWORD_DEFAULT) : false;

    if (empty($email) || empty($newpassword)) {
        echo "Please fill out all fields.";
        exit;
    }
    try {
        $query = "SELECT * FROM cvs WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $updateQuery = "UPDATE cvs SET password = :newpassword WHERE email = :email";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->bindParam(':newpassword', $newpassword);
            $updateStmt->bindParam(':email', $email);
            $updateStmt->execute();
            echo "Password reset successfully!";
        } else {
            echo "Email not found.";

        }
    }
    catch (PDOException $ex) {
        error_log($ex->getMessage()); // logs privately to server
        echo("Failed to connect to the database.");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8" name = "viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="Images/CVIcon.png" type="image/x-icon"/>
        <title>AstonCV</title>
        <link rel="stylesheet" type="text/css" href="CSS/style.css">
    </head>

   <body>

       <header id = "main-header">
        <h1>AstonCV</h1>
        <section id = "nav"><!--ID used to layout buttons exclusively-->
            <a href="landingPage.html">
            <button>Home</button>
            </a>
            <a href="login.php">
            <button>Login</button>
            </a>
            <a href = "register.php">
            <button>Register</button>
            </a>
            <a href = "guest.php">
            <button>Guest Access</button>
            </a>
        </section>
       </header>
     <main>
        <section id = "forgot-password">
        <h2>Forgot Password</h2>
        <!-- Form for users to enter their email to reset password -->
        <!-- This is a placeholder, actual implementation would involve sending a reset link to the user's email -->
        <form action="forgotpassword.php" method="post">
            <label for="email">Enter your email address:</label><br>
            <input type="email" id="email" name="email" required><br><br>
            <label for="newpassword">Enter your new password:</label><br>
            <input type="password" id="newpassword" name="newpassword" required><br><br>

            <input type="submit" value="Reset Password">
            <input type = "reset" value = "Clear"/>
            <input type = "hidden" name = "submitted" value = "TRUE"/>
        </form>