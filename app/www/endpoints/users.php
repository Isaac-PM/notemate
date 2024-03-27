<?php

define("USERS_PATH", "http://127.0.0.1:8090/api/collections/users/");

function authUser(string $username, string $password)
{
    $requestPath = USERS_PATH . "auth-with-password";
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
    $response = @file_get_contents($requestPath, false, $context);
    if ($response === false) {
        return false;
    }
    // save user data to session
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['loginForm'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        if (authUser($username, $password)) {
            header("HX-Redirect: dashboard.php");
            http_response_code(200);
        } else {
            echo "Something went wrong. Please try again.";
        }
        exit();
    }
    http_response_code(405);
}