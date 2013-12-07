<?php

require_once 'database.php';

// get the specific message
function getMessage($nr)
{
    $db = connectToDB();

    $q = "SELECT * FROM messages WHERE serial = :nr";

    $result;
    $stm;
    try {
        $stm = $db->prepare($q);
        $stm->bindParam(':nr', $nr, PDO::PARAM_INT);
        $stm->execute();
        $result = $stm->fetchAll();
    } catch (PDOException $e) {
        echo("Error creating query: " .$e->getMessage());

        return false;
    }

    if ($result) {
        return $result[0];
    } else {
        return false;
    }
}

function getMessageIdForProducer($pid)
{
    $db = connectToDB();

    $q = "SELECT serial FROM messages WHERE pid = :pid";

    $result;
    $stm;
    try {
        $stm = $db->prepare($q);
        $stm->bindParam(':pid', $pid, PDO::PARAM_INT);
        $stm->execute();
        $result = $stm->fetchAll();
    } catch (PDOException $e) {
        echo("Error creating query: " .$e->getMessage());

        return false;
    }

    if ($result) {
        return $result;
    } else {
        return false;
    }
}

function getProducer($id)
{
    $db = connectToDB('producerDB.sqlite');

    $q = "SELECT * FROM Producers WHERE producerID = :id";

    $result;
    $stm;
    try {
        $stm = $db->prepare($q);
        $stm->bindParam(':id', $id, PDO::PARAM_INT);
        $stm->execute();
        $result = $stm->fetchAll();
    } catch (PDOException $e) {
        echo("Error creating query: " .$e->getMessage());

        return false;
    }

    if ($result) {
        return $result[0];
    } else {
        return false;
    }
}

function getProducers()
{
    $db = connectToDB('producerDB.sqlite');

    $q = "SELECT * FROM Producers";

    $result;
    $stm;
    try {
        $stm = $db->prepare($q);
        $stm->execute();
        $result = $stm->fetchAll();
    } catch (PDOException $e) {
        echo("Error creating query: " .$e->getMessage());

        return false;
    }

    if ($result) {
        return $result;
    } else {
        return false;
    }
}
