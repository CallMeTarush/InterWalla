<html>
    <head>
        
        <title> InternWalla </title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <!-- Latest compiled and minified JavaScript -->
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="src/css/header.css" />
        <link rel="stylesheet" href="src/css/main.css" />
        <?php session_start() ?>

        <?php
            include("./src/pages/config.php");
            
            $sql = "SELECT title FROM internships";
            $internships = array();
            $result = mysqli_query($db,$sql);
            // print_r($result);
            $rows = mysqli_num_rows($result);
            for ( $x = 0; $x < $rows; $x++ ) {
                $row = mysqli_fetch_array($result,MYSQLI_NUM);
                array_push($internships,$row[0] );
            }
            // $row = mysqli_fetch_array($result,MYSQLI_NUM);
            
            // print_r($row);
            // $row = mysqli_fetch_array($result,MYSQLI_NUM);
            
            // print_r($row);
            // $row = mysqli_fetch_array($result,MYSQLI_NUM);
            
            // print_r($row);
            
            //print_r($row[0]);
            
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                // username and password sent from form 
                $errors = array(); 

                $title = mysqli_real_escape_string($db,$_POST['username']);
                $description = mysqli_real_escape_string($db,$_POST['email']);
                $date_valid = mysqli_real_escape_string($db,$_POST['full_name']);
                
                if (empty($title)) { array_push($errors, "Title is required"); }
                if (empty($description)) { array_push($errors, "Description is required"); }
                if (empty($date_valid)) { array_push($errors, "Date is required"); }
                

                
                $user_check_query = "SELECT * FROM internships WHERE title='$title' LIMIT 1";
                $result = mysqli_query($db, $user_check_query);
                $user = mysqli_fetch_assoc($result);
                if ($user) { // if user exists
                    array_push($errors, "Title already exists");                    
                }
                if (count($errors) == 0) {
                    $emp_username = $_SESSION['login_user'];
                    $sql = "SELECT emp_id FROM employer WHERE emp_username = '$emp_username'";
                    $result = mysqli_query($db,$sql);
                    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                    
                    $emp_id = $row['emp_id'] ;

                    $query = "INSERT INTO internships (`emp_id`, `title`, `description`, `date_valid`) VALUES('".$emp_id."', '".$title."', '".$description."','".$date_valid."')";
                    $result = mysqli_query($db, $query);
                    if($result) {
                        echo "<script type='text/javascript'>alert('Done!');</script>";
                    }
                    else {
                        echo "<script type='text/javascript'>alert('Error!');</script>";
                    }
                    
                    
                }
            
                
            }
        ?>
        
        <script type="application/javascript">
            function showNewInternship() {
                document.getElementById("modal").style.display = "block";
            }
            function showAppliedInternship() {
                document.getElementById("modal-interns").style.display = "block";
            }
            function close() {
                console.log("hello?");
                document.getElementById("modal-interns").style.display = "none";
                document.getElementById("modal").style.display = "none";
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
                <a class="navbar-brand" href="#"> <img src="src/img/logo_2.png" style="width: auto; height: 100%;" > </a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                
                <?php if( isset($_SESSION['type'])): ?>
                    <?php if( $_SESSION['type'] == "student" ): ?>
                        <ul class="nav navbar-nav navbar-right">
                            <li><a class="nav-a" href="./src/pages/login.php"> Hello Student! </a></li>
                            <li><a class="nav-a" href="./src/pages/logout.php"> Logout </a></li>
                        </ul>
                    <?php elseif( $_SESSION['type'] == "emp" ) : ?>
                        <ul class="nav navbar-nav navbar-right">
                            <li><a class="nav-a" href="#">Hi Employer!</a></li>
                            <li><a class="nav-a" href="./src/pages/logout.php"> Logout </a></li>
                        </ul>
                    <?php endif;?>
                <?php else: ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a class="nav-a" href="./src/pages/login.php">Login</a></li>
                        <li><a class="nav-a" href="./src/pages/signup.php">Sign Up</a></li>
                    </ul>
                <?php endif; ?>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>    

        <div class="container col-md-12">
            
            <?php if( isset($_SESSION['type'])): ?>
                <?php if( $_SESSION['type'] == "emp" ) : ?>
                    <div onclick="showNewInternship()" class="col-md-3 text-center" style="background: #0981BF; color:white; padding: 1%; cursor:pointer;" > Make a new internship </div>
                    <div onclick="showAppliedInternship()" class="col-md-3 col-md-offset-6 text-center" style="background: #0981BF; color:white; padding: 1%;cursor:pointer;" > Show Applications </div>
                    <br><br><br>
                    

                <?php endif; ?>
            <?php endif; ?>
            
            <?php if( isset($internships)): 
                foreach($internships as $x => $value) {
                    echo "<div class='col-md-12 internship' >";
                        echo "<img src='./src/img/logo_2.png' class='col-md-2' >";
                        echo "<div class='col-md-8' >";
                            echo $value;
                        echo "</div>";
                        if( isset($_SESSION['type']) ) {
                            if( $_SESSION['type']=='emp' ) {
                                echo "<div class='col-md-2' >";
                                    echo "<a href='#' onclick='alert(`You are employer`)' >Apply</a>";
                                echo "</div>";    
                            }
                            else {
                                echo "<div class='col-md-2' >";
                                    echo "<a href='./src/pages/internship_desc.php?title=".$value." '>Apply</a>";
                                echo "</div>";
                            }
                        }
                        else {
                            echo "<div class='col-md-2' >";
                                echo "<a href='./src/pages/login.php'>Apply</a>";
                            echo "</div>";
                        }
                    echo "</div>";
                }

            ?>
                
            <?php endif;?>

        </div>

        <div class="modal-form " id="modal" onclick="close()" >
            <form action = "" method = "post" class="text-center internship-form" style="z-index:99" > 
                    <div class="text-right" style="z-index: 100" > X &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </div>
                    <h3> A New Internship! </h3>
                    <label>Title  </label><br /><input type = "text" name = "username" /><br /><br />
                    <label>Description  </label><br /><input type = "text" name = "email" /><br /><br />
                    <label>Date Valid  </label><br /><input type = "text" name = "full_name" /><br /><br />                        
                <input type = "submit" value = " Submit! "/><br />                    
            </form>
        </div>

        <div class="modal-form " id="modal-interns" onclick="close()" >
            <div class="internship-form text-center" style="width:70%; margin-left: 15%; z-index:99; padding: 2.5% 0;" > 
                <div class="text-right" style="cursor:pointer; z-index:100" onclick="close()" > X&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </div>
                <?php

                    $emp_username = $_SESSION['login_user'];
                    $sql = "SELECT emp_id FROM employer WHERE emp_username = '$emp_username'";
                    $result = mysqli_query($db,$sql);
                    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);                    
                    $emp_id = $row['emp_id'];

                    $sql = "SELECT internships_applied.internship_id, stud_id,linkedin_link,description from internships_applied, ( SELECT internship_id FROM internships WHERE emp_id = '$emp_id' ) AS T WHERE T.internship_id = internships_applied.internship_id";
                    $internships = array();
                    $result = mysqli_query($db,$sql);
                    // print_r($result);
                    $rows = mysqli_num_rows($result);
                    echo "<h2>Your Applications:</h2>";
                    echo "<table style='width: 100%' class='text-center' >";                            
                            echo "<thead> <td>Internship</td><td>Student</td><td>Linked In</td><td>Application</td> </thead> ";
                                echo "<tbody>";
                    for ( $x = 0; $x < $rows; $x++ ) {
                        echo "<tr>";
                        
                        $row = mysqli_fetch_array($result,MYSQLI_NUM);                        
                        $int_id = $row[0];

                        $sql_for_title = "SELECT title FROM internships WHERE internship_id='$int_id' ";
                        $result_for_title = mysqli_query($db,$sql_for_title);

                        $row_for_title = mysqli_fetch_array($result_for_title,MYSQLI_ASSOC);
                            echo "<td >";
                                echo($row_for_title['title']);
                            echo "</td>";    
                        
                        $stud_id = $row[1];
                        // print_r($stud_id);
                        $sql_for_title = "SELECT full_name FROM student WHERE stud_id='$stud_id' ";
                        $result_for_title = mysqli_query($db,$sql_for_title);
                        
                        $row_for_title = mysqli_fetch_assoc($result_for_title);
                            echo "<td>";
                                echo($row_for_title['full_name']);
                            echo "</td>";    
                        
                        echo "<td>";
                            echo($row[2]);
                        echo "</td>";    
                    
                        echo "<td>";
                            echo($row[3]);
                        echo "</td>";    
                            

                        echo "</tr>";
                    }
                    
                        echo "</tbody>";
                    echo "</table>";
                    // print_r($internships);
                    // foreach($internships as $x => $value) {
                    //     echo "<div class='col-md-12' >";
                            
                    //         echo "<div class='col-md-8' >";
                    //             echo $value;
                    //         echo "</div>";

                    //     echo "</div>";
                    // }
                ?>
            </div>
        </div>

    </body>
</html>