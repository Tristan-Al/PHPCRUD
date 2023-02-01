
<?php
//Script to activate php warnings
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('header.php');
require_once('admin/config.php');
require_once('admin/DBConnection.php');
require_once('admin/func.php');

//try to connect to the database
$dbc = new DBConnection($dbsettings);
//Instance of a conexion
$conn = $dbc->getCon();
$errnumber = 0;

//Check if the user is logged
if ( func::checkLogin( $conn ) ) {
    //Check the user profile
    if($_SESSION['user_id'] == 1){
        //The user is admin
        header("Location: hero.php");
    }else{
        //Plain user
        header("Location: hero.php");
    }
}else {
    if ( isset( $_POST['submit'] ) ) {

        //Check the required fields
        if ( empty( $_POST['user_username'] ) && empty( $_POST['user_pass'] ) ) {
    
            $e = func::showError(1);
    
        }else{
            // Harvesting
            $username = $_POST['user_username'];
            $password = sha1( $_POST['user_pass'] );
    
            //Check if user and password are valid
            $sql = "SELECT * FROM `users` where `user_username` = :username AND `user_pass` = :userpass";
            $stmt = $conn->prepare( $sql );
            $stmt->execute([':username'=>$username, ':userpass'=>$password]);            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (isset( $row['user_id'] ) && $row['user_id'] > 0) {
                //create a new session and record it on DB
                $remember = 0;
                if( isset( $_POST['remember'] ) ){
                    $remember = 1;
                }

                //Record session
                func::recordSession( $conn, $row['user_id'], $row['user_username'], $remember);
                
                //Go to user profile
                header("Location: index.php");               
                
            }else {
                //Wrong username or password
                $errnumber = 3;
                $e = func::showError($errnumber);

            } 
    
        }
    
    }
}



?>
<body>
<div class="container"> 
    <div class="row"> 
        <div class="col-md-6"> 
            <div class="card"> 
                <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" class="box"> 
                <p class="text-muted"> <?php if(isset( $e )){ echo $e;} ?> </p>
                <h1>Sign in</h1> 
                <p class="text-muted"> Please enter your login and password!</p> 
                <input type="text" name="user_username" placeholder="Username"> 
                <input type="password" name="user_pass" placeholder="Password">
                <input type="checkbox" name="remember" value="1">
                <span class="text-muted">Remember me</span>
                <input type="submit" name="submit" value="login"> 
                <a class="forgot" href="register.php">You dont have an account yet?</a> 
<!--            <div class="col-md-12"> 
                    <ul class="social-network social-circle"> 
                        <li><a href="#" class="icoFacebook" title="Facebook"><i class="fab fa-facebook-f"></i></a></li> 
                        <li><a href="#" class="icoTwitter" title="Twitter"><i class="fab fa-twitter"></i></a></li> 
                        <li><a href="#" class="icoGoogle" title="Google +"><i class="fab fa-google-plus"></i></a></li> 
                    </ul> 
                </div> 
-->
            </form> 
        </div> 
    </div> 
</div>
</div>


<?php
        require_once('foother.php');
?>