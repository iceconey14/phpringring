<?php
require_once 'db.php';
$db = get_db();

$current = $_GET['id'] ?? 0;

if (DB_TYPE === 'mongodb') {
    $all = iterator_to_array($db->find([], ['sort' => ['_id' => 1]]));
    $prevSite = end($all);

    for ($i = 0; $i < count($all); $i++) {
        if ((string)$all[$i]->_id === $current && $i > 0) {
            $prevSite = $all[$i - 1];
            break;
        }
    }

    header("Location: " . $prevSite->url);
    exit;
} else {
    $stmt = $db->prepare("SELECT * FROM sites WHERE id < ? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$current]);
    $prev = $stmt->fetch();

    if (!$prev) {
        $prev = $db->query("SELECT * FROM sites ORDER BY id DESC LIMIT 1")->fetch();
    }

    header("Location: " . $prev['url']);
    exit;
}
