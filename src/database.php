<?php

function connectToDB()
{
    try {
        $db = new PDO("sqlite:db.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOEception $e) {
        die("Something went wrong -> " .$e->getMessage());
    }
    return $db;
}
