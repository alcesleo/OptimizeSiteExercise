<?php

/**
* Called from AJAX to add stuff to DB
*/
function addToDB($name, $message, $pid)
{
    $db = null;

    try {
        $db = new PDO("sqlite:db.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOEception $e) {
        die("Something went wrong -> " .$e->getMessage());
    }

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
