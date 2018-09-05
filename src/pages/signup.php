<html>
    <head>
        
        <title> InternWalla </title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <!-- Latest compiled and minified JavaScript -->
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="../css/header.css" />
        <link rel="stylesheet" href="../css/signup.css" />
        <?php
            include("config.php");
            
            $errors = array();             
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                // username and password sent from form 


                $myusername = mysqli_real_escape_string($db,$_POST['username']);
                $email = mysqli_real_escape_string($db,$_POST['email']);
                $full_name = mysqli_real_escape_string($db,$_POST['full_name']);
                $mypassword = mysqli_real_escape_string($db,$_POST['password']); 
                $confpassword = mysqli_real_escape_string($db,$_POST['confpassword']); 
                $type = mysqli_real_escape_string($db,$_POST['type']); 
                
                if (empty($myusername)) { array_push($errors, "Username is required"); }
                if (empty($email)) { array_push($errors, "Email is required"); }
                if (empty($mypassword)) { array_push($errors, "Password is required"); }
                if ($mypassword != $confpassword) {
                  array_push($errors, "The two passwords do not match");
                }

                if($type == "student") {
                    $user_check_query = "SELECT * FROM student WHERE username='$myusername' OR email='$email' LIMIT 1";
                    $result = mysqli_query($db, $user_check_query);
                    $user = mysqli_fetch_assoc($result);
                    if ($user) { // if user exists
                        if ($user['username'] === $myusername) {
                          array_push($errors, "Username already exists");
                        }
                    
                        if ($user['email'] === $email) {
                          array_push($errors, "email already exists");
                        }
                    }
                    if (count($errors) == 0) {
                        $query = "INSERT INTO student (`username`, `email`, `password`, `full_name`) VALUES('".$myusername."', '".$email."', '".$mypassword."','".$full_name."')";
                        $result = mysqli_query($db, $query);
                        if($result) {
                            echo "<script type='text/javascript'>alert('$result');</script>";
                        }
                        else {
                            echo "<script type='text/javascript'>alert('nop');</script>";
                        }
                        $_SESSION['login_user'] = $myusername;
                        $_SESSION['type'] = $type;
                        header('location: ./login.php');
                    }
                }
                else {
                    $user_check_query = "SELECT * FROM employer WHERE username='$myusername' OR email='$email' LIMIT 1";
                    $result = mysqli_query($db, $user_check_query);
                    $user = mysqli_fetch_assoc($result);
                    if ($user) { // if user exists
                        if ($user['username'] === $myusername) {
                          array_push($errors, "Username already exists");
                        }
                    
                        if ($user['email'] === $email) {
                          array_push($errors, "email already exists");
                        }
                    }
                    if (count($errors) == 0) {
                        $query = "INSERT INTO employer (`emp_username`, `email`, `password`, `organization`) VALUES('".$myusername."', '".$email."', '".$mypassword."','".$full_name."')";
                        mysqli_query($db, $query);
                        $_SESSION['login_user'] = $myusername;
                        $_SESSION['type'] = $type;
                        header('location: ./login.php');
                    }
                }

                
            }
        ?>
        <script>
            var student = true;
            function changeType(type) {
                student = !student;
                document.getElementById('type').value= type;
                var student_element = document.getElementById("stud");
                var emp_element = document.getElementById("emp");

                if(student) {           

                    student_element.classList.add("active");
                    emp_element.classList.remove("active");
                    document.getElementById('logginInAs').innerHTML = 'Signing up as Student';
                }

                else {
                    document.getElementById('logginInAs').innerHTML = 'Signing up as Employer';
                    student_element.classList.remove("active");
                    emp_element.classList.add("active");
                }
            }
        </script>
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
        <div class="col-md-12">

                <form action = "" method = "post" class="col-md-4 col-md-offset-4 text-center signup-form" >
                    
                    <div class="col-md-6 tab active" id="stud" onclick="changeType('student')" > Student </div>
                    <div class="col-md-6 tab" id="emp" onclick="changeType('emp')" > Employer </div>

                    <h2 id="logginInAs">Signing up as Student</h2>

                    <label>UserName  </label><br /><input type = "text" name = "username" /><br /><br />
                    <label>Email  </label><br /><input type = "email" name = "email" /><br /><br />
                    <label id="full_name" >Full Name  </label><br /><input type = "text" name = "full_name" /><br /><br />
                    <label>Password  </label><br /><input type = "password" name = "password" /><br/><br />
                    <label>Confirm Password  </label><br /><input type = "password" name = "confpassword" /><br/><br />
                    <input style="visibility: hidden" name = "type" id="type" value="student" /> <br/>
                    <?php 
                        echo '<pre>'; print_r($errors); echo '</pre>';
                    ?>
                    <input type = "submit" value = " Login! "/><br />
                    
                </form>
        </div>

    </body>
</html>