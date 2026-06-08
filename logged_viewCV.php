<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}
require_once('connectdb.php');
if (isset($_GET['name'])) {
    $name = $_GET['name'];
    $query = "SELECT * FROM cvs WHERE name = :name";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $name);//Use bindParam to safely include the name in the query, preventing SQL injection attacks.
    $stmt->execute();
    $cv = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    echo "No CV specified.";
    exit;
}
?>
<!DOCTYPE html>
<html lang = "en">
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
        <section id = "viewCV">
            <h2><?= htmlspecialchars($cv['name']) ?>'s CV</h2>
            <p><strong>Email:</strong> <?= htmlspecialchars($cv['email']) ?></p>
            <p><strong>Date of Birth:</strong> <?= htmlspecialchars($cv['dob']) ?></p>
            <p><strong>Profile:</strong><br><?= nl2br(htmlspecialchars($cv['profile'])) ?></p>
            <p><strong>Key Programming Skills:</strong><br><?= nl2br(htmlspecialchars($cv['keyprogramming'])) ?></p>
            <p><strong>Education:</strong><br><?= nl2br(htmlspecialchars($cv['education'])) ?></p>
            <p><strong>URL Links:</strong><br><?= nl2br(htmlspecialchars($cv['URLlinks'])) ?></p>
        </section>
    </main>
        <footer id = "main-footer"><!--Unique ID for the footer-->
            <p>Contact us:</p>
            <a href="mailto:250138351@aston.ac.uk?" target="_blank" rel="noopener noreferrer">Email: 250138351@aston.ac.uk</a>
            <p>Phone: 071234567890</p>
        </footer>
    </body>
</html>
