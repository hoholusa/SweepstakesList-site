<?php
// Database credentials are NOT stored in Git. Looked up in this order:
//   1. environment variables SL_DB_HOST, SL_DB_PORT, SL_DB_USER, SL_DB_PASS, SL_DB_NAME
//   2. PHP settings sl.db_host, sl.db_port, sl.db_user, sl.db_pass, sl.db_name
//      (Plesk > PHP Settings > Additional configuration directives)
//   3. reg-config.php one folder above httpdocs (recommended on Plesk)
//   4. reg-config.php next to this file
function slSetting($env, $ini, $default = '') {
    $v = getenv($env);
    if ($v !== false && $v !== '') return $v;
    $v = get_cfg_var($ini);
    if ($v !== false && $v !== '') return $v;
    return $default;
}

$hostname = slSetting('SL_DB_HOST', 'sl.db_host');
$port     = (int) slSetting('SL_DB_PORT', 'sl.db_port', '3306');
$username = slSetting('SL_DB_USER', 'sl.db_user');
$password = slSetting('SL_DB_PASS', 'sl.db_pass');
$dbname   = slSetting('SL_DB_NAME', 'sl.db_name', 'slwin');

if ($password === '') {
    foreach (array(__DIR__ . '/../reg-config.php', __DIR__ . '/reg-config.php') as $cfg) {
        if (is_file($cfg)) { require $cfg; break; }
    }
}

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
