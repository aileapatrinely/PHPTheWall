<?php
session_start();
require_once('new-connection.php');
$_SESSION['reg-errors']=[];
$_SESSION['login-errors']=[];

//registration form
// if statement check isset and not empty
if(isset($_POST['action'])&& $_POST['action']=='register'){
    if(!empty($_POST['first_name'])){
        // set errors
        $_SESSION['reg_errors'][]="First Name is required.";
    }
    //else if ctype alpha for first name
    else if(!ctype_alpha($_POST['first_name'])){
        // set errors
        $_SESSION['reg_errors'][]="First Name cannot have any numbers.";
    }
    //else if strlen
    else if(strlen($_POST['first_name'])<2){
        $_SESSION['reg_errors'][]="First Name has to be longer than 2 characters, if not, go get that legally changed, then try again.";
    }
    //if not last name
    if(!$_POST['last_name']){
        //set error
        $_SESSION['reg_errors'][]="Last Name is required.";
    }
    //else if ctype alpha last name
    else if(!ctype_alpha($_POST['last_name'])){
        //errors
        $_SESSION['reg_errors'][]="Last Name cannot have any numbers.";
    }
    //else if strlen
    else if(strlen($_POST['last_name'])<2){
        $_SESSION['reg_errors'][]="Last Name has to be longer than 2 characters, if not, go get that legally changed, then try again.";
    }
    //if email
    if(!$_POST['email']){
        //errors
        $_SESSION['reg_errors'][]="Email is required.";
    }
    //else if filter var?
    else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        //errors
        $_SESSION['reg_errors'][]="Email is not valid.";
    }
    //if no password
    if(!$_POST['password']){
        //set error
        $_SESSION['reg_errors'][]="Password is required.";
    }
    //else if strlen
    else if($_POST['password']<=6){
        //errors
        $_SESSION['reg_errors'][]="Password must be greater than 6 characters.";
    }
    //if c_password
    if(!$_POST['c_password']){
        //errors
        $_SESSION['reg_errors'][]="Password Confirmation is required.";
    }
    //else if c_password != password
    else if($_POST['c_password']!=$_POST['password']){
        //errors
        $_SESSION['reg_errors'][]="Passwords must match.";
    }
//this is where I'll check the session errors
    if(count($_SESSION['regerrors'])>0){
        //got some errors send 'em back
        header('location: index.php');
        //kill it
        die();
    }
    //else no errors check if email is taken
    else{
        //checking if email is taken
        $query="SELECT id FROM email = '{$_POST['email']}'";
        $user=fetch_record($query);
        if(!empty($user)){
            //setting session error
            $_SESSION['reg_errors'][]="Email is taken.";
            header('location: index.php');
            die();
        } else{
        //escape malicious strings
        $first_name=escape_this_string($_POST['first_name']);
        $last_name=escape_this_string($_POST['last_name']);
        $email=escape_this_string($_POST['email']);
        $password=escape_this_string($_POST['password']);
        //create a salt
        $salt=bin2hex(openss1_random_psuedo_bytes(22));
        //encrypt password with md5 & the salt
        $enc_password=md5($password .''. $salt);

        //finally query time!
        $query = "INSERT INTO users (first_name, last_name, email, password, salt) VALUES ('{$first_name}', '{$last_name}', '{$email}', '{$enc_password}', '{$salt}')";
        //set last_row_id, so query can run
        $last_row_id=run_mysql_query($query);

            if($last_row_id>0){
                //saved successfully
                $_SESSION['user_id']=$last_row_id;
                $_SESSION['email']=$email;
                header('location: success.php');
                die();
            }
            //else kill it
            else{
            die('The INSERT failed. YOU failed!');
            }
        }
    }

//login form
}else if(isset($_POST['action']) && $_POST['action'] == 'login'){
    //don't let 'em mess up your db!
    $email=escape_this_string($_POST['email']);
    $password=escape_this_string($_POST['password']);
    //find user based on email
    $query="SELECT * FROM users WHERE email = '{$email}'";
    $user=fetch_record($query);
    //if user is found then we compare passwords and do encryption comparison
    if(!empty($user)){
        $enc_password=md5($password .''. $user['salt']);
        if($user['password']==$enc_password){
            //save the email and user_id in sesson
            $_SESSION['user_id']=$user['id'];
            $_SESSION['email']=$user['email'];
            header('location: success.php');
            die();
        }
        //else password didn't match
        else{
            $_SESSION['login_errors'][]="Email/Password Combination Failed";
            header('location: index.php');
            die();
        }
    }
    //email not found, but don't tell them that
    else{
        $_SESSION['login_errors'][]="Email/Password Combination Failed";
        header('location: index.php');
        die();
    }
}
//logout form
else if(isset($_POST['action']) && $_POST['action']=='logout'){
    session_destroy();
    header('location: index.php');
    die();
}
//new message form needs to be and else if chain with that stuff^
else if(isset($_POST['action']) && $_POST['action']=='new_message'){
    $message = escape_this_string($_POST['message']);

    $query= "INSERT INTO messages (user_id, message, created_at, updated_at) 
VALUES ('{$_SESSION['user_id']}', '{$message}', NOW(), NOW())";
    $last_row_id=run_mysql_query($query);
    if($last_row_id>0){
        //successful message save
        //get all messages and comments again
        get_messages_and_comments();
    } else{
        die('Insert failed for message.');
    }
    header('location: success.php');
    die();
}
//new comment form
else if(isset($_POST['action']) && $_POST['action']=='new_comment'){
    $comment=escape_this_string($_POST['comment']);

    $query= "INSERT INTO comments (user_id, message_id, comment, created_at, updated_at) 
VALUES ('{$_SESSION['user_id']}', '{$_POST['message_id']}', '{$coment}', NOW(), NOW())";
    $last_row_id=run_mysql_query($query);
    if($last_row_id>0){
        //successful comment save
        //get all messages and comments again
        get_messages_and_comments();
    } else{
        die('Insert failed for comment.');
    }
    header('location: success.php');
    die();
}
//delete message form
else if(isset($_POST['action']) && $_POST['action']=='delete'){
    $message_id=escape_this_string($_POST['message_id']);
    $query="DELETE FROM messages WHERE id = {$message_id}";
    run_mysql_query($query);

    get_messages_and_comments();
    header('location: success.php');
    die();
}
//no form
else{
    //someone on process.php w/o submitting a form
    header('location: index.php');
    die();
}
//get_messages_and_comments function
function get_messages_and_comments(){
    $messages_query= "SELECT messages.*, users.id 
    AS user_id, users.first_name, users.last_name FROM messages 
    JOIN users ON messages.user_id = users.id ORDER BY id DESC";
    $messages = fetch_all($messages_query);

    $comments_query= "SELECT comments.*, users.first_name, users.last_name 
    FROM comments JOIN users ON comments.user_id = users.id";
    $comments = fetch_all($comments_query);

    $_SESSION['messages'] = $messages;
    $_SESSION['comments'] = $comments;
}
?>