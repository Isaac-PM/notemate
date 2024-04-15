<?php
session_start();
define("NOTES_PATH", "http://127.0.0.1:8090/api/collections/notes/");

enum NoteStatus
{
    case NOTE_CREATED;
    case NOTE_CREATION_FAILED;
    case NOTE_DELETED;
    case NOTE_DELETION_FAILED;
}

function createNote(string $title, string $description, string $userId): NoteStatus
{
    $requestUrl = NOTES_PATH . "records";
    $requestBody = array(
        'title' => $title,
        'description' => $description,
        'user' => $userId
    );
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
        return NoteStatus::NOTE_CREATION_FAILED;
    }
    return NoteStatus::NOTE_CREATED;
}

function deleteNote(string $noteId): NoteStatus
{
    $requestUrl = NOTES_PATH . "records/$noteId";
    $options = [
        "http" => [
            "method" => "DELETE"
        ]
    ];
    $context = stream_context_create($options);
    $response = @file_get_contents($requestUrl, false, $context);
    if ($response === false) {
        return NoteStatus::NOTE_DELETION_FAILED;
    }
    return NoteStatus::NOTE_DELETED;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addNoteForm'])) {
        $title = $_POST['noteTitle'];
        $description = $_POST['noteDescription'];
        $userId = $_SESSION['userId'];
        $noteStatus = createNote($title, $description, $userId);
        if ($noteStatus === NoteStatus::NOTE_CREATED) {
            header("HX-Redirect: dashboard.php");
            http_response_code(200);
        } else {
            echo "<script>alert('Failed to create note. Please try again.');</script>";
        }
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $delete_vars);
    $noteId = $delete_vars['noteId'] ?? null;
    if ($noteId !== null) {
        $noteStatus = deleteNote($noteId);
        if ($noteStatus === NoteStatus::NOTE_DELETED) {
            header("HX-Redirect: dashboard.php");
            http_response_code(200);
        } else {
            echo "<script>alert('Failed to delete note. Please try again.');</script>";
        }
    } else {
        http_response_code(400);
        echo "<script>alert('Invalid note ID.');</script>";
    }
}