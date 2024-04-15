<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$username = $_SESSION["username"] ?? "";
?>
<header id="header">
    <div class="appData">
        <img src="notemate.ico" width="64px"></img>
        <div>
            <div class="appName caveatFont">
                NoteMate
            </div>
            <div class="appMotto caveatFont">
                Simple note-taking app project created to test and learn PocketBase
            </div>
        </div>
    </div>
    <?php if ($username !== "") { ?>
        <div class="userData">
            <div class="username caveatFont">
                <i class="bi bi-person-fill"></i>
                <?php echo $username; ?>
            </div>
            <form hx-post="endpoints/users.php">
                <input type="hidden" name="logoutForm" value="1">
                <button type="submit" class="btn btnLight">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </button>
            </form>
        </div>
    <?php } ?>
</header>