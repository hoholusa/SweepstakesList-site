<?php
// Copy to reg-config.php in the SAME folder as reg.php on the server (httpdocs).
// This host's open_basedir does not allow reading above httpdocs.
// reg-config.php is in .gitignore and must never be committed.
$hostname = "your-mysql-host";
$port     = 3306;
$username = "your-db-user";
$password = "your-db-password";
$dbname   = "slwin";
