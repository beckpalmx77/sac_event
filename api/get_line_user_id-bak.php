<?php

include 'delete_file.php';

// Path to the JSON file
$filename = 'log_api.json';

/*
$mysql_host = "171.100.56.194";
$mysql_port = "3307";
$mysql_db_name = "sac_event";
$mysql_user = "myadmin";
$mysql_pass = "myadmin";
*/

$mysql_host = "localhost";
$mysql_port = "3306";
$mysql_db_name = "themedia_thaidatabase";
$mysql_user = "themedia_themedia";
$mysql_pass = "AsdZxc007";


try {
    $conn = new PDO("mysql:host=" . $mysql_host . ";dbname=" . $mysql_db_name . ";port=" . $mysql_port, $mysql_user, $mysql_pass
        , array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit("Error: " . $e->getMessage());
}


// Read the file contents
$jsonContent = file_get_contents($filename);

// Decode the JSON data to a PHP associative array
$data = json_decode($jsonContent, true);

// Check if the data was decoded correctly
if (json_last_error() === JSON_ERROR_NONE) {
    // Accessing values from the decoded array
    // Check if 'events' key exists and is an array
    if (isset($data['events']) && is_array($data['events'])) {
        foreach ($data['events'] as $event) {
            // Check if 'source' and 'userId' keys exist
            if (isset($event['source']['userId'])) {
                echo 'User ID: ' . $event['source']['userId'] . PHP_EOL;

                $messageText = $event['message']['text'];

                $sql_find = "SELECT * FROM evs_sale_name WHERE sale_line_user_id = '" . $event['source']['userId'] . "'";
                $sale_line_user_id = $event['source']['userId'];
                $nRows = $conn->query($sql_find)->fetchColumn();
                if ($nRows > 0) {
                    echo $dup;
                    //Delete($filename);

                } else {

                    $sql = "INSERT INTO evs_sale_name (sale_line_user_id,messageText) 
                            VALUES (:sale_line_user_id,messageText)";
                    $query = $conn->prepare($sql);
                    $query->bindParam(':sale_line_user_id', $sale_line_user_id, PDO::PARAM_STR);
                    $query->bindParam(':messageText', $messageText, PDO::PARAM_STR);
                    $query->execute();
                    $lastInsertId = $conn->lastInsertId();
                    if ($lastInsertId) {
                        echo $save_success;
                        //Delete($filename);

                    } else {
                        echo $error;
                    }

                }

            } else {
                echo 'User ID key does not exist in the event' . PHP_EOL;
            }
        }
    } else {
        echo 'Events key does not exist or is not an array' . PHP_EOL;
    }
} else {
    // Handle JSON decode error
    echo 'Failed to decode JSON: ' . json_last_error_msg();
}

function Delete($FileName)
{
    try {
        if (file_exists($FileName)) {
            if (unlink($FileName)) {
                echo "File deleted successfully.";
            } else {
                throw new Exception("Error deleting the file.");
            }
        } else {
            throw new Exception("File does not exist.");
        }
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage();
    }
}