<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['db_type'];
    $config = "<?php\n";
    $config .= "define('DB_TYPE', '$type');\n";

    if ($type === 'sqlite') {
        $path = __DIR__ . '/webring.sqlite';
        $config .= "define('SQLITE_PATH', '$path');\n";
        $db = new PDO("sqlite:$path");
        $db->exec("CREATE TABLE IF NOT EXISTS sites (id INTEGER PRIMARY KEY AUTOINCREMENT, url TEXT, title TEXT);");
    } elseif ($type === 'mysql' || $type === 'pgsql') {
        $host = $_POST['db_host'];
        $name = $_POST['db_name'];
        $user = $_POST['db_user'];
        $pass = $_POST['db_pass'];
        $config .= "define('DB_HOST', '$host');\n";
        $config .= "define('DB_NAME', '$name');\n";
        $config .= "define('DB_USER', '$user');\n";
        $config .= "define('DB_PASS', '$pass');\n";

        $dsn = ($type === 'mysql' ? 'mysql' : 'pgsql') . ":host=$host;dbname=$name";
        $db = new PDO($dsn, $user, $pass);
        $sql = "CREATE TABLE IF NOT EXISTS sites (
                    id SERIAL PRIMARY KEY,
                    url TEXT NOT NULL,
                    title TEXT NOT NULL
                );";
        $db->exec($sql);
    } elseif ($type === 'mongodb') {
        require_once 'vendor/autoload.php';
        $config .= "// mongodb uses localhost default\n";
    }

    file_put_contents('config.php', $config);
    echo "installed successfully. <a href='index.php'>View webring</a>";
    exit;
}
?>
<form method="post">
    <label>Select your database:</label><br>
    <select name="db_type" onchange="this.form.submit()">
        <option value="">-- choose --</option>
        <option value="mysql">mysql</option>
        <option value="pgsql">postgresql</option>
        <option value="sqlite">sqlite</option>
        <option value="mongodb">mongodb</option>
    </select>
    <noscript><input type="submit" value="Next"></noscript>
</form>
<?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['db_type'] !== 'sqlite' && $_POST['db_type'] !== 'mongodb'): ?>
<form method="post">
    <input type="hidden" name="db_type" value="<?= htmlspecialchars($_POST['db_type']) ?>">
    Host: <input name="db_host" required><br>
    Database name: <input name="db_name" required><br>
    Username: <input name="db_user" required><br>
    Password: <input type="password" name="db_pass"><br>
    <button type="submit">install!</button>
</form>
<?php endif; ?>
