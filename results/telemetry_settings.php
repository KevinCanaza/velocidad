<?php

// Type of db: "mysql", "sqlite" or "postgresql"
$db_type = 'mysql';
// Password to login to stats.php. Change this!!!
$stats_password = 'pass123';
// If set to true, test IDs will be obfuscated to prevent users from guessing URLs of other tests
$enable_id_obfuscation = true;
// If set to true, IP addresses will be redacted from IP and ISP info fields, as well as the log
$redact_ip_addresses = true;

// Sqlite3 settings
$Sqlite_db_file = '../../speedtest_telemetry.sql';

// Mysql settings
$MySql_username = 'root';
$MySql_password = '';
$MySql_hostname = 'localhost';
$MySql_databasename = 'speedtest';
$MySql_port = '3306';

// Postgresql settings
$PostgreSql_username = 'USERNAME';
$PostgreSql_password = 'PASSWORD';
$PostgreSql_hostname = 'DB_HOSTNAME';
$PostgreSql_databasename = 'DB_NAME';
