<?php
require_once 'db.php';
$db = get_db();
$current = (int)($_GET['id'] ?? 0);

if (DB_TYPE === 'mongodb') {
    $all = iterator_to_array($db->find([], ['sort' => ['_id' => 1]]));
    for ($i = 0; $i < count($all); $i++) {
        if ((string)$all[$i]->_id == $current && isset($all[$i+1])) {
            header("Location: " . $all[$i+1]->url);
            exit;
        }
    }
    header("Location: " . $all[0]->url);
} else {
    $stmt = $db->prepare("SELECT * FROM sites WHERE id > ? ORDER BY id ASC LIMIT 1");
    $stmt->execute([$current]);
    $next = $stmt->fetch();
    if (!$next) {
        $next = $db->query("SELECT * FROM sites ORDER BY id ASC LIMIT 1")->fetch();
    }
    header("Location: " . $next['url']);
}
