<?php
ini_set('display_errors', '1');
// DB credentials.
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','db_admin');
// Establish database connection.
try {
    $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'"));

    function checkTableExists($pdo, $tableName) {
        try {
            $result = $pdo->query("SELECT 1 FROM $tableName LIMIT 1");
        } catch (Exception $e) {
            // We assume the table does not exist based on the error thrown
            // WARNING: This is not a foolproof method and may need adjustment depending on your database and setup
            return false;
        }

        return $result !== false;
    }


    function executeSQLFile($file, $pdo) {
        try {
            $query = file_get_contents($file);
            $pdo->exec($query);

            // Assuming your SQL statements are split by semicolons
            // This is a simplistic approach and may not work for all SQL files, especially those containing semicolon within stored procedures, strings, or comments
            // var_export(explode(';', $query));
            // $stmt->execute(explode(';', $query));
        } catch (PDOException $e) {
            die("Error occurred: " . $e->getMessage());
        }
    }

    // Assuming connection is already established in $pdo
    // Replace 'your_table_name_here' with a table name that's expected to exist after your SQL file runs successfully
    if (!checkTableExists($dbh, 'admin')) {
        // If the check determines that the file must be executed, call the function to execute it
        executeSQLFile('../database.sql', $dbh);
        // Plus, your other actions
    }

    function checkUserExists($pdo) {
        try {
            $stmt = $pdo->query("SELECT 1 FROM users LIMIT 1");
            $row = $stmt->fetch();
            if (!$row) return false;
            return true;
        } catch (PDOException $e) {
            die("Error occurred: " . $e->getMessage());
        }
    }

    if (!checkUserExists($dbh)) {
        try{
            require '../parseDB.php';
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

} catch (PDOException $e) {
exit("Error: " . $e->getMessage());
}
?>