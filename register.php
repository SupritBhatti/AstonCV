<?php
session_start();
//checks if form has been submitted
if (isset($_POST['submitted'])) {
    //connect to database
    require_once ('connectdb.php');
    //stores all information into the database
    $name = isset($_POST['name'])?$_POST['name']:false;
    $email = isset($_POST['email'])?$_POST['email']:false;
    $password = isset($_POST['password'])?password_hash($_POST['password'],PASSWORD_DEFAULT):false;
    $dob = isset($_POST['dob'])?$_POST['dob']:false;
    $profile = isset($_POST['profile'])?$_POST['profile']:false;
    $keyprogramming = isset($_POST['keyprogramming'])?$_POST['keyprogramming']:false;
    $education = isset($_POST['education'])?$_POST['education']:false;
    $URLlinks = isset($_POST['URLlinks'])?$_POST['URLlinks']:false;

    //input length validation, if any of the fields exceed their maximum length show error message, this ensures that data stored in the database is within expected limits and prevents potential issues with excessively long input.
    if (strlen($profile) > 500) {
        echo "Profile must be less than 500 characters.";
        exit;
    }
    if (strlen($keyprogramming) > 255) {
        echo "Key programming skills must be less than 255 characters.";
        exit;
    }
    if (strlen($education) > 500) {
        echo "Education must be less than 500 characters.";
        exit;
    }
    if (strlen($URLlinks) > 500) {
        echo "URL links must be less than 500 characters.";
        exit;
    }

    if (empty($name) || empty($email) || empty($password) || empty($dob)) {//required fields check, if any of the required fields are empty show error message
        echo "Please fill out all required fields.";
        exit;
    }
    if (empty($profile) || empty($keyprogramming) || empty($education) || empty($URLlinks)) {//leaves optional fields blank if user does not fill them out
        $profile = null;
        $keyprogramming = null;
        $education = null;
        $URLlinks = null;
    }

    try{
        $query = "SELECT * FROM cvs WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            echo "An account with this email already exists. Please use a different email.";//check if email already exists in the database, if so show error message
            exit;
        }
    } catch (PDOException $ex) {
        error_log($ex->getMessage()); // logs privately to server
        echo("Failed to connect to the database.");//Ensures that if there is a database connection error, the user is shown a generic error message and the specific error details are logged privately on the server for debugging purposes.
        exit;
    }
    $query = "INSERT INTO cvs (name, email, password, dob, profile, keyprogramming, education, URLlinks) VALUES (:name, :email, :password, :dob, :profile, :keyprogramming, :education, :URLlinks)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':dob', $dob);
    $stmt->bindParam(':profile', $profile);
    $stmt->bindParam(':keyprogramming', $keyprogramming);
    $stmt->bindParam(':education', $education);
    $stmt->bindParam(':URLlinks', $URLlinks);
    $stmt->execute();
    $_SESSION['email'] = $email; // Store email in session
    
    header('Location: logged.php'); // Redirect instead of echoing
    exit;
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

        <section id = "register">
            <h2>Register for AstonCV</h2>
            <p>To create an account on AstonCV, please fill out the registration form below. Once completed, you will be redirected to your new home page.</p>
            <form id = "registerForm" method = "post" action = "register.php">
                <label for="name">Name:</label><br>
                <input type = "text" id = "name" name = "name" required><br><br>
                <label for="email">Email:</label><br>
                <input type = "email" id = "email" name = "email" required><br><br>
                <label for="password">Password:</label><br>
                <input type = "password" id = "password" name = "password" required><br><br>
                <label for="dob">Date of Birth:</label><br>
                <input type="date" id="dob" name="dob" required><br><br>
                <p>The following information is optional and can be filled out at any time:</p><br>
                <label for="profile">Profile:</label><br>
                <textarea id="profile" name="profile" rows="8"></textarea><br><br>
                <label for="keyprogramming">Key Programming Skills:</label><br>
                <textarea id="keyprogramming" name="keyprogramming" rows="6"></textarea><br><br>
                <label for="education">Education:</label><br>
                <textarea id="education" name="education" rows="6"></textarea><br><br>
                <label for="URLlinks">Extra URL Links (LinkedIn, GitHub, etc.):</label><br>
                <textarea id="URLlinks" name="URLlinks" rows="6"></textarea><br><br>

                <input type = "submit" value = "Register"/>
                <input type = "reset" value = "Clear"/>
                <input type = "hidden" name = "submitted" value = "TRUE"/>
            </form>
        </section>

     </main>
     <footer id = "main-footer"><!--Unique ID for the footer-->
        <p>Contact us:</p>
        <a href="mailto:250138351@aston.ac.uk?" target="_blank" rel="noopener noreferrer">Email: 250138351@aston.ac.uk</a>
        <p>Phone: 071234567890</p>
     </footer>
   </body>
</html>
