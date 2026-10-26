<?php
// Database credentials are NOT stored in Git. They are loaded from reg-config.php,
// which sits next to this file on the server only (see reg-config.example.php).
// (Plesk open_basedir blocks reading files above httpdocs on this host.)
require __DIR__ . '/reg-config.php';

$code  = isset($_GET['code']) ? $_GET['code'] : '';
$found = false;

mysqli_report(MYSQLI_REPORT_OFF);
$db = new mysqli($hostname, $username, $password, $dbname, $port);
if ($db->connect_errno) {
    die("@ Unable to connect to database! Please try again later.");
}

$stmt = $db->prepare("SELECT regcode FROM sluser WHERE regcode = ?");
$stmt->bind_param("s", $code);
$stmt->execute();
$stmt->bind_result($regcode);
while ($stmt->fetch()) {
    echo "|SL1961|{$code}|<br>";
    $found = true;
}
$stmt->close();

if (!$found) {
    echo "|SL1961|<br>";
} else {
    $upd = $db->prepare("UPDATE sluser SET hits = hits + 1 WHERE regcode = ?");
    $upd->bind_param("s", $code);
    $upd->execute();
    $upd->close();
}
$db->close();
