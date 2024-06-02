<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <form action="submit-login.php" method="post" class="login-form">
        <div>
            <label for="username">Korisničko ime:</label>
            <input type="text" id="username" name="username" required>
        </div>

        <div>
            <label for="password">Lozinka:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div>
            <button type="submit">Prijavi se</button>
        </div>

        <div>
            <a href="zaboravljenaLozinka.php">Zaboravili ste lozinku?</a>
        </div>
    </form>
</body>
</html>
