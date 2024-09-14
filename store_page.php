<?php
// Start the session to store the random value
session_start();

if (isset($_POST['value'])) {
    // Store the random value in the session
    $_SESSION['random_value'] = $_POST['value'];
    echo 'Value stored successfully';
} else {
    echo 'No value provided';
}
?>

