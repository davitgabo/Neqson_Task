<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
<div class="topnav">
    <a class="active" href="/">Login</a>
    <a href="/register">Register</a>
</div>
<form action="/action_page.php">
    <div class="container">
        <h1>Log In</h1>
        <p>Please fill in this form to Log In.</p>
        <hr>

        <label for="email"><b>Email</b></label>
        <input type="text" placeholder="Enter Email" name="email" id="email" required>

        <label for="psw"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="password" id="psw" required>

        <button type="submit" class="registerbtn">Log In</button>
    </div>

    <div class="container signin">
        <p>Create new account <a href="/register">Register</a>.</p>
    </div>
</form>

</body>
</html>
