<?php

/* Delete student details after user has confirmed the action.*/
if(isset($_POST['DeleteConfirmed'])){
    if(isset($_GET['delete'])){
        $id=$_GET['delete'];
        $pdo= Database::connect();
        $sql="DELETE FROM Students WHERE id LIKE :id";
        $query=$pdo->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();
        Database::disconnect();
    }
}


