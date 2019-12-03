<?php
//include("connect.php");

function detail_check(){
    $errors = "<div id='error' class='alert alert-danger' role='alert'>";
    $user_name = strip_tags($_POST['username']);
    $user_password = strip_tags($_POST['password']);
    $user_type = strip_tags($_POST['type']);
    $user_fname = strip_tags($_POST['firstname']);
    $user_lname = strip_tags($_POST['lastname']);
    $user_email = strip_tags($_POST['email']);
    $user_team = strip_tags($_POST['team']);

    if(empty($user_name)){  
        $errors.= "Please Enter a valid username";
    }elseif(strlen($user_name) < 5){
        $errors .= "Usernames must be atleast 5 characters";
    }elseif($user_name == $user_password){
        $errors .= "Username and password can't be the same";
    }
    elseif (empty($user_password)){
        $errors.= "Passwords cannot be empty";
    }elseif(strlen($user_password) < 8){
        $errors.= "Passwords must be atleast 8 characters";
    }
    elseif (empty($user_type)){
        $errors.= "Please Enter a valid Type";
    }
    elseif (empty($user_fname)){
        $errors.= "Please Enter a valid first name";
    }
    elseif (empty($user_lname)){
        $errors.= "Please Enter a valid last name";
    }
    elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)){
        $errors.= "Please Enter a valid email";
    }
    elseif (empty($user_team)){
        $errors.= "Please select a valid team";
    }elseif(preg_match("[\W]", $user_name)){
        $errors.= "No Special characters";
    }elseif(preg_match("[\W]", $user_password)){
        $errors.= "No Special characters";
    }elseif(preg_match("[\W]", $user_type)){
        $errors.= "No Special characters";
    }elseif(preg_match("[\W]", $user_fname)){
        $errors.= "No Special characters";
    }elseif(preg_match("[\W]", $user_lname)){
        $errors.= "No Special characters";
    }
    else{
        try{
            //check details
            //Check to see if username already exists
            //If it does, cancelform
            $user = new Admin();
            $user->checkExisting(
             $user_name,
             $user_password, $user_type,
             $user_fname, $user_lname, 
             $user_email, $user_team);
            

        } catch(PDOException $e){
            $errors.= $e->get_message();
            echo "fail";
        }
    }
    $errors .= '</div>';

    echo $errors;
}



?>