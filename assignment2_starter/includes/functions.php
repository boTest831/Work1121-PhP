<?php
require 'includes/database.php';

define('SALT', 'a_very_random_salt_for_this_app');

/**
 * Look up the user & password pair from the text file.
 *
 * Passwords are simple md5 hashed.
 *
 * Remember, md5() is just for demonstration purposes.
 * Do not do this in production for passwords.
 *
 * @param $user string The username to look up
 * @param $pass string The password to look up
 * @return bool true if found, false if not
 */
function findUser($user, $pass)
{
    $found = false;
    $lines = file('users.txt');

    foreach ($lines as $line) {
        $pieces = preg_split("/\|/", $line); // | is a special character, so escape it
        $hash = md5($pass . SALT);

        if ($pieces[0] == $user && trim($pieces[1]) == $hash) {
            $found = true;
        }
    }

    return $found;
}

function findUserDB($user)
{
    $database = new database();
    $found = false;
    $result = $database->query("SELECT * FROM USERS WHERE username = '$user'");

    if ($result->num_rows > 0) {
        $found = true;
    }
    return $found;
}

function checkUserDB($user, $pass)
{
    $database = new database();
    $found = false;
    $hash = md5($pass . SALT);

    $result = $database->query("SELECT * FROM USERS WHERE username = '$user' AND password = '$hash'");

    if ($result->num_rows > 0) {
        $found = true;
    }
    return $found;
}

/**
 * Remember, md5() is just for demonstration purposes.
 * Do not do this in production for passwords.
 *
 * @param $data
 * @return bool returns false if fopen() or fwrite() fails
 */
function saveUser($data)
{
    $success = false;

    $fp = fopen('users.txt', 'a+');

    if ($fp != false) {
        $username = trim($data['username']);
        $password = trim($data['password']);
        $hash = md5($password . SALT);

        $results = fwrite($fp, $username . '|' . $hash . PHP_EOL);

        fclose($fp);

        if ($results) {
            $success = true;
        }
    }

    return $success;
}

function saveUserDB($data)
{
    $success = false;
    $username = trim($data['username']);
    $password = trim($data['password']);
    $hash = md5($password . SALT);

    $database = new database();

    $result = $database->query("INSERT INTO users (username, password) VALUES ('$username','$hash')");

    if ($result === TRUE) {
        $success = true;
    }

    return $success;
}

function checkUsername($username)
{
    return preg_match('/^([a-z]|[0-9]){8,15}$/i', $username);
}

/**
 * @param $data
 * @return bool
 */
function checkSignUp($data)
{
    $valid = true;

    // if any of the fields are missing
    if (trim($data['username']) == '' ||
        trim($data['password']) == '' ||
        trim($data['verify_password']) == '') {
        $valid = false;
    } elseif (!checkUsername(trim($data['username']))) {
        $valid = false;
    } elseif (!preg_match('/((?=.*[a-z])(?=.*[0-9])(?=.*[!?|@])){8}/', trim($data['password']))) {
        $valid = false;
    } elseif ($data['password'] != $data['verify_password']) {
        $valid = false;
    }

    return $valid;
}

function filterUserName($name)
{
    // if it's not alphanumeric, replace it with an empty string
    return preg_replace("/[^a-z0-9]/i", '', $name);
}

function getAllProfiles()
{
    $database = new database();
    return $database->query("SELECT * FROM profiles");
}

function addProfile($user, $picture)
{
    $database = new database();

    $result = $database->query("INSERT INTO profiles (username, picture) VALUES ('$user','$picture')");
    $success = false;
    if ($result === TRUE) {
        $success = true;
    }
    return $success;
}

function findProfile($id, $username)
{
    $database = new database();
    $found = false;
    $result = $database->query("SELECT * FROM profiles WHERE id = '$id' AND username = '$username'");
    if ($result->num_rows > 0) {
        $found = true;
    }
    return $found;
}

function deleteProfile($id)
{
    $database = new database();
    $database->query("DELETE FROM profiles WHERE id = '$id'");
}

function checkDuplicateProfile($user) {
    $database = new database();
    $found = false;
    $result = $database->query("SELECT * FROM profiles WHERE username = '$user'");
    if ($result->num_rows > 0) {
        $found = true;
    }
    return $found;
}