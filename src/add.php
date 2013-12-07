<?php
require_once("database.php");

/**
* Called from AJAX to add stuff to DB
*/
function addToDB($name, $message, $pid)
{
    $db = connectToDB();

    $query = "INSERT INTO messages (message, name, pid) VALUES(:message, :name, :pid)";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':pid', $pid, PDO::PARAM_INT);

    try {
        $stmt->execute();
    } catch (PDOException $e) {
        die("Something went wrong -> " .$e->getMessage());
    }
}
