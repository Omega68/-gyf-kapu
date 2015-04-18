<?
include("db_config.php");
include("database.php");
$db=new DatabaseConnection(DBConfig::$user,DBConfig::$password,DBConfig::$location,DBConfig::$dbname,DBConfig::$charset);
echo $db->connect().PHP_EOL;