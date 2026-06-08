<?php
session_start();
require_once('connectdb.php');

$searchTerm = '';
$query = "SELECT name, email, keyprogramming FROM cvs WHERE profile IS NOT NULL AND keyprogramming IS NOT NULL AND education IS NOT NULL AND URLlinks IS NOT NULL";
$params = [];

if (isset($_GET['search']) && trim($_GET['search']) !== '') {
    $searchTerm = trim($_GET['search']);
    $like = '%' . $searchTerm . '%';
    $query .= " AND (name LIKE :s1 OR keyprogramming LIKE :s2)";
    $params = [':s1' => $like, ':s2' => $like];
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
            <a href="landingPage.html">
            <button>Home</button>
            </a>
            <a href="login.php">
            <button>Login</button>
            </a>
            <a href = "register.php">
            <button>Register</button>
            </a>
        </section>
       </header>
     <main>
        <section id = "guest">
            <h2>Guest Access</h2>
            <p>Welcome to AstonCV! As a guest, you can explore the platform and view CVs created by other users. However, please note that you will not be able to create or edit your own CV until you register for an account.</p>
            <p>View CVs below:</p>
            <section id="searchBar">
                <form method="GET" action="guest.php">
                    <input
                        type="text"
                        name="search"
                        placeholder="Search by name or programming skills..."
                        value="<?= htmlspecialchars($searchTerm) ?>"
                    >
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
                                <td><a href="viewCV.php?name=<?= urlencode($row['name']) ?>">View CV</a></td>
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
