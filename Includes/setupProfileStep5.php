<?php
    session_start();
    
    require 'dbh.php';

    if(isset($_SESSION['userId'])){

        $input1 = htmlentities($_POST['_input1']);
        $userId = $_SESSION['userId'];

        $sql1 = "UPDATE tbl_information SET bio=? WHERE idUser=?";
        $stmt1 = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt1, $sql1)){
            header("Location: ../main.php?error=sqlerror");
            exit();
        }else{
            mysqli_stmt_bind_param($stmt1, 'si', $input1, $userId);
            mysqli_stmt_execute($stmt1);
        }
        
    }else{
        header("Location: ../main.php?error=not_set");
        exit();
    }
?>