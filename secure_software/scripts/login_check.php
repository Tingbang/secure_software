<?php
session_start();
include_once ('../token.php');
include '../connect.php';

if(Token::check($_POST['token'])){
    if(isset($_POST['login'], $_POST['token'])){
    
        $user_try = !empty($_POST['username']) ? strip_tags($_POST['username']): null;
        $pass_try = !empty($_POST['password']) ? strip_tags($_POST['password']): null;
    
        $sql = "SELECT username, password, admin FROM user WHERE username = ?";
        $stmt=$conn->prepare($sql);
        $stmt->bindParam(1, $user_try);
    
        if($stmt->execute()){
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $db_user = $result['username'];
            $db_pass = $result['password'];
       
            include_once("../admin/admin-class.php");

            $login_obj = new Admin();
           
            if(password_verify($pass_try, $db_pass)){
                if($result['admin'] == 101){
                    $_SESSION['user_session'] = $user_try;
                    $_SESSION['logged_in'] = time();
                    header("Location: ../app.php");
                    exit();
                }else{
                  
                    $_SESSION['admin_session'] = $user_try;
                    $_SESSION['logged_in'] = time();
                    header("Location: ../app.php");
                    exit();
                }
    
            }else{
                header("Location: ../invalid_cred.php");
                exit();
            }
    
        }else{
            header("Location: ../invalid_cred.php");
            exit();
        }
    
    }else{
        header("Location: ../index.php");
        exit();
    }
}else{
    header("Location: ../invalid.php");
    exit();
}

?>