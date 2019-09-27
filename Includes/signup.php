<?php

require 'dbh.php';

$firstname = htmlentities($_POST['_regFname']);
$lastname = htmlentities($_POST['_regLname']);
$email = htmlentities($_POST['_regEmail']);
$password = htmlentities($_POST['_regPass']);
$repassword = htmlentities($_POST['_regRePass']);

echo $email;
echo $firstname;
echo $lastName;
echo $password;
echo $repassword;

if(empty($email)||empty($firstname)||empty($lastname)||empty($password)||empty($repassword)){
  header("Location: ../register.php?error=emptyfields?1=".$email."&2=".$firstname."&3=".$lastName."&4=".$password."&5=".$repassword);
  //echo "some empty";
  exit();
}else if(!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $lastname)){
  header("Location: ../register.php?error=invalidemailnames");
  //echo "not email and ugly characters";
  exit();
}else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
  header("Location: ../register.php?error=invalidemail");
  //echo "not email";
  exit();
}else if(!preg_match("/^[a-zA-Z0-9]*$/", $lastname)){
  header("Location: ../register.php?error=invalidlname");
  //echo "ugly lname characters";
  exit();
}else if($password !== $repassword){
  header("Location: ../register.php?error=passwordcheck");
  //echo "passwords dont match";
  exit();
}else if(strlen($password)<6){
  header("Location: ../register.php?error=passwordshort");
  //password too short`
  exit();
}else{
  $sql = "SELECT email FROM tbl_users WHERE email=?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../register.php?error=sqlerror");
    exit();
  }else{
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $resultCheck = mysqli_stmt_num_rows($stmt);
      if($resultCheck > 0){
        header("Location: ../register.php?error=emailtaken");
        exit();
      }else{
        //selecting last id
        $sql_sel1 = "SELECT idUser FROM tbl_users";
        $stmt_sel1 = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt_sel1, $sql_sel1)){
          header("Location: ../register.php?error=sqlerror");
          exit();
        }else{
          mysqli_stmt_execute($stmt_sel1);
          $result_sel1 = mysqli_stmt_get_result($stmt_sel1);
          $nextId = 0;
          while($row_sel1 = mysqli_fetch_assoc($result_sel1)){
            $nextId = $row_sel1['idUser'];
          }
          $nextId++;

          $sql = "INSERT INTO tbl_users(idUser, email,fName,lName,password,is_active,is_reported,is_blocked,dateOfReg,updatedAt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
          $stmt = mysqli_stmt_init($conn);
          if(!mysqli_stmt_prepare($stmt, $sql)){
            header("Location: ../register.php?error=sqlerror");
            exit();
          }else{
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $dateTimeNow = date('Y-m-d H:i:s');
            $true = 1;
            $false = 0;
            mysqli_stmt_bind_param($stmt, "issssiii", $nextId, $email, $firstname, $lastname, $hashedPassword, $false, $false, $false);
            mysqli_stmt_execute($stmt);
            header("Location: ../login.php?error=sgn_success");
            exit();
          }

        }

      }
  }
}
mysqli_stmt_close($stmt);
mysqli_close($conn);


?>
