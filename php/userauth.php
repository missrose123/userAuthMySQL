
<?php

require_once "../config.php";

//register users
function registerUser($fullnames, $email, $password, $gender, $country){
    //create a connection variable using the db function in config.php
    $conn = db();
    //check if user with this email already exist in the database
   $sql = "SELECT email FROM students WHERE email= '$email' ";
   $result = $conn->query($sql);
   if ($result->num_rows===0) {
       $sql = "INSERT INTO students (full_names,country, email, gender, password ) VALUE('$fullnames', '$country', '$email', '$gender', '$password')";
       $result = $conn->query($sql);
       if ($result) {
           echo "User Successfully registered";
        } else {
            echo "<script>alert('Registration Unsuccesful')</script>";
        }
        
    }else {
       echo 'User already exist';
   }
   $conn->close();
}


//login users
function loginUser($email, $password){
    //create a connection variable using the db function in config.php
    $conn = db();

    echo "<h1 style='color: red'> LOG ME IN (IMPLEMENT ME) </h1>";
    //open connection to the database and check if username exist in the database
    $sql = "SELECT * FROM students WHERE email='$email' ";
    $result = $conn->query($sql);
    if ($result->num_rows>0) {
        //if it does, check if the password is the same with what is given
        $result = $result->fetch_assoc();
        if ($result['password']===$password) {
            //if true then set user session for the user and redirect to the dasbboard
            // print_r($result);
            session_start();
            $_SESSION['username'] = $result['full_names'];
            header('Location:../dashboard.php');
            exit;
        } else {
            header('Location:../forms/login.html');
            exit;
        }
        
    }
    $conn->close();
}

function resetPassword($email, $password){
    //create a connection variable using the db function in config.php
    $conn = db();
    // echo "<h1 style='color: red'>RESET YOUR PASSWORD (IMPLEMENT ME)</h1>";
    //open connection to the database and check if username exist in the database
    $sql = "SELECT * FROM students WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result)>0) {
        //if it does, replace the password with $password given
        $sql= "UPDATE students SET password = '$password' WHERE email = '$email' ";
        if (mysqli_query($conn, $sql)) {
            echo "Password reset successfully";
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    } else {
        print_r('User does not exist');
    }
    mysqli_close($conn);
    
}

function getusers(){
    $conn = db();
    $sql = "SELECT * FROM Students";
    $result = mysqli_query($conn, $sql);
    echo"<html>
    <head></head>
    <body>
    <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
    <table border='1' style='width: 700px; background-color: magenta; border-style: none'; >
    <tr style='height: 40px'><th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th></tr>";
    if(mysqli_num_rows($result) > 0){
        while($data = mysqli_fetch_assoc($result)){
            //show data
            ###fixed form issue of always deleting last id, by correctly writing the html syntax and placing the form inside the td, and writing closing tags ommited
            echo "<tr style='height: 30px'>".
                "<td style='width: 50px; background: blue'>" . $data['id'] . "</td>
                <td style='width: 150px'>" . $data['full_names'] .
                "</td> <td style='width: 150px'>" . $data['email'] .
                "</td> <td style='width: 150px'>" . $data['gender'] . 
                "</td> <td style='width: 150px'>" . $data['country'] . 
                "</td>
                <td style='width: 150px'><form action='action.php' method='post' >
                <input type='hidden' name='id'" .
                 "value=" . $data['id'] . ">".
                " <button type='submit', name='delete'> DELETE </button></form></td>".
                "</tr>";
        }
        echo "</table></table></center></body></html>";
    }
    //return users from the database
    //loop through the users and display them on a table
}

function deleteaccount($id){
    $conn = db();
    //delete user with the given id from the database
    $sql = "DELETE FROM students WHERE id='$id';";
    if (mysqli_query($conn, $sql)) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
