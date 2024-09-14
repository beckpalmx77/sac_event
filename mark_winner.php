// mark_winner.php
<?php
include 'config/connect_db.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$sql_update = "UPDATE evs_event_checkin SET is_winner = 'Y' WHERE id = ?";

/*
$myfile = fopen("permission-param.txt", "w") or die("Unable to open file!");
fwrite($myfile, $sql_update);
fclose($myfile);
*/

$stmt = $conn->prepare($sql_update);
$stmt->execute([$id]);


?>