<?php

function connectToDB($databaseName = 'db.sqlite')
{
    try {
        $db = new PDO("sqlite:$databaseName");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOEception $e) {
        die("Something went wrong -> " .$e->getMessage());
    }
    return $db;
}
