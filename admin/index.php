<?php
 //Script to activate php warnings
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);

 require_once('config.php');
 require_once('DBConnection.php');
 require_once('func.php');

 $dbc = new DBConnection( $dbsettings );
 $conn = $dbc->getCon();

 //Reject not allowed users
 if ( !func::checkLogin( $conn ) ) {
    header("Location: ../index.php?error=5");
 }
 //Check if user is admin

 $tipolistado = 0;
 if ( isset( $_GET['type'] ) ) {
    $tipolistado = $_GET['type'];
 }

 $my_Url = "";


 //Get the words list to search
 if ( isset( $_GET['s'] ) ) {
    //Extract the search words
    //replace %20 for blanks
    //$raw_list = str_replace('%20', ' ', $_GET['words_list']);
    //Get the search words list as an array
    //$searchWords = explode(' ', $raw_list);
    $words_list = explode(' ', $_GET['words_list']);
 }else{
    //New search, we pressed the submit button or we have the very first entry
    $words_list = array();
    //The form have been submitted
    if ( isset( $_POST['submit'] ) ) {
        //If we have something to search...
        if ( !empty($_POST['s'] ) ) {
            //harvesting
            $words = $_POST['s'];

            //replace the puntiation marsk for blanks       
            $punctuationMarks = array('.',',',';',':','?','!','(',')','-','_','=','+','"','[',']','{','}','/','?','*','|','~','`','@','#','$','%','^','&','<','>');
            $filterwords = str_replace( $punctuationMarks, ' ', $words);
    
            //Get the filter words list as an array
            $words_list = explode( ' ', $filterwords );
            
            //Filter to delete empty items of the array
            $words_list = array_filter( $words_list );
        }else {
            $errormsg = func::showError(1);
        }
    }
 }

 //Prepare the query
 if ( !empty( $words_list ) ) {
    //Create an array with LIKE clause
    foreach($words_list as $word){
        $likes[] =  " `user_username` LIKE '%".$word."%'";
    }

    $where_clause = 'SELECT * FROM `users` WHERE'.implode(" OR ", $likes);

    //Set the list type
    if ( isset( $tipolistado ) ) {
        switch ( $tipolistado ) {
            case 0:
                $query = $where_clause;
                break;
            case 1:
                $query = $where_clause . " AND `status` = 0";
                break;
            case 2:
                $query = $where_clause . " AND `trash` = 1";
                break;
        }
    }
 }else{
    switch ( $tipolistado ) {
        case 0:
            $query = "SELECT * FROM `users`";
            break;
        case 1:
            $query = "SELECT * FROM `users` WHERE `status` = 0";
            break;
        case 2:
            $query = "SELECT * FROM `users` WHERE `trash` = 1";
            break;
        }
    }

    $stmt = $dbc->getQuery( $query );
    //Count members
    $totalmembers = $stmt->rowCount();
    
    $errormsg = "Total records found... " . $totalmembers;

    //Get the total members -zombies included
    $total = $dbc->getQuery( 'SELECT * FROM `users`' ) ->rowCount();
    $totalmemberstoday = $dbc->getQuery( 'SELECT * FROM `users` WHERE `member_from` = CURRENT_DATE()' ) ->rowCount();
    $lastweeknewmembers = $dbc->getQuery( 'SELECT * FROM `users` WHERE `member_from` = DATE_SUB( NOW(), INTERVAL 7 DAY)' ) ->rowCount();
    
    //Get the percentages of last week members and today new members
    $lastweeknewmemberspercent = number_format(( $lastweeknewmembers / $total ) * 100 , 2); 
    $todaypercent = number_format(( $totalmemberstoday / $total ) * 100 , 2 );
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>Admin area</title>
    <!-- Bootstrap Core CSS -->
    <link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/mdb.css" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="css/colors/blue.css" id="theme" rel="stylesheet">
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <script src="js/myjs.js"></script>
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>-->
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="fix-header fix-sidebar card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <!--<div class="preloader">-->
    <!--    <svg class="circular" viewBox="25 25 50 50">-->
    <!--        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> -->
    <!--    </svg>-->
    <!--</div>-->
    <!-- ============================================================== -->
    
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-toggleable-sm navbar-light">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="../buscar.php">
                        <!-- Logo icon -->
                        <b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                          <img src="../assets/images/logo-icon.png" alt="homepage" class="dark-logo" />

                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <span>
                            <!-- dark Logo text -->
                            <img src="../assets/images/logo-text.png" alt="homepage" class="dark-logo"  width="150" />
                        </span>
                    </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto mt-md-0 ">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:search()"><i class="ti-menu"></i></a> </li>
                        <li class="nav-item hidden-sm-down">
                            <form class="app-search p-l-20" action="index.php" method="post">
                                <input type="text" name="s" class="form-control" placeholder="Search for...<?php if (isset($_POST['s'])) echo $_POST['s'];?>"; >
                                <button type="submit" name="submit" id="submit" class="srh-btn" ><i class="ti-search"></i></button>
                            </form>
                        </li>
                        <li class="showmsg">
                            <?php if (isset($errormsg)) echo $errormsg;?>
                        </li>
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="../assets/images/users/1.png" alt="user" class="profile-pic m-r-5" />
                                 
                                <?php echo "admin";?>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li>
                            <a href="index.php" class="waves-effect"><i class="fa fa-clock-o m-r-10" aria-hidden="true"></i>Dashboard</a>
                        </li>
                        <li>
                            <a href="../index.php" class="waves-effect"><i class="fa fa-rebel m-r-10" aria-hidden="true"></i>Front End</a>
                        </li>
                         <li>
                            <a href="../log_out.php" class="waves-effect"><i class="fa fa-globe m-r-10" aria-hidden="true"></i>Log Out</a>
                        </li>
                
                    </ul>
                  
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor m-b-0 m-t-0">Dashboard</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        <a href="index.php?type=0" class="btn pull-right hidden-sm-down btn-success"> Refresh</a>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-block">
                                <h4 class="card-title">New Members</h4>
                                <div class="text-right">
                                    <h2 class="font-light m-b-0"><i class="ti-arrow-up text-success"></i> <?php echo $lastweeknewmembers;?></h2>
                                    <span class="text-muted">Last week new Members</span>
                                </div>
                                <span class="text-success"><?php echo $lastweeknewmemberspercent;?></span>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $lastweeknewmemberspercent;?>%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-block">
                                <h4 class="card-title">Total members: <?php echo $total;?></h4>
                                <div class="text-right">
                                    <h2 class="font-light m-b-0"><i class="ti-arrow-up text-info"></i> <?php echo $totalmemberstoday;?></h2>
                                    <span class="text-muted">Total members today</span>
                                </div>
                                <span class="text-info"><?php echo $todaypercent;?></span>
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $todaypercent;?>%; height: 6px;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-block">
                                <select class="custom-select pull-right list-type" name="list-type" id="list-type">
                                    <option value="0" <?php if ($tipolistado == 0) echo 'selected';?>>All</option>
                                    <option value="1" <?php if ($tipolistado == 1) echo 'selected';?>>Pending approval</option>
                                    <option value="2" <?php if ($tipolistado == 2) echo 'selected';?>>Zombies</option>
                                    <!--<option value="3">April</option>-->
                                </select>
                                <h4 class="card-title">Membership<?php if (isset($_GET['p'])) echo  " - Page".$_GET['p'];?></h4>
                                <div class="table-responsive m-t-40">
                                    <table class="table stylish-table">
                                        <thead>
                                            <tr>
                                                <th colspan="2">Username</th>
                                                <th>Name</th>
                                                <th>Graduation Year</th>
                                                <th>Member from</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ( $totalmembers == 0) {
                                                echo "I have nothing to show you...";
                                            }else {
                                                //set the number of lines per page
                                                $lines_per_page = 4;
                                                //check which page to display (Take it from url(if not we are in page 1))
                                                if ( !isset( $_GET['p'] ) ) {
                                                    //We must show the first page
                                                    $begin = 0; //first tuple in recordset
                                                    $page_number =1;
                                                } else {
                                                    //Get the number page from url
                                                    $page_number = $_GET['p'];
                                                    $begin = ($page_number - 1) * $lines_per_page;                                                    
                                                }
                                                //Get the total pages of the query
                                                $total_pages = ceil( $totalmembers / $lines_per_page );

                                                //Limit the query ro lines per page
                                                $custom_query = $query . ' LIMIT ' . $begin . ', ' . $lines_per_page;

                                                //Run the quey of this page
                                                $stmt = $dbc->getQuery( $custom_query );
                                                $subtotal = $stmt->rowCount();

                                                $colorsclass = array('round-danger', 'round-warning' , 'round-success', 'round-primary');
                                                
                                                while ( $row = $stmt->fetch() ) {
                                                    $rnd = mt_rand(0,4);
                                                    $incial = strtoupper( substr( $row['user_username'] , 0, 1 ) );
                                                    ?>
                                                        <tr>
                                                            <td style="width: 50px;">
                                                                <span class="round <?php echo $colorsclass[$rnd];?>"> <?php echo $incial;?> </span>
                                                            </td>
                                                            <td>
                                                                <h6> 
                                                                    <?php echo $row['user_username'];?>
                                                                </h6>
                                                                <small class="text-muted"> 
                                                                    <?php echo $row['degree'];?> 
                                                                </small>
                                                            </td>
                                                            <td>
                                                                <?php echo $row['first_name'] . " " . $row['last_name'];?>
                                                            </td>
                                                            <td>
                                                                <?php echo $row['year'];?> 
                                                            </td>
                                                            <td>
                                                                <?php echo $row['member_from'];?> 
                                                            </td>
                                                            <td>
                                                                <button id="<?php echo $row['user_id'];?>" type="button" class="iconbutton view_data_edit" data-item="" data-url="<?php echo $my_Url;?>">
                                                                   <i class="fa fa-edit azul m-r-10"></i>
                                                                </button>
                                                                <?php
                                                                if ( $row['user_id'] != 1) {
                                                                    //Not the admin
                                                                    if ( $row['trash'] == 0) {
                                                                        echo '<button id="'.$row['user_id'].'" type="button" class="iconbutton view_data_trash" data-item="" data-url="<?php echo $my_Url;?>">
                                                                            <i class="fa fa-trash rojo m-r-10"></i>
                                                                         </button>';
                                                                    }else {
                                                                        echo '<i class="fa fa-trash gris m-r-10"></i>';
                                                                    }
                                                                    if ( $row['status'] == 0) {
                                                                        echo '<button id="'.$row['user_id'].'" type="button" class="iconbutton view_data_checking" data-item="" data-url="<?php echo $my_Url;?>">
                                                                            <i class="fa fa-check-square-o verde m-r-10"></i>
                                                                        </button>';
                                                                    }else {
                                                                        echo '<i class="fa fa-check-square-o gris m-r-10"></i>';
                                                                    }
                                                                }

                                                                ?>
                                                                
                                                                
                                                            </td>
                                                        </tr>
                                                    <?php
                                                }

                                            }




                                            ?>
                                        </tbody>
                                      </table>

                                      <div class="paginacion">
                                        <ul class="pag">
                                       
                                        <?php
                                        //Setting the pagination URL
                                        $url = $_SERVER['PHP_SELF'];
                                        if ( isset( $tipolistado ) ) {
                                            $url = $url."?type=".$tipolistado;
                                        }

                                        //Convert words array into string in order to be sent through the url
                                        $words_serie = implode('%20', $words_list);

                                        if ( !empty($words_serie) ) {
                                            //We have words to search
                                            $url = $url.'&s='.$words_serie;
                                        }

                                        //Page navvigation
                                        if ( $total_pages > 1 ) {
                                            //There is more than 1 page, show page navigation
                                            if ( $page_number == 1 ) {
                                                //The first page, previous page is disabled
                                                echo '<li class="activo">Previous</li>';
                                
                                            }else {    
                                                //this is not the first page            
                                                echo '<li><a href="'.$url.'&p='.( $page_number - 1 ).'">Previous</a></li>';

                                            }

                                            //Draw the pages
                                            for ($i=1; $i <= $total_pages; $i++) { 
                                                //check if we are in actual page -> page link is disabled
                                                if ($page_number == $i) {
                                                    echo '<li class="activo">'.$i.'</li>';
                                                }else{            
                                                    echo '<li><a href="'.$url.'&p='.$i.'">'.$i.'</a></li>';
                                                }
                                            }
                                
                                            if ( $page_number != $total_pages ) {
                                                echo '
                                                    <li><a href="'.$url.'&p='.( $page_number + 1 ).'">Next</a></li>'; 
                                            }else {                           
                                                echo '
                                                    <li class="activo">Next</li>';
                                            }
                                        }
                                        ?>
                                        </ul>
                                      </div>
                                </div> <!--div table responsive-->
                        </div><!-- div card box-->
                    </div> <!-- card -->
                </div>  <!-- col -->
            </div> <!-- row -->
            </div>  <!-- container fluid -->
            <?php
                require_once('antencion.php');
                require_once('modalediting.php');
                require_once('modalchecking.php');
            ?>
            <footer class="footer text-center authorname">
                © 2022 t Admin Area
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->    
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/plugins/bootstrap/js/tether.min.js"></script>
    <script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="../assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <!--Custom JavaScript -->
    <script src="js/custom.min.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!-- Flot Charts JavaScript -->
    <script src="../assets/plugins/flot.tooltip/js/jquery.flot.js"></script>
    <script src="../assets/plugins/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>
    <script src="js/flot-data.js"></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    
</body>

</html>