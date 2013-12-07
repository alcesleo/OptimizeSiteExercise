<?php

require_once('database.php');

/**
 * Just som simple scripts for session handling
 */
function sec_session_start()
{
    $session_name = 'sec_session_id'; // Set a custom session name
    $secure = false; // Set to true if using https.
    $httponly = true; // This stops javascript being able to access the session id.
    ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies.
    $cookieParams = session_get_cookie_params(); // Gets current cookies params.
    session_set_cookie_params(3600, $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
    session_name($session_name); // Sets the session name to the one set above.
    session_start(); // Start the php session
    session_regenerate_id(); // regenerated the session, delete the old one.
}

function checkUser()
{
    if (!session_id()) {
        sec_session_start();
    }
    //var_dump($_SESSION);
    if (!isset($_SESSION["user"])) {
        header('HTTP/1.1 401 Unauthorized');
        die();
    }

    $user = getUser($_SESSION["user"]);
    $un = $user[0]["username"];

    if (isset($_SESSION['login_string'])) {
        if ($_SESSION['login_string'] !== hash('sha512', "Come_On_You_Spurs" + $un)) {
            header('HTTP/1.1 401 Unauthorized');
            die(); // Yey!
        }
    } else {
        header('HTTP/1.1 401 Unauthorized');
        die();
    }
}

function isAuthorized($username, $password)
{
    $db = connectToDB();

    $q = "SELECT id FROM users WHERE username = :username AND password = :password";

    $result;
    $stm;
    try {
        $stm = $db->prepare($q);
        $stm->bindParam(':username', $username, PDO::PARAM_STR);
        $stm->bindParam(':password', $password, PDO::PARAM_STR);
        $stm->execute();
        $result = $stm->fetchAll();
    } catch (PDOException $e) {
        echo("Error creating query: " .$e->getMessage());

        return false;
    }

    return $result;

}

function getUser($username)
{
    $db = connectToDB();

    $q = "SELECT * FROM users WHERE username = :username";

    $result;
    $stm;
    try {
        $stm = $db->prepare($q);
        $stm->bindParam(':username', $username, PDO::PARAM_STR);
        $stm->execute();
        $result = $stm->fetchAll();
    } catch (PDOException $e) {
        echo("Error creating query: " .$e->getMessage());

        return false;
    }

    return $result;
}

function logout()
{
    session_destroy();
    header('Location: index.php');
}
