<?php

$mysql_host = "localhost";
$mysql_port = "3306";
$mysql_db_name = "themedia_thaidatabase";
$mysql_user = "themedia_themedia";
$mysql_pass = "AsdZxc007";

try {
    $conn = new PDO("mysql:host=$mysql_host;dbname=$mysql_db_name;port=$mysql_port;charset=utf8", $mysql_user, $mysql_pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

$filename = 'log_api.json';

// Read the entire JSON file
$json_data = file_get_contents($filename);

// Split JSON data by lines (assuming each line is a separate JSON object)
$lines = explode("\n", $json_data);

foreach ($lines as $line) {
    if (!empty($line)) {
        // Decode JSON data for each line
        $data = json_decode($line, true);

        // Accessing values
        $destination = $data['destination'];
        $events = $data['events'];

        // Loop through each event
        foreach ($events as $event) {
            $type = $event['type'];
            $messageType = $event['message']['type'];
            $messageId = $event['message']['id'];
            $quoteToken = $event['message']['quoteToken'];
            $messageText = $event['message']['text'];
            $webhookEventId = $event['webhookEventId'];
            $isRedelivery = $event['deliveryContext']['isRedelivery'];
            $timestamp = $event['timestamp'];
            $sourceType = $event['source']['type'];
            $userId = $event['source']['userId'];
            $replyToken = $event['replyToken'];
            $mode = $event['mode'];

            $sql_find = "SELECT * FROM evs_line_message WHERE message_id = '" . $messageId . "'";
            $nRows = $conn->query($sql_find)->fetchColumn();
            if ($nRows <= 0) {

                // Prepare SQL statement to insert data into MySQL
                $sql = "INSERT INTO evs_line_message (type, message_type, message_id, quote_token, message_text, webhook_event_id, is_redelivery, timestamp, source_type, user_id, reply_token, mode)
                    VALUES (:type, :message_type, :message_id, :quote_token, :message_text, :webhook_event_id, :is_redelivery, :timestamp, :source_type, :user_id, :reply_token, :mode)";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':type', $type, PDO::PARAM_STR);
                $stmt->bindParam(':message_type', $messageType, PDO::PARAM_STR);
                $stmt->bindParam(':message_id', $messageId, PDO::PARAM_STR);
                $stmt->bindParam(':quote_token', $quoteToken, PDO::PARAM_STR);
                $stmt->bindParam(':message_text', $messageText, PDO::PARAM_STR);
                $stmt->bindParam(':webhook_event_id', $webhookEventId, PDO::PARAM_STR);
                $stmt->bindParam(':is_redelivery', $isRedelivery, PDO::PARAM_BOOL);
                $stmt->bindParam(':timestamp', $timestamp, PDO::PARAM_INT);
                $stmt->bindParam(':source_type', $sourceType, PDO::PARAM_STR);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_STR);
                $stmt->bindParam(':reply_token', $replyToken, PDO::PARAM_STR);
                $stmt->bindParam(':mode', $mode, PDO::PARAM_STR);

                // Execute the SQL statement
                try {
                    $stmt->execute();
                    echo "Inserted data successfully for event with timestamp: $timestamp\n";
                } catch (PDOException $e) {
                    echo "Error inserting data: " . $e->getMessage() . "\n";
                }
            }
        }
    }
}


