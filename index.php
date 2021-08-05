<!DOCTYPE html>
<?php
session_start();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Wall Login/Registration</title>
</head>
<body>
    <!-- div for Registration -->
    <div>
        <h1>Registration</h1>
<?php if(isset($_SESSION['reg_errors'])){
    foreach($_SESSION['reg_errrors'] as $error){?>
    <p><?=$error?></p>
<?php
    }
}?>
    <form action="process.php" method="post">
        <label for="first_name">First Name</label>
        <input id="first_name" type="text" name="first_name">
        <label for="last_name">Last Name</label>
        <input id="last_name" type="text" name="last_name">
        <label for="email">Email</label>
        <input id="email" type="text" name="email">
        <label for="password">Password</label>
        <input id="password" type="text" name="password">
        <label for="c_password">Confirm Password</label>
        <input id="c_password" type="text" name="c_password">
        <input id="submit" value="register">
    </form>
    </div>
<!-- div for Login -->
    <div>
        <h1>Login</h1>
<?php if(isset($_SESSION['login_errors'])){
    foreach($_SESSION['login_errors'] as $error){?>
        <p><?=$error?></p>
<?php
    }
}   ?>
    <form action="process.php" method="post">
        <label for="email">Email</label>
        <input id="email" type="text" name='email'>
        <label for="password">Password</label>
        <input id="password" type="text" name="password">
        <input type="submit" value="Login">
    </form>
</div>
</body>
</html>