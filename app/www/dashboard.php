<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$userId = $_SESSION["userId"] ?? "";
$username = $_SESSION["username"] ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<?php include $_SERVER['DOCUMENT_ROOT'] . "/common/head.php"; ?>

<head>
    <title>Dashboard | NoteMate</title>
</head>

<style>
    .notes {
        display: flex;

        flex-wrap: wrap;
        gap: 1rem;
    }

    .note {
        background-color: #FFFF99;
        box-shadow: 4px 3px 0 var(--granny-smith);
        display: flex;
        flex-direction: column;
        padding: 1rem;
        gap: 0.25rem;
        position: relative;
    }

    .note:hover {
        transform: translateY(-0.25rem);
    }

    .noteTitle {
        font-size: 1.5rem;
    }

    .d-flex {
        gap: 0.25rem;
    }

    .deleteButton {
        position: absolute;
        bottom: -1rem;
        right: -0.5rem;
        background-color: #FF6347;
        color: white;
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        margin-bottom: 0.5rem;
        border: none;
    }

    .deleteButton:hover {
        background-color: #CD5C5C;
    }

    .deleteButton:active {
        transform: scale(0.9);
    }
</style>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>
    <div id="mainContent">
        <div class="container d-flex align-items-center justify-content-between mt-3 mb-3">
            <?php
            echo "<h1 class='caveatFont'>Welcome, " . $username . "!</h1>";
            ?>
            <button class="btn btnDark" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                <i class="bi bi-pencil-fill"></i> Add a new note
            </button>
        </div>
        <div class="container notes mb-3" hx-ext="client-side-templates" hx-trigger="load"
            hx-get="http://127.0.0.1:8090/api/collections/notes/records?filter=(user='<?= $userId ?>')"
            hx-swap="innerHTML" mustache-template="noteTemplate">
        </div>
        <template id="noteTemplate">
            {{#items}}
            <div class="note">
                <h1>{{title}}</h1>
                <div class="noteDescription">{{description}}</div>
                <form hx-delete="endpoints/notes.php" hx-swap="none">
                    <input type="hidden" id="noteId" name="noteId" value="{{id}}">
                    <button type="Submit" class="deleteButton" type="Submit"><i class="bi bi-trash"></i></button>
                </form>
            </div>
            {{/items}}
        </template>
    </div>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/common/footer.php"; ?>
</body>

<div class="modal fade" id="addNoteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title caveatFont"><i class="bi bi-pencil-fill"></i> Add a new note</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="addNoteModalBody">
                <form id="addNoteForm" hx-post="endpoints/notes.php" hx-swap="none">
                    <input type="hidden" name="addNoteForm" value="1">
                    <div class="mb-3">
                        <label for="noteTitle" class="form-label">Title</label>
                        <input type="text" id="noteTitle" name="noteTitle" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="noteDescription" class="form-label">Description</label>
                        <div class="mdEdit">
                            <div></div>
                            <textarea id="noteDescription" name="noteDescription"
                                aria-describedby="noteDescriptionHelp"></textarea>
                        </div>
                        <div id="noteDescriptionHelp" class="form-text">
                            Supports Markdown syntax for headers, links, inline code, italics, bold, and images.
                        </div>
                    </div>
                    <button type="Submit" id="addNoteButton" class="btn btnDark">Submit</button>
                </form>
            </div>
            <div class="modal-footer">
                <span class="keyboardBinding">
                    <span class="key">ESC</span><span class="action">Toggle markdown</span>
                </span>
            </div>
        </div>
    </div>
</div>

</html>


<script>
    window.addEventListener('load', function () {
        let allNoteDescriptions = document.querySelectorAll(".noteDescription");
        for (let noteDescription of allNoteDescriptions) {
            let markdown = noteDescription.innerHTML;
            noteDescription.innerHTML = markdownToHTML(markdown);
        }
    })
</script>