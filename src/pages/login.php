<html>
    <head>
        
        <title> InternWalla </title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <!-- Latest compiled and minified JavaScript -->
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="../css/login.css" />
        <link rel="stylesheet" href="../css/header.css" />

        <?php
            include("config.php");
            session_start();
            
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                // username and password sent from form 
                
                $myusername = mysqli_real_escape_string($db,$_POST['username']);
                $mypassword = mysqli_real_escape_string($db,$_POST['password']); 
                $type = mysqli_real_escape_string($db,$_POST['type']); 
                if($type == "student") {
                    $sql = "SELECT stud_id FROM student WHERE username = '$myusername' and password = '$mypassword'";
                }
                else {
                    $sql = "SELECT emp_id FROM employer WHERE emp_username = '$myusername' and password = '$mypassword'";
                }
                
                $result = mysqli_query($db,$sql);
                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                // $active = $row['active'];
                
                $count = mysqli_num_rows($result);
                
                // If result matched $myusername and $mypassword, table row must be 1 row
                    
                if($count == 1) {
                    $_SESSION['login_user'] = $myusername;
                    $_SESSION['type'] = $type;
                    
                    header("location: ../../index.php");
                }else {
                    
                    $error = "Your Login Name or Password is invalid";
                    
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
                    document.getElementById('logginInAs').innerHTML = 'Loggin in as Student'
                    student_element.classList.add("active");
                    emp_element.classList.remove("active");
                }
                else {
                    document.getElementById('logginInAs').innerHTML = 'Loggin in as Employer'
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

                <form action = "" method = "post" class="col-md-4 col-md-offset-4 text-center login-form" >
                    
                    <div class="col-md-6 tab active" id="stud" onclick="changeType('student')" > Student </div>
                    <div class="col-md-6 tab" id="emp" onclick="changeType('emp')" > Employer </div>
                    
                    <h2 id="logginInAs">Loggin in as Student</h2>
                    <br>
                    <label>UserName  :</label><input type = "text" name = "username" /><br /><br />
                    <label>Password  :</label><input type = "password" name = "password" /><br/><br />
                    <input style="visibility: hidden" name = "type" id="type" value="student" /> <br/>
                    <?php echo $error ?>
                    <input type = "submit" value = " Login! "/><br />
                    
                </form>
        </div>


    </body>
</html>