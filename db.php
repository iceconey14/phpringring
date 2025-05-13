<?php
require_once 'config.php';

function get_db() {
    switch (DB_TYPE) {
        case 'mysql':
        case 'pgsql':
            $dsn = (DB_TYPE === 'mysql' ? 'mysql' : 'pgsql') . ":host=" . DB_HOST . ";dbname=" . DB_NAME;
            return new PDO($dsn, DB_USER, DB_PASS);
        case 'sqlite':
            return new PDO("sqlite:" . SQLITE_PATH);
        case 'mongodb':
            $mongo = new MongoDB\Client("mongodb://localhost:27017");
            return $mongo->webring->sites;
        default:
            die("Uh oh, your database type is not supported.");
    }
}
?>
