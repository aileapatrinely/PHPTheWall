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
    <div> <!--1-->
    <div><!--logout-->
<!--form for logout-->
    <form action="process.php" method="post">
        <input type="hidden" name="action" value="logout">
        <input type="submit" value="Logging out? That's fine. No, I'm not upset.">
    </form>
</div><!--logout-->
<div><!--messages-->
    <!--form for messages-->
    <form action="process.php" method="post">
        <label for="message">Post a Message</label>
        <input type="hidden" name="action" value="new_message">
        <textarea id="messaage" name="message"></textarea>
        <input type="submit" value="You sure you want to say that? Yeah? Okay.">
</form>
</div><!--messages form-->
<!-- this is where messages will display--> 
<?php foreach($_SESSION['messages'] as $message){?>
    <div><!--outer-->
        <div><!--inner-->
            <h4><?= $message['first_name'] ?> <?= $message['last_name']?> 
            - <?= date_format(date_create($message['created_at']), 'F jS Y') ?> </h4>
            <p><?= $message['message'] ?></p>
        <?php
            $time_diff= date_diff(date_create($message['created_at']), date_create($now));
            if (($message['user_id']==$_SESSION['user_id']) && ($time_dif->days < 1 && $time_diff->i <=30)){?>
            <!--dis where da delete go-->
            <form action="process.php" method="post">
                <input type="hiddden" name="action" value="delete">
                <input type="hidden" name="message_id" value="<?= $message['id']?>">
                <input type="submit" value="Delete">
            </form>
            }
<?php }?>
</div><!--inner-->
</div><!--outer-->
<!--form for comments-->
<form action="process.php" method="post">
</form>
<!--comment display-->
<!--dates?-->
</div><!--1-->
</body>
</html>
