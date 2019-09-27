<?php
    session_start();
    
    require 'dbh.php';

    if(isset($_SESSION['userId'])){

        $loggedProfile = false;

        
        if(htmlentities($_POST['_userId']) == 0 || htmlentities($_POST['_userId']) == $_SESSION['userId']){
            $loggedProfile = true;
            $idUser = $_SESSION['userId'];
        }else{
            $idUser = htmlentities($_POST['_userId']);
        }
        
        $profileImgPath = "https://res.cloudinary.com/hgfmqcnjc/image/upload/v1567858286/defaultImage_t3bfth.jpg";
        $firstName = "";
        $lastName = "";
        
        $sql1 = "SELECT * FROM tbl_information WHERE idUser = ?";
        $stmt1 = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt1, $sql1)){
            header("Location: ../main.php?error=sqlerror");
            exit();
        }else{
            mysqli_stmt_bind_param($stmt1, 'i', $idUser);
            mysqli_stmt_execute($stmt1);
            $result1 = mysqli_stmt_get_result($stmt1);
            $row1 = mysqli_fetch_assoc($result1);
            if($row1['nameProfilePic'] && $row1['nameProfilePic'] != "empty")
              $profileImgPath = $row1['nameProfilePic'];
            $bio = $row1['bio'];
        }

        $sql2 = "SELECT * FROM tbl_users WHERE idUser = ?";
        $stmt2 = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt2, $sql2)){
            header("Location: ../main.php?error=sqlerror");
            exit();
        }else{
            mysqli_stmt_bind_param($stmt2, 'i', $idUser);
            mysqli_stmt_execute($stmt2);
            $result2 = mysqli_stmt_get_result($stmt2);
            $row2 = mysqli_fetch_assoc($result2);
            $firstName = $row2['fName'];
            $lastName = $row2['lName'];
        }


        $currentStep = 0;
        if($loggedProfile == true){
            //checking if profile is set, if it's in the process or it's unset totally
            $sql3 = "SELECT * FROM tbl_information WHERE idUser = ?";
            $stmt3 = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt3, $sql3)){
                header("Location: ../main.php?error=sqlerror");
                exit();
            }else{
                mysqli_stmt_bind_param($stmt3, 'i', $idUser);
                mysqli_stmt_execute($stmt3);
                $result3 = mysqli_stmt_get_result($stmt3);
                $row3 = mysqli_fetch_assoc($result3);
                if($row3['idUser']){
                    if($row3['nameProfilePic'] == "https://res.cloudinary.com/hgfmqcnjc/image/upload/v1567858286/defaultImage_t3bfth.jpg"){
                        $currentStep = 2;
                    }else if($row3['originated'] == "empty"){
                        $currentStep = 3;
                    }else if($row3['gender'] == "2"){
                        $currentStep = 4;
                    }else if($row3['bio'] == "empty"){
                        $currentStep = 5;
                    }else{
                        $currentStep = 6;
                    }
                }else{
                    $currentStep = 1;
                }
            }
        }
        echo '
            <div id="container-profile-page">
            ';
            if($loggedProfile == true){
                echo '
                    <div id="container-profile-header">
                    <h2>Welcome to your profile</h2>
                    </div>
                ';
            }
                if($loggedProfile == true){
                    if($currentStep == 0){
                        //unset
                        echo '
                        <div id="container-profile-unset">
                            <span>You have not set up your profile yet</span>
                        </div>
                        <button name="btnSetupProfile">Start now</button>
                        ';
                    }else if($currentStep >= 6){
                        //set
                    }else{
                        //in the process
                        echo '
                        <div id="container-profile-unset">
                            <span>Finish setting up your profile - '.$currentStep.'/5</span>
                        </div>
                        <button name="btnSetupProfile">Continue</button>
                        ';
                    }
                }
                
                echo '
                <div id="container-profile-information">
                    <img src="'.$profileImgPath.'"></img>
                    <span name="_nameInfo">'.$firstName.' '.$lastName.'</span>
                    <span style="display: none;">'.$idUser.'</span>
                    <span name="_bioInfo">'.$bio.'</span>
                    ';
                if($loggedProfile == true){
                    echo '
                     <button name="_logoutBtn"><img src="Icons/logoutMatWhite.png"></button>
                    ';
                }else{
                    //check friendship
                    $sqlfr = "SELECT * FROM tbl_contacts WHERE idUser = ? && idFriend = ?";
                    $stmtfr = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmtfr, $sqlfr)){
                        header("Location: ../main.php?error=sqlerror");
                        exit();
                    }else{
                        $lid = $_SESSION['userId'];
                        mysqli_stmt_bind_param($stmtfr, 'ii', $lid, $idUser);
                        mysqli_stmt_execute($stmtfr);
                        $resfr = mysqli_stmt_get_result($stmtfr);
                        $rowfr = mysqli_fetch_assoc($resfr);
                        if($rowfr['idUser']){
                            //they are friends
                            echo '
                                <button name="_addFriendBtn"><img src="Icons/deletePersonMatWhite.png"></button>
                            ';
                        }else{
                            //they are not friends
                            //check frequests
                            $sqlfq = "SELECT * FROM tbl_frequests WHERE idUser = ? && idFriend = ?";
                            $stmtfq = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmtfq, $sqlfq)){
                                header("Location: ../main.php?error=sqlerror");
                                exit();
                            }else{
                                $lid = $_SESSION['userId'];
                                mysqli_stmt_bind_param($stmtfq, 'ii', $lid, $idUser);
                                mysqli_stmt_execute($stmtfq);
                                $resfq = mysqli_stmt_get_result($stmtfq);
                                $rowfq = mysqli_fetch_assoc($resfq);
                                if($rowfq['idUser']){
                                    //frequest sent
                                    echo '
                                     <button name="_addFriendBtn"><img src="Icons/swapMatWhite.png"></button>
                                    ';
                                }else{
                                    //not friends
                                    echo '
                                     <button name="_addFriendBtn"><img src="Icons/addPersonMatWhite.png"></button>
                                    ';
                                }
                            }
                        }
                    }
                    
                }
                echo '
                </div>

                <div id="container-profile-options">
                    <img src="Icons/fillingMatWhite.png" name="_profileMenuButton1">
                    <img src="Icons/shareMatWhite.png" name="_profileMenuButton2">
                    <img src="Icons/peopleMatWhite.png" name="_profileMenuButton3">
                    <img src="Icons/textMatWhite.png" name="_profileMenuButton4">
                    <div id="container-profile-options-bottom">
                </div>
                
                    
                </div>

                <div id="container-profile-content">
                    
                </div>

                
                
            </div>
        ';
        //<span name="_bioInfo">I am not really that charismatic, but when I am, you know it.</span>
    }else{
        header("Location: ../main.php?error=not_set");
        exit();
    }
?>