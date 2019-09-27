<?php
    session_start();
    
    require 'dbh.php';

    if(isset($_SESSION['userId'])){

        $input1 = htmlentities($_POST['_input1']);
        $input2 = htmlentities($_POST['_input2']);
        $userId = $_SESSION['userId'];

        if($input1=='Male'){
            $input1 = 0;
        }else{
            $input1 = 1;
        }

        if($input2=='Male'){
            $input2 = 0;
        }else{
            $input2 = 1;
        }

        $sql1 = "UPDATE tbl_information SET gender=?, lookingFor=? WHERE idUser=?";
        $stmt1 = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt1, $sql1)){
            header("Location: ../main.php?error=sqlerror");
            exit();
        }else{
            mysqli_stmt_bind_param($stmt1, 'iii', $input1, $input2, $userId);
            mysqli_stmt_execute($stmt1);
        }
        
    }else{
        header("Location: ../main.php?error=not_set");
        exit();
    }
?>