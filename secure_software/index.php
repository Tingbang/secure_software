<?php
include("header.php");
//session_start();
include ($dir . '/token.php');
if(isset($_SESSION['admin_session']) || (isset($_SESSION['user_session']))){
    header('Location: ' . $route . 'app.php ');
    exit;  
}
?>
<div class="container-fluid">
    <div class = "row justify-content-center">
        <div class="col-6">
            <div class = "login">
                <div class="container">
                    <h3 class="text-center mx-auto mt-2">Login</h3><hr>
                    <form action="scripts/login_check.php" method="POST">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control" id="username" aria-describedby="Username" placeholder="Username">
                            <small id="" class="form-text text-muted">Can't remember your details?</small>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input name="password" type="password" class="form-control" id="password" placeholder="Password">
                        </div>
                        <button type="submit" name="login" class="btn btn-primary">Submit</button>
                        <input type="hidden" name ="token" value="<?php echo Token::generate(); ?>">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include("eof.php");
?>