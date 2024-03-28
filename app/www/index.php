<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<?php include $_SERVER['DOCUMENT_ROOT'] . "/common/head.php"; ?>

<head>
    <title>Welcome! | NoteMate</title>
</head>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/common/header.php"; ?>

<style>
    .homeContainer {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    .loginContainer {
        background-color: var(--light);
        padding: 1rem;
    }

    .instructions {
        font-size: 1rem;
        color: var(--accent);
    }

    #loginResult {
        color: var(--accent);
    }
</style>

<body>
    <div id="mainContent">
        <div class="container homeContainer">
            <div class="loginContainer rounded">
                <h1 class="caveatFont">Welcome!</h1>
                <p class="caveatFont instructions">
                    Login to start taking notes; if you don't have an account it will be created automatically.
                </p>
                <form hx-post="endpoints/users.php" hx-target="#loginResult">
                    <input type="hidden" name="loginForm" value="1">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" required id="username" name="username" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" pattern=".{8,}" required aria-describedby="passwordHelp" id="password"
                            name="password" class="form-control">
                        <div id="passwordHelp" class="form-text">Password must be at least 8 characters long.</div>
                    </div>
                    <button type="submit" id="loginSubmitButton" class="btn btnDark">Submit</button>
                    <div id="loginResult" class="caveatFont instructions">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/common/footer.php"; ?>

</html>