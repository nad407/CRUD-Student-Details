<?php
/* Adding new student details into the database based on user input */
include_once 'Edit.php';

if(isset($_POST['ConfirmAdd'])){
    $id=$_POST['id'];
    
    $pdo= Database::connect();
    
    //check if student ID already exists
    $sql="SELECT * FROM Students WHERE id LIKE :id";
    $query=$pdo->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_STR);
    $query->execute();
    $matchingID = $query->rowCount();
    
    /*if a match is found inside the database, an error message is prepared, connection
    with the database is terminated and 'Add' process is stopped.*/
    if($matchingID>0){
        $_SESSION['ErrorMsg']="Student ID already exists";
        Database::disconnect();
    }else{
        $name=$_POST['name'];
        $surname=$_POST['surname'];
        $dob=$_POST['dob'];
         
        /*Date validation using the function ValidateDate()
         * If date is invalid, an error message is prepared, connection with
         * database is terminated and 'Add' process is stopped.*/
        $validDate = validateDate($dob,'Y-m-d');
        if(!$validDate){
            $_SESSION['ErrorMsg']="Invalid date format. Date format is YYYY-MM-DD.";
            Database::disconnect();
        }else{
            $address=$_POST['address'];
            $town=$_POST['town'];
            $level=$_POST['level'];
            $class=$_POST['class'];


            $sql="INSERT INTO Students(id, name, surname, dob, address, town, level, class) "
                    . "VALUES(:id,:name, :surname, :dob, :address, :town, :level, :class)";
            $query=$pdo->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_STR);
            $query -> bindParam(':name', $name, PDO::PARAM_STR);
            $query -> bindParam(':surname', $surname, PDO::PARAM_STR);
            $query -> bindParam(':dob', $dob, PDO::PARAM_STR);
            $query -> bindParam(':address', $address, PDO::PARAM_STR);
            $query -> bindParam(':town', $town, PDO::PARAM_STR);
            $query -> bindParam(':level', $level, PDO::PARAM_STR);
            $query -> bindParam(':class', $class, PDO::PARAM_STR);
            $query->execute();
            Database::disconnect();
        }
    }   
}

