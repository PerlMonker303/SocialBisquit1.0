<?php
    session_start();
    
    require 'dbh.php';
    //1-secret question
    //2 profile picture
    //3 information
    //4 gender/lf - optional
    //5 bio - optional
    //6 you're all set

    if(isset($_SESSION['userId'])){

        $idUser = $_SESSION['userId'];
        $currentStep = 0;

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
            if($row1['idUser']){
                if($row1['nameProfilePic'] == "https://res.cloudinary.com/hgfmqcnjc/image/upload/v1567858286/defaultImage_t3bfth.jpg"){
                    $currentStep = 2;
                }else if($row1['originated'] == "empty"){
                    $currentStep = 3;
                }else if($row1['gender'] == "2"){
                    $currentStep = 4;
                }else if($row1['bio'] == "empty"){
                    $currentStep = 5;
                }else{
                    $currentStep = 6;
                }
            }else{
                $currentStep = 1;
            }
              
        }
        if($currentStep != 6){
            echo '
            <div id="setup-container-header">
                <h2>Setup your profile in order to have full access to the platform</h2>
            </div>
            
            <div id="setup-container-steps">
                <div id="progress-bar-bg">
                    <img id="progress-bar-loaded'.$currentStep.'">
                </div>
                <h3 name="setupsCounter">Step '.$currentStep.'/5</h3>
            </div>';
        }
        if($currentStep == 1){
            echo '

        <div id="setup-container-main">
            <h2>Secure your account</h2>
            <p>Our platform values each individual\'s privacy,
            therefore all we ask of you is to have a sense of security while
            browsing our platform</p>
            <span>Choose a secret question:</span>
            <select name="_secretSelect">';
            $sql_s1 = "SELECT * FROM tbl_questions";
            $stmt_s1 = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt_s1, $sql_s1)){
                header("Location: ../main.php?error=sqlerror");
                exit();
            }else{
                mysqli_stmt_execute($stmt_s1);
                $res_s1 = mysqli_stmt_get_result($stmt_s1);
                while($row_s1 = mysqli_fetch_assoc($res_s1)){
                    echo '<option>'.$row_s1['question'].'</option>';
                }
            }
            echo '
            </select></br>
            <span>Your answer: </span>
            <input type="password" name="_secretAnswer">
            <button name="secretAnswerSwitch">Show</button>
            <div name="_secretAnswerError"></div>
            <button id="nextButton1" class="nextButton">Next</button>
        </div>
        
        ';
        }else if($currentStep == 2){
            echo '
            <div id="setup-container-main">
                <h2>Upload a profile picture</h2>
                <p>Let everybody know how cool and unique you are by uploading a personal profile picture</p>
                </br>
                <span>Restrictions: maximum size of 50kb</span>
                <input type="file" name="_setupPicLoaded">
                <div name="_secretAnswerError"></div>
                <button id="nextButton2" class="nextButton">Next</button>
            </div>
            ';
        }else if($currentStep == 3){
            echo '
            <div id="setup-container-main">
                <h2>Information is key</h2>
                <p>Other users may find an interest in looking over your information so why not allow them this pleasure?</p>
                </br>
                <span>Where are you from?</span>
                <input type="text" placeholder="ex: Brasov, Romania" name="_step3Input1">
                <span>Where are you currently residing?</span>
                <input type="text" placeholder="ex: Cluj-Napoca, Romania" name="_step3Input2">
                <span>What is your current employment position?</span>
                <input type="text" placeholder="ex: Entry-level web delevoper" name="_step3Input3">
                <div name="_secretAnswerError"></div>
                <button id="nextButton3" class="nextButton">Next</button>
            </div>
            ';
        }else if($currentStep == 4){
            echo '
            <div id="setup-container-main">
                <h2>Be a free spirit</h2>
                <p>We know, gender is not important, but it is key to let people know what your game is about.</p>
                <span>I am:</span>
                <select name="_genderSelectIs">
                    <option disabled selected value>-- select an option --</option>
                    <option value="is_male">Male</option>
                    <option value="is_fem">Female</option>
                </select>
                <span>I am looking for:</span>
                <select name="_genderSelectLf">
                    <option disabled selected value>-- select an option --</option>
                    <option value="lf_male">Male</option>
                    <option value="lf_fem">Female</option>
                </select>
                <div name="_secretAnswerError"></div>
                <button id="nextButton4" class="nextButton">Next</button>
            </div>
            ';
        }else if($currentStep == 5){
            echo '
            <div id="setup-container-main">
                <h2>Describe yourself</h2>
                <p>Add a description of yourself that people will remember. If you feel like Stephen King, try and shorten it up
                as you only have 500 characters available.</p>
                <span>Remember: be true to yourself before being true to others</span>
                <textarea name="_textareaBio" placeholder="ex: Sometimes you lose, sometimes you don\'t win"></textarea>
                <div name="_secretAnswerError"></div>
                <button id="nextButton5" class="nextButton">Next</button>
            </div>
            ';
        }else if($currentStep == 6){
            echo '
            <div id="setup-container-main">
                <h2>You are all set</h2>
                <p>Go on now, enjoy the platform, socialise and don\'t let it slip your mind: have fun!</p>
            </div>
            ';
        }
        
    }else{
        header("Location: ../main.php?error=not_set");
        exit();
    }
?>