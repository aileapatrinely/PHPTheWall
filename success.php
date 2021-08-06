<!DOCTYPE html>
<?php session_start();?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Wall</title>
</head>
<body>
    <h4>Coding Dojo Wall</h4>
    <h4>Welcome <?= $_SESSION['first_name']?></h4>
    <div>
    <--form for messages-->
    <form action="process.php" method="post">
        <input type="hidden" name="action" value="logout">
        <input type="submit" value="Logout">
    </form>
</div>
<div>
    <form>
        <--form for comments-->
</form>
</div>
<div>
<form action="process.php" method="post">
        <input type="hidden" name="action" value="logout">
        <input type="submit" value="Logging out? That's fine. No, I'm not upset.">
</form>
</div>
</body>
</html>
