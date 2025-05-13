<?php
require_once 'db.php';
$db = get_db();

if (DB_TYPE === 'mongodb') {
    $sites = iterator_to_array($db->find());
    $site = $sites[array_rand($sites)];
    header("Location: " . $site->url);
} else {
    $site = $db->query("SELECT * FROM sites ORDER BY RAND() LIMIT 1")->fetch();
    header("Location: " . $site['url']);
}
