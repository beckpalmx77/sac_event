<?php
function delete($filename)
{
    try {
        if (file_exists($filename)) {
            if (unlink($filename)) {
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