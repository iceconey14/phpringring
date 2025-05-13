<?php
require_once 'db.php';
$db = get_db();

// change this, and dont use a valuable password
$ADD_PASSWORD = 'letmein';

session_start();
if (!isset($_SESSION['add_auth'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['authpass'])) {
        if ($_POST['authpass'] === $ADD_PASSWORD) {
            $_SESSION['add_auth'] = true;
        } else {
            echo "Incorrect password.";
            exit;
        }
    } else {
        echo '<form method="post">
                <label>enter password to add a site: <input type="password" name="authpass"></label>
                <input type="submit" value="enter!">
              </form>';
        exit;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['url'])) {
    $title = trim($_POST['title']);
    $url = trim($_POST['url']);

    if (!$title || !$url) {
        echo "Make sure you filled in both fields.";
        exit;
    }

    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        echo "Invalid url.";
        exit;
    }

    if (DB_TYPE === 'mongodb') {
        $db->insertOne(['title' => $title, 'url' => $url]);
    } else {
        $stmt = $db->prepare("INSERT INTO sites (title, url) VALUES (?, ?)");
        $stmt->execute([$title, $url]);
    }

    echo "Site added successfully.<br>";
    echo "<a href=\"list.php\">View the webring list</a>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>add a site</title>
</head>
<body>
    <h2>Add a website to the webring.</h2>
    <form method="post">
        <label>site ttle: <input type="text" name="title" required></label><br><br>
        <label>site address: <input type="url" name="url" required></label><br><br>
        <input type="submit" value="add!">
    </form>
</body>
</html>
