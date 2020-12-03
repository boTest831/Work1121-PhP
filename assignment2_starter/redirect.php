<?php
require 'includes/functions.php';

if(count($_POST) > 0)
{
    if($_GET['from'] == 'login')
    {
        $found = false; // assume not found

        $user = trim($_POST['username']);
        $pass = trim($_POST['password']);

        if(checkUsername($user))
        {
            $found = checkUserDB($user, $pass);
            if($found)
            {
                session_start();
                $_SESSION['loggedFlag']=true;
                $_SESSION['username']=$user;
                header('Location: thankyou.php?from=login&username='.filterUserName($user));
                exit();
            }
        }

        setcookie("error_message", "Sorry, login validation fails.", time()+1);
        header('Location: login.php');
        exit();
    }
    elseif($_GET['from'] == 'signup')
    {
        if(findUserDB($_POST["username"])) {
            setcookie("error_message", "Sorry, username already exists.", time()+1);
            header('Location: signup.php');
            exit();
        }
        if(checkSignUp($_POST) && saveUserDB($_POST))
        {
            session_start();
            $_SESSION['loggedFlag']=true;
            $_SESSION['username']=$_POST["username"];
            header('Location: thankyou.php?from=signup&username='.filterUserName(trim($_POST['username'])));
            exit();
        }

        setcookie("error_message", "Sorry, signup validation fails.", time()+1);
        header('Location: signup.php');
        exit();
    }
}

header('Location: index.php');
exit();
