<?php
require ("../header.php");
include_once ($dir . '/admin/admin-class.php');
include_once ($dir . '/token.php');
include_once ($dir . '/functions.php');

if(isset($_SESSION['user_session'])){
    header("Location: ../index.php");
    exit();

}

$timer = new Token();
$timer->sessionCheck();

if(isset($_POST['register'], $_POST['token'])){
    
    if(Token::check($_POST['token'])){
        detail_check();
        
    }else{
        echo '<div id="error" class="alert alert-danger" role="alert">
        <strong>Error: </strong> Invalid CSRF TOKEN
        </div>';
    }
}
?>
<div class="container-fluid">
    <div class="row justify-content-center">

        <form class="mt-5" action="" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" id="username" aria-describedby="Username" placeholder="Username (No Special Characters)">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input name="password" type="password" class="form-control" id="password" placeholder="Password (Must be 8 Characters or more)">
            </div>
            <label for="firstname">Full Name</label>
            <div class="input-group">
                <input name="firstname" type="text" class="form-control" id="fname" placeholder="User's firstname">
                <span class="input-group-addon"> </span>
                <input name="lastname" type="text" class="form-control mb-2" id="lname" placeholder="Surname">

            </div>
            <div class="form-group">
                <label for="type">Type of Account</label>
                <select class="form-control" name="type" id ="type">
                    <option value="Developer">Developer</option>
                    <option value="Production">Client</option>
                    <option value="Testing">Tester</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="Type">Email</label>
                <input name="email" type="email" class="form-control" id="email" placeholder="Email">
            </div>
            <div class="form-group">
                <label for="Type">Team</label>
                <select class="form-control" name="team" id ="team">
                    <?php
                    $team_obj = new Admin();
                    $team_obj->getTeams();
                    echo $team_obj->getTeamID(0);
                    for($x = 0; $x < $team_obj->getRows(); $x++){
                    ?>
                    <option value="<?php echo "" . $team_obj->getTeamID($x);?>"><?php echo $team_obj->getTeamName($x);?></option><?php } ?>

                </select>

            </div>
                <button type="submit" name="register" class="btn btn-primary">Submit</button>
                <input type="hidden" name ="token" value="<?php echo Token::generate();?>">
            </form>
    </div>
</div>

<?php
include("../eof.php");
?>