<?php
session_start();
define("USERS_PATH", "http://127.0.0.1:8090/api/collections/users/");

enum UserStatus
{
    case USER_EXISTS;
    case USER_NOT_EXIST;
    case USER_CREATED;
    case USER_CREATION_FAILED;
    case USER_AUTHENTICATED;
    case USER_AUTHENTICATION_FAILED;
}

function userExists(string $username): UserStatus
{
    $requestUrl = USERS_PATH . "records?filter=(username='$username')";
    $response = @file_get_contents($requestUrl);
    $response = @json_decode($response, true);
    $totalItems = $response["totalItems"];
    if ($totalItems === 0) {
        return UserStatus::USER_NOT_EXIST;
    }
    return UserStatus::USER_EXISTS;
}

function createUser(string $username, string $password): UserStatus
{
    $requestUrl = USERS_PATH . "records";
    $requestBody = array(
        'username' => $username,
        'password' => $password,
        'passwordConfirm' => $password
    );
    $options = array(
        'http' => array(
            "method" => "POST",
            "header" => "Content-Type: application/json",
            'content' => json_encode($requestBody)
        )
    );
    $context = stream_context_create($options);
    $response = @file_get_contents($requestUrl, false, $context);
    if ($response === false) {
        return UserStatus::USER_CREATION_FAILED;
    }
    return UserStatus::USER_CREATED;
}

function authenticateUser(string $username, string $password): UserStatus
{
    if (userExists($username) === UserStatus::USER_EXISTS) {
        $requestUrl = USERS_PATH . "auth-with-password";
        $requestBody = [
            "identity" => $username,
            "password" => $password
        ];
        $options = [
            "http" => [
                "method" => "POST",
                "header" => "Content-Type: application/json",
                "content" => json_encode($requestBody)
            ]
        ];
        $context = stream_context_create($options);
        $response = @file_get_contents($requestUrl, false, $context);
        if ($response === false) {
            return UserStatus::USER_AUTHENTICATION_FAILED;
        }
        $response = @json_decode($response, true);
        $_SESSION["userId"] = $response["record"]["id"];
        $_SESSION["username"] = $response["record"]["username"];
        return UserStatus::USER_AUTHENTICATED;
    } else {
        $status = createUser($username, $password);
        if ($status === UserStatus::USER_CREATED) {
            return authenticateUser($username, $password);
        } else {
            return UserStatus::USER_CREATION_FAILED;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['loginForm'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $status = authenticateUser($username, $password);
        if ($status === UserStatus::USER_AUTHENTICATION_FAILED || $status === UserStatus::USER_CREATION_FAILED) {
            echo "Something went wrong. Please try again.";
        } else {
            header("HX-Redirect: dashboard.php");
            http_response_code(200);
        }
        exit();
    } else if (isset($_POST['logoutForm'])) {
        session_destroy();
        header("HX-Redirect: index.php");
        http_response_code(200);
        exit();
    }
    http_response_code(405);
}