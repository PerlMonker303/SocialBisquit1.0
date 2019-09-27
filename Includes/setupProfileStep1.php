<?php
    session_start();
    
    require 'dbh.php';

    if(isset($_SESSION['userId'])){

        $secretQuestion = htmlentities($_POST['_secQ']);
        $secretAnswer = htmlentities($_POST['_secAns']);

        $idUser = $_SESSION['userId'];

        $sql0 = "SELECT idQuestion FROM tbl_questions WHERE question = ?";
        $stmt0 = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt0, $sql0)){
            header("Location: ../main.php?error=sqlerror");
            exit();
        }else{
            mysqli_stmt_bind_param($stmt0, 'i', $secretQuestion);
            mysqli_stmt_execute($stmt0);
            $res0 = mysqli_stmt_get_result($stmt0);
            $row0 = mysqli_fetch_assoc($res0);
            $idSecretQuestion = $row0['idQuestion'];
        }

        $hashedPassword = password_hash($secretAnswer, PASSWORD_DEFAULT);

        $sql1 = "INSERT INTO tbl_information (idUser, idSecretQuestion, secretAnswer, dateOfBirth, gender, lookingFor, originated, location, profession, bio, nameProfilePic) VALUES (?,?,?,NOW(),?,?,?,?,?,?,?)";
        $stmt1 = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt1, $sql1)){
            header("Location: ../main.php?error=sqlerror");
            exit();
        }else{
            $empty = "empty";
            $emptyI = 2;
            $path = "https://res.cloudinary.com/hgfmqcnjc/image/upload/v1567858286/defaultImage_t3bfth.jpg";
            mysqli_stmt_bind_param($stmt1, 'iisiisssss', $idUser, $idSecretQuestion, $hashedPassword, $emptyI, $emptyI, $empty, $empty, $empty, $empty, $path);
            mysqli_stmt_execute($stmt1);
        }
        
    }else{
        header("Location: ../main.php?error=not_set");
        exit();
    }
?>