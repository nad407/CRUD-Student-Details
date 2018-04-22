<?php
require 'Database.php';

function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

//reading all the data from the database about the student that has been selected for editing
if(!isset($_POST['ConfirmEdit'])&&(isset($_GET['edit']))&&!(isset($_POST['Add']))){
    $idCurrent=$_GET['edit']; //student ID of the selected record for editing
    $pdo=Database::connect();
    $sql="SELECT * FROM Students WHERE id LIKE :id";
    $query = $pdo->prepare($sql);
    $query -> bindParam(':id', $idCurrent, PDO::PARAM_STR);
    $query->execute();
    $resultsToEdit = $query->fetch(PDO::FETCH_ASSOC);
    Database::disconnect();
    
}

//fetching data from the Edit form containg the updated student details
if(isset($_POST['ConfirmEdit'])){
    $idNew=$_POST['id']; //student ID in 'Edit' form
    $pdo= Database::connect();
    $matchingID=0;
    
    /*if the student's ID in the 'Edit' form has been updated check if the new 
     * ID already exists */
    if($idNew!==$_GET['edit']){
        $sql="SELECT * FROM Students WHERE id LIKE :id";
        $query=$pdo->prepare($sql);
        $query->bindParam(':id', $idNew, PDO::PARAM_STR);
        $query->execute();
        $matchingID = $query->rowCount();
    }
    
    
     /* If student ID already exists, error message is prepare, connection with
      * database is terminated and editing process is stopped.
      * 
      * If student ID does not exist, update the database with the new student details
      */
    if($matchingID>0){
        $_SESSION['ErrorMsg']="Student ID already exists";
        Database::disconnect();
    }else{
        $name=$_POST['name'];
        $surname=$_POST['surname'];
        $dob=$_POST['dob'];
        
       /*date validation - if date is not valid, error message is prepare, connection with
        * database is terminated and editing process is stopped. */
        $validDate = validateDate($dob,'Y-m-d');
        if(!$validDate){
            $_SESSION['ErrorMsg']="Invalid date format. Date format is YYYY-MM-DD.";
            Database::disconnect();
        }else{
            $address=$_POST['address'];
            $town=$_POST['town'];
            $level=$_POST['level'];
            $class=$_POST['class'];

            $sql="UPDATE Students SET id=:idNew,name=:name,surname=:surname, dob=:dob, address=:address, town=:town, level=:level, class=:class WHERE id LIKE :selectId";
            $updateStudent = $pdo->prepare($sql);

            $updateStudent -> bindParam(':idNew', $idNew, PDO::PARAM_STR);
            $updateStudent -> bindParam(':name', $name, PDO::PARAM_STR);
            $updateStudent -> bindParam(':surname', $surname, PDO::PARAM_STR);
            $updateStudent -> bindParam(':dob', $dob, PDO::PARAM_STR);
            $updateStudent -> bindParam(':address', $address, PDO::PARAM_STR);
            $updateStudent -> bindParam(':town', $town, PDO::PARAM_STR);
            $updateStudent -> bindParam(':level', $level, PDO::PARAM_STR);
            $updateStudent -> bindParam(':class', $class, PDO::PARAM_STR);         
            $updateStudent -> bindParam(':selectId', $_GET['edit'], PDO::PARAM_STR);
            $updateStudent -> execute(); 
            Database::disconnect();
            $_SESSION['EditComplete'] = true;
            unset($resultsToEdit);
        }
    }
}

?>
     