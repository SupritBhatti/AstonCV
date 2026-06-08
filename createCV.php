<?php
session_start();
// Redirect to login if not logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

require_once('connectdb.php');

// Fetch the user's data from the DB
$query = "SELECT * FROM cvs WHERE email = :email";
$stmt = $db->prepare($query);
$stmt->bindParam(':email', $_SESSION['email']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle CV update on form submission
if (isset($_POST['Submitted'])) {
    $profile = $_POST['profile'] ?? null;
    $keyprogramming = $_POST['keyprogramming'] ?? null;
    $education = $_POST['education'] ?? null;
    $URLlinks = $_POST['URLlinks'] ?? null;

    if ( empty($profile) || empty($keyprogramming) || empty($education) || empty($URLlinks)) { //mandatory fields check, if any of the fields are empty show error message as user is creating CV now.
        echo "Please fill out all fields before submitting.";
        exit;
    }
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

    $query = "UPDATE cvs SET profile=:profile, keyprogramming=:keyprogramming, education=:education, URLlinks=:URLlinks WHERE email=:email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':profile', $profile);
    $stmt->bindParam(':keyprogramming', $keyprogramming);
    $stmt->bindParam(':education', $education);
    $stmt->bindParam(':URLlinks', $URLlinks);
    $stmt->bindParam(':email', $_SESSION['email']);
    $stmt->execute();

    // Refresh $user so the form shows the updated values
    $user['profile'] = $profile;
    $user['keyprogramming'] = $keyprogramming;
    $user['education'] = $education;
    $user['URLlinks'] = $URLlinks;

    echo "CV updated successfully!";
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
            <a href="logged.php">
            <button>Home</button>
            </a>
            <a href="createCV.php">
            <button>Create a CV</button>
            </a>
            <a href = "logout.php">
            <button>Logout</button>
            </a>
        </section>
       </header>
     <main>
        <section id = "create">
            <h2>Create a CV</h2>
            <p>To create a CV, please fill out the form below with your details. Once you have submitted the form, your CV will be created and you can view it in your profile.</p>
            <form id = "createForm" method = "post" action = "createCV.php">
                <label for="name">Name:</label><br>
                <input type="text"  name="name"  value="<?= htmlspecialchars($user['name'] ?? '') ?>" readonly><br><br><!--Name is read-only as it is set during registration and should not be changed here-->
                <label for="email">Email:</label><br>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required><br><br>
                <label for="dob">Date of Birth:</label><br>
                <input type="date"  name="dob"   value="<?= htmlspecialchars($user['dob']  ?? '') ?>" readonly><br><br><!--Date of Birth is read-only as it is set during registration and should not be changed here-->

                <label for="profile">Profile:</label><br>
                <textarea name="profile" rows="8"><?= htmlspecialchars($user['profile'] ?? '') ?></textarea required><br><br>
                <label for="keyprogramming">Key Programming Skills:</label><br>
                <textarea name="keyprogramming" rows="6"><?= htmlspecialchars($user['keyprogramming'] ?? '') ?></textarea required><br><br>
                <label for="education">Education:</label><br>
                <textarea name="education" rows="6"><?= htmlspecialchars($user['education'] ?? '') ?></textarea required><br><br>
                <label for="URLlinks">Extra URL Links (LinkedIn, GitHub, etc.):</label><br>
                <textarea name="URLlinks" rows="6"><?= htmlspecialchars($user['URLlinks'] ?? '') ?></textarea required><br><br>

                <input type = "submit" value = "Create CV"/>
                <input type = "reset" value = "Restore to Default"/>
                <input type = "hidden" name = "Submitted" value = "true"/>
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
