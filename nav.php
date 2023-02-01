
<?php
    //Script to activate php warnings
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

?>

<div class="navbar">
    
    <?php
    if ($_SERVER['PHP_SELF'] == '/PHPCRUD2/index.php' ) {//SI ESTOY EN VIEW.PHP MUESTRO

        echo '<a href="view_profile.php" >View Profile</a>
        <a href="edit_profile.php" >Edit Profile</a>';

    } else if ($_SERVER['PHP_SELF'] == '/PHPCRUD2/edit_profile.php' ){//SI ESTOY EN EDITPROFILE MUESTRO OTRA COSA
        
        echo '<a href="view_profile.php" >View Profile</a>';

    } else if ($_SERVER['PHP_SELF'] == '/PHPCRUD2/view_profile.php' ){//SI ESTOY EN EDITPROFILE MUESTRO OTRA COSA
        
        echo '<a href="edit_profile.php" >Edit Profile</a>';

    } 

    //SI SOY ADMIN MUESTRO OTRA COSA
    if ( $_SESSION['user_id'] == 1) {

        echo '<a href="admin/index.php">Admin Area</a>';
        
    }
    ?>
    <a href="log_out.php">Log Out</a>
</div>