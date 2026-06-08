<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}
require_once('connectdb.php');

$searchTerm = '';
$query = "SELECT name, email, keyprogramming FROM cvs WHERE profile IS NOT NULL AND keyprogramming IS NOT NULL AND education IS NOT NULL AND URLlinks IS NOT NULL";
$params = [];

if (isset($_GET['search']) && trim($_GET['search']) !== '') {
    $searchTerm = trim($_GET['search']);
    $like = '%' . $searchTerm . '%';
    $query .= " AND (name LIKE :s1 OR keyprogramming LIKE :s2 OR email LIKE :s3)";
    $params = [':s1' => $like, ':s2' => $like, ':s3' => $like];
}

$stmt = $db->prepare($query);
$stmt->execute($params);
$cvs = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <section id = "logged">
            <h2>Welcome to AstonCV!</h2>
            <p>You have successfully logged in. You can now access your CV and make edits as you see fit. Use the navigation bar to access different sections of the platform.</p>
            <p>To create a new CV, click on the "Create a CV" button in the navigation bar. You can also log out of your account by clicking the "Logout" button.</p>
        </section>
        <section id = "view">
            <h2>View CVs:</h2>
            <p>Below is a list of CVs that have been created on the platform. Click on any CV to view its details.</p>

            <section id="searchBar">
                <form method="GET" action="logged.php">
                    <input type="text" name="search" placeholder="Search by name , email or programming skills..." value="<?= htmlspecialchars($searchTerm) ?>">
                    <button type="submit">Search</button>
                </form>
            </section>

            <?php if (!empty($cvs)): ?>
                <table id="cvTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Key Programming Skills</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cvs as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['keyprogramming']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><a href="logged_viewCV.php?name=<?= urlencode($row['name']) ?>">View CV</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p id="noResults">No CVs found matching "<?= htmlspecialchars($searchTerm) ?>".</p>
            <?php endif; ?>

        </section>
    </main>
        <footer id = "main-footer"><!--Unique ID for the footer-->
            <p>Contact us:</p>
            <a href="mailto:250138351@aston.ac.uk?" target="_blank" rel="noopener noreferrer">Email: 250138351@aston.ac.uk</a>
            <p>Phone: 071234567890</p>
        </footer>
    </body>
</html>
