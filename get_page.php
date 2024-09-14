<?php
// Start the session to retrieve the stored random value
session_start();

if (isset($_SESSION['random_value'])) {
    echo $_SESSION['random_value'];
} else {
    echo 'No value set';
}
?>

