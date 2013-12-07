<?php
require_once 'get.php';
require_once 'add.php';
require_once 'sec.php';
sec_session_start();

/*
* It's here all the ajax calls goes
*/
if (isset($_GET['function'])) {

    switch ($_GET['function']) {
        case 'logout':
            logout();
            break;
        case 'add':
            $name = $_GET["name"];
            $message = $_GET["message"];
            $pid = $_GET["pid"];

            addToDB($name, $message, $pid);
            echo "Det gick fint! Ladda om sidan för att se ditt meddelande!";
            break;
        case 'getAllMessagesForProducer':
            $pid = $_GET["pid"];
            echo(json_encode(getAllMessagesForProducer($pid)));
            break;
        case 'producers':
            $pid = $_GET["pid"];
            echo(json_encode(getProducer($pid)));
            break;
        case 'getIdsOfMessages':
            $pid = $_GET["pid"];
            echo(json_encode(getMessageIdForProducer($pid)));
            break;
        case 'getMessage':
            $serial = $_GET["serial"];
            echo(json_encode(getMessage($serial)));
            break;
    }
}
