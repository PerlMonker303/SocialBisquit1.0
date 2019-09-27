<?php
//script for loading profile image

    session_start();
    
    require 'dbh.php';

    if(isset($_SESSION['userId'])){
        $sql_sel = "SELECT nameProfilePic FROM tbl_information WHERE idUser = ?";
        $stmt_sel = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt_sel, $sql_sel)){
            header("Location: ../main.php?error=not_set");
            exit();
        }else{
            $localId = $_SESSION['userId'];
            mysqli_stmt_bind_param($stmt_sel, 'i', $localId);
            mysqli_stmt_execute($stmt_sel);
            $res_sel = mysqli_stmt_get_result($stmt_sel);
            $row_sel = mysqli_fetch_assoc($res_sel);
            $picPath = $row_sel['nameProfilePic'];
            if($picPath == NULL || $picPath == "empty")
                $picPath = 'https://res.cloudinary.com/hgfmqcnjc/image/upload/v1567858286/defaultImage_t3bfth.jpg'; 
            echo '<img src="'.$picPath.'">';
        }
    }else{
        header("Location: ../main.php?error=not_set");
        exit();
    }
?>