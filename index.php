<?php
session_start();

include 'Edit.php';
include 'Delete.php';
include 'Add.php';

//Reading all student details from the database
$pdo=Database::connect();
$sql="SELECT * FROM Students";
$studentDetails = $pdo->prepare($sql);
$studentDetails->execute();
Database::disconnect();
   
   
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
        
        <link rel='stylesheet' href='MyStyle.css'>
        <title> Student Details </title>
    </head>
    <body>
        <!--Displaying success, error and warning alerts-->
        <div class ="row" style="min-height: 77px">
            <nav class="navbar static-top" style="max-width: 1200px;">
            <?php if(isset($_SESSION['EditComplete'])){?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Student has been edited.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php unset($_SESSION['EditComplete']);?>
                </div>
            <?php }elseif((isset($_GET['delete']))&&(!isset($_POST['Add']))){?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <form method="POST" action="">
                    <strong>Warning</strong> Are you sure you want to delete this student?
                    <input type="submit" name="DeleteConfirmed" value="Yes"/>
                </form>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php }elseif(isset($_SESSION['ErrorMsg'])){?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> <?php echo $_SESSION['ErrorMsg']; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php unset($_SESSION['ErrorMsg']);?>
            </div>
            <?php } ?>
            </nav>
        </div>
        <div class="row">
            <div class="col-sm-7">
                <div class="col-sm-12">
                    <!--Table with student details-->
                    <form method="POST" action="">
                        <table class="table table-hover table-light">
                            <thead>
                            <h3 style="text-align: center">Student Details </h3>
                                <tr>
                                    <th> ID </th><th> Name </th><th> Surname </th>
                                    <th> DoB </th><th> Address </th><th> Town </th>
                                    <th> Level </th><th> Class </th><th> Actions </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($studentDetails as $student): ?>
                                <tr> 
                                    <?php $theID=$student['id'];?>
                                    <td><?php echo htmlspecialchars($student['id']); ?></td>
                                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['surname']); ?></td>
                                    <td><?php echo htmlspecialchars($student['dob']); ?></td>
                                    <td><?php echo htmlspecialchars($student['address']); ?></td>
                                    <td><?php echo htmlspecialchars($student['town']); ?></td>
                                    <td><?php echo htmlspecialchars($student['level']); ?></td>
                                    <td><?php echo htmlspecialchars($student['class']); ?></td>
                                    <td><a href="?edit=<?php echo $student['id'];?>" >Edit</a>
                                        <a href="?delete=<?php echo $student['id'];?>">Delete</a></td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                        <input type="submit" name="Add" value="Add New Student">
                    </form>
                </div>
            </div>
            <!--Add/Edit Form. Edit Form is prefilled with student details based on user's selection -->
            <div class="col-sm-5">
                <?php if (isset($resultsToEdit)||isset($_POST['Add'])){ ?>
                    <div class="card bg-light" style="max-width: 35rem">
                        <div class="card-body">
                            <?php if (isset($_POST['Add'])){?><h4 class="card-title">Add Student</h4> <?php } ?>
                            <?php if (isset($resultsToEdit)){?><h4 class="card-title">Edit Student Details</h4> <?php } ?>
                                <form method="POST" action="">
                                    <div class="form-group">
                                        <label for="id">ID Number</label>
                                        <input type="text"  class="form-control" name="id" value="<?php if(isset($resultsToEdit)) {echo htmlspecialchars($resultsToEdit['id']);}?>" required>
                                        <small id="IDHelp" class="form-text text-muted">E.g. 123456M </small>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="name" value="<?php if(isset($resultsToEdit)) {echo htmlspecialchars($resultsToEdit['name']);}?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="surname">Surname</label>
                                        <input type="text" class="form-control" name="surname" value="<?php if(isset($resultsToEdit)) {echo htmlspecialchars($resultsToEdit['surname']);}?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="dob">DoB</label>
                                        <input type="date" class="form-control" name="dob" value="<?php if(isset($resultsToEdit)) {echo htmlspecialchars($resultsToEdit['dob']);}?>" required>
                                        <small id="DoBHelp" class="form-text text-muted">Date Format: YYYY-MM-DD</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" class="form-control" name="address" value="<?php if(isset($resultsToEdit)) {echo htmlspecialchars($resultsToEdit['address']);}?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="town">Town</label>
                                        <input type="text" class="form-control" name="town" value="<?php if(isset($resultsToEdit)) {echo htmlspecialchars($resultsToEdit['town']);}?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="level">Level</label>
                                        <input type="text" class="form-control" name="level" value="<?php if(isset($resultsToEdit)) {echo htmlspecialchars($resultsToEdit['level']);}?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="class">Class</label>
                                        <input type="text" class="form-control" name="class" value="<?php if(isset($resultsToEdit)) {echo htmlspecialchars($resultsToEdit['class']);}?>" required>
                                    </div>
                                    <?php if (isset($resultsToEdit)){?>
                                        <input type="submit" name="ConfirmEdit" value="Confirm Edit"/>
                                    <?php } ?>
                                    <?php if (isset($_POST['Add'])){?>
                                        <input type="submit" name="ConfirmAdd" value="Add new student"/>
                                    <?php } ?>
                                
                            </form>  
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>