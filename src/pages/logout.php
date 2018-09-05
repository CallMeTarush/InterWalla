<?php
    session_start();
    if( isset($_SESSION['type'])): 
        unset($_SESSION['type']);
    endif;
    if( isset( $_SESSION['login_user'] ) ):
        unset($_SESSION['login_user']);
    endif;
    header('Location: ../../index.php');
?>