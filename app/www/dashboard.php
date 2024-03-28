<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<?php include $_SERVER['DOCUMENT_ROOT'] . "/common/head.php"; ?>

<head>
    <title>Dashboard | NoteMate</title>
</head>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>

<style>

</style>

<body>
    <div id="mainContent">
        <div class="container">
            <?php
            echo "<h1>Welcome, " . $_SESSION["username"] . "!</h1>";
            ?>
        </div>
    </div>
</body>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/common/footer.php"; ?>

</html>