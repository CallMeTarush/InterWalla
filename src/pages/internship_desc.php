<html>
    <head>
        
        <title> InternWalla </title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <!-- Latest compiled and minified JavaScript -->
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="../css/header.css" />
        <link rel="stylesheet" href="../css/desc.css" />
        <?php
            session_start();
            include("config.php");

            $title = $_GET['title'];

            $user_check_query = "SELECT * FROM internships WHERE title='$title'";
            $result = mysqli_query($db, $user_check_query);
            $internship = mysqli_fetch_assoc($result);
            $errors = array(); 
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                // username and password sent from form 
                

                $linkedin = mysqli_real_escape_string($db,$_POST['linkedin']);
                $desc = mysqli_real_escape_string($db,$_POST['description']);
                
                if (empty($linkedin)) { array_push($errors, "Username is required"); }
                if (empty($desc)) { array_push($errors, "Email is required"); }

                
                
                $user_check_query = "SELECT internship_id FROM internships WHERE title='$title'";
                $result = mysqli_query($db, $user_check_query);
                $user = mysqli_fetch_assoc($result);
                
                $internship_id = $user['internship_id'];
                $stud_id = $_SESSION['login_user'];

                $check_query = "SELECT applied_id FROM internships_applied WHERE internship_id = '$internship_id' and stud_id = '$stud_id'";
                $result = mysqli_query($db, $check_query);
                $check = mysqli_fetch_assoc($result);
                
                if ($check) { // if user exists                    
                    array_push($errors, "Already Applied!");                    
                }
                if (count($errors) == 0) {
                    $query = "INSERT INTO internships_applied (`internship_id`, `stud_id`, `description`, `linkedin_link`) VALUES('".$internship_id."', '".$stud_id."', '".$desc."','".$linkedin."')";
                    $result = mysqli_query($db, $query);
                    if($result) {
                        echo "<script type='text/javascript'>alert('Success');</script>";
                    }
                    else {
                        echo "<script type='text/javascript'>alert('nop');</script>";
                    }
                    // header('location: ../../index.php');
                }
            
            }

        ?>
    </head>
    <body>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"> <img src="../img/logo_2.png" style="width: auto; height: 100%;" > </a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                
                <?php if( isset($_SESSION['type'])): ?>
                    <?php if( $_SESSION['type'] == "student" ): ?>
                        <ul class="nav navbar-nav navbar-right">
                            <li><a class="nav-a" href="./login.php"> Hello Student! </a></li>
                            <li><a class="nav-a" href="./logout.php"> Logout </a></li>
                        </ul>
                    <?php elseif( $_SESSION['type'] == "emp" ) : ?>
                        <ul class="nav navbar-nav navbar-right">
                            <li><a class="nav-a" href="#">Hi Employer!</a></li>
                            <li><a class="nav-a" href="./logout.php"> Logout </a></li>
                        </ul>
                    <?php endif;?>
                <?php else: ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a class="nav-a" href="./login.php">Login</a></li>
                        <li><a class="nav-a" href="./signup.php">Sign Up</a></li>
                    </ul>
                <?php endif; ?>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>     
        <div class="col-md-12 container"> 
            <div class="col-md-12" >
                <div class="col-md-4">
                    <img src="../img/logo_2.png" style="width: 100%; height: auto;" >
                </div>
                <div class="col-md-8 text-center">
                    <h2> <?php echo($internship['title']); ?> </h2>
                    <p class="text-left" >  <?php echo($internship['description']); ?></p>
                </div>
            </div>
            <div class="col-md-12" >
                <h2 class="text-center" > Apply for this Internship </h2>
                <form action = "" id="apply_internship" method = "post" class="col-md-4 col-md-offset-4 text-center login-form" >
                    
                    <div class="text-left"> LinkedIn Profile: </div>
                    <input type="url" name="linkedin" style="width:80%;" /> <br/><br/>
                    <div class="text-left" >Description:</div>
                    <textarea name="description" form="apply_internship" style="width: 80%; height: 80px;" ></textarea> <br/><br/>
                    <?php print_r($errors) ?>
                    <input type = "submit" value = " Submit Application! "/><br />
                    
                </form>
            </div>
        </div>
    </body>
</html>