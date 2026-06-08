<?php
session_start();
if (isset($_POST['submitted'])) {
    if (!isset($_POST['email'], $_POST['password'])) {
        exit('Please fill both the email and password fields!');
    }
    require_once('connectdb.php');
    try {
        $stat = $db->prepare('SELECT password FROM cvs WHERE email = ?');
        $stat->execute(array($_POST['email']));

        if ($stat->rowCount() > 0) {
            $row = $stat->fetch();

            if (password_verify($_POST['password'], $row['password'])) { // Hashes the entered password and compares it to hashed passwords in database, if hashes match, log user in otherwise don't log them in.
                session_start();
                $_SESSION["email"] = $_POST['email'];
                header("Location: logged.php");
                exit();
            } else {
                echo "<p style='color:red'>Error logging in, password does not match </p>";
            }
        } else {
            echo "<p style='color:red'>Error logging in, Email not found </p>";
        }
    } catch (PDOException $ex) {
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
        <section id = "login">
        <h2>Login to AstonCV</h2>
        <p>To access your account on AstonCV, please fill out the login form below:</p>
        <form id = "loginForm" action = "login.php" method = "post">
            <label for = "email">Email:</label><br>
            <input type = "email" id = "email" name = "email" required><br><br>
            <label for = "password">Password:</label><br>
            <input type = "password" id = "password" name = "password" required><br><br>

            <input type="submit" value="Login" />
	        <input type="reset" value="Clear"/>
            <input type="hidden" name="submitted" value="TRUE" />
        </form>
        </section>

        <a href = "forgotpassword.php">
        <button id = "forgot-password">Forgot Password?</button>
        </a>
        <p>Don't have an account? Use the register option to create an account today or use the guest access option to explore the platform.</p>
     </main>

     <footer id = "main-footer"><!--Unique ID for the footer-->
        <p>Contact us:</p>
        <a href="mailto:250138351@aston.ac.uk?" target="_blank" rel="noopener noreferrer">Email: 250138351@aston.ac.uk</a>
        <p>Phone: 071234567890</p>
     </footer>
    </body>
</html>
