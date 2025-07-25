<?php
include('includes/config.php'); 
if(isset($_POST['submit']))
{
    $ExistingUser = false;
    $file = $_FILES['image']['name'];
    $file_loc = $_FILES['image']['tmp_name'];
    $folder="images/"; 
    $new_file_name = strtolower($file);
    $final_file=str_replace(' ','-',$new_file_name); 

    $name=$_POST['name'];
    $email=$_POST['email'];
    $password=md5($_POST['password']);
    $gender=$_POST['gender'];
    $mobileno=$_POST['mobileno'];
    $designation=$_POST['designation'];
        if(isset($_POST['team']))
        {
        $team=$_POST['team'];
        }
        if(move_uploaded_file($file_loc,$folder.$final_file))
    	{
    		$image=$final_file;
        }
    $notitype='Create Account';
    $reciver='Admin';
    $sender=$email;

        $sqlCheck = "SELECT U.id,U.TeamID,U.name,U.email, U.designation, U.Status FROM users U ";
	    $sqlCheck .= "where U.email = :email AND U.ClassID = :classid ";
	    $queryCheck = $dbh->prepare($sqlCheck);
        $queryCheck-> bindParam(':email', $email, PDO::PARAM_STR);
	    $queryCheck-> bindParam(':classid', $ClassIDGlobal, PDO::PARAM_STR);
	    $queryCheck->execute();
	    $resultCheck=$queryCheck->fetch(PDO::FETCH_OBJ);
	    if($queryCheck->rowCount() > 0)
	    {
	    	$ExistingUser = true; 
	    }
        else
        {
            $ExistingUser = false;
        }

        if ($ExistingUser == false)
        {
            $sqlnoti="insert into notification (notiuser,notireciver,notitype,classid) values (:notiuser,:notireciver,:notitype,:classid)";
            $querynoti = $dbh->prepare($sqlnoti);
            $querynoti-> bindParam(':notiuser', $sender, PDO::PARAM_STR);
            $querynoti-> bindParam(':notireciver',$reciver, PDO::PARAM_STR);
            $querynoti-> bindParam(':notitype', $notitype, PDO::PARAM_STR);
            $querynoti-> bindParam(':classid', $ClassIDGlobal, PDO::PARAM_STR);
            $querynoti->execute();    

                $sql ="INSERT INTO users(name,email, password, gender, mobile, designation,";
                    if ($designation == "Student")
                    {
                        $sql .="TeamID,"; 
                    }
                $sql .="image,ClassID, status)"; 

                $sql .="VALUES(:name, :email, :password, :gender, :mobileno, :designation,";
                    if ($designation == "Student")
                    {
                    $sql .=":team,"; 
                    }
                $sql .=":image,:classid, 1)";

                $query= $dbh -> prepare($sql);
                $query-> bindParam(':name', $name, PDO::PARAM_STR);
                $query-> bindParam(':email', $email, PDO::PARAM_STR);
                $query-> bindParam(':password', $password, PDO::PARAM_STR);
                $query-> bindParam(':gender', $gender, PDO::PARAM_STR);
                $query-> bindParam(':mobileno', $mobileno, PDO::PARAM_STR);
                $query-> bindParam(':designation', $designation, PDO::PARAM_STR); 
                if ($designation == "Student")
                {
                $query-> bindParam(':team', $team, PDO::PARAM_STR);
                }
                $query-> bindParam(':classid', $ClassIDGlobal, PDO::PARAM_STR);
                $query-> bindParam(':image', $image, PDO::PARAM_STR);
                $query->execute();
                $lastInsertId = $dbh->lastInsertId();
                if($lastInsertId)
                {
                    	//mail.goatcrist.us
                $to = $email;
                $subject = "You have a message from Shark Tank";
                $txt = "Congratulation you have registered with Lincoln Zebra Shark Tank http://goatcrist.us";
                $headers = "From: " . $AdminEmail . " \r\n" . "CC: ". $AdminCC . " \r\n" . "BCC: ". $AdminBCC ;
                mail($to,$subject,$txt,$headers);

                echo "<script type='text/javascript'>alert('Registration Sucessfull!');</script>";
                echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
                }
                else 
                {
                $error="Something went wrong. Please try again";
                }
        }
        else
        {
            echo "<script type='text/javascript'>alert('There is already a user with that email!');</script>";
            //  echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
        }

}



?>

<!doctype html>
<html lang="en" class="no-js">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
    <script type="text/javascript">

	function validate()
        {
            var extensions = new Array("jpg","jpeg","png");
            var image_file = document.regform.image.value;
            var image_length = document.regform.image.value.length;
            var pos = image_file.lastIndexOf('.') + 1;
            var ext = image_file.substring(pos, image_length);
            var final_ext = ext.toLowerCase();
            for (i = 0; i < extensions.length; i++)
            {
                if(extensions[i] == final_ext)
                {
                return true;
                
                }
            }
            alert("Image Extension Not Valid (Use Jpg,jpeg)");
            return false;
        }
        
</script>
</head>

<body>
	<div class="login-page bk-img">
		<div class="form-content">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h1 class="text-center text-bold mt-2x">Register</h1>
                        <div class="hr-dashed"></div>
						<div class="well row pt-2x pb-3x bk-light text-center">
                         <form method="post" class="form-horizontal" enctype="multipart/form-data" name="regform" onSubmit="return validate();">
                            <div class="form-group">
                            <label class="col-sm-1 control-label">Name<span style="color:red">*</span></label>
                            <div class="col-sm-5">
                            <input type="text" name="name" class="form-control" required>
                            </div>
                            <label class="col-sm-1 control-label">Email<span style="color:red">*</span></label>
                            <div class="col-sm-5">
                            <input type="text" name="email" class="form-control" required>
                            </div>
                            </div>

                            <div class="form-group">
                            <label class="col-sm-1 control-label">Password<span style="color:red">*</span></label>
                            <div class="col-sm-5">
                            <input type="password" name="password" class="form-control" id="password" required >
                            </div>

                            <label class="col-sm-1 control-label">Designation<span style="color:red">*</span></label>
                            <div class="col-sm-5">
                            <select name="designation" id="designation" class="form-control" required>
                            <option value="">Select</option>
                            <option value="Student">Student</option>
                            <option value="Shark">Sharp (under construction)</option>
                            </select>
                            </div>
                            </div>

                             <div class="form-group">
                            <label class="col-sm-1 control-label">Gender<span style="color:red">*</span></label>
                            <div class="col-sm-5">
                            <select name="gender" class="form-control" required>
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            </select>
                            </div>

                            <label class="col-sm-1 control-label">Phone<span style="color:red">*</span></label>
                            <div class="col-sm-5">
                            <input type="number" name="mobileno" class="form-control" required>
                            </div>
                            </div>

                             <div class="form-group">
                            <label class="col-sm-1 control-label">Avtar<span style="color:red">*</span></label>
                            <div class="col-sm-5">
                            <div><input type="file" name="image" class="form-control"></div>
                            </div>
                           
                            <label class="col-sm-1 control-label">Team<span style="color:red">*</span></label>
                            <div class="col-sm-5">
                            <div>
                                <select name="team" id="team" class="form-control" required>
                                <option value=''>SELECT</option>
                            <?php
                            $sqlTeam = "SELECT TeamID,TeamName FROM Team where Status = 1 AND ClassID = (:classidglobal)";
                            $queryTeam= $dbh -> prepare($sqlTeam);
                            $queryTeam-> bindParam(':classidglobal', $ClassIDGlobal, PDO::PARAM_STR);
                            $queryTeam->execute();
                            $results=$queryTeam->fetchAll(PDO::FETCH_OBJ);
                            foreach($results as $result)    
                            {
                                ?>
                              
                            <option value='<?php echo htmlentities($result->TeamID);?>' > <?php echo htmlentities($result->TeamName);?> </option>
                            <?php
                            }
                            ?>
                            </select>
                            </div>
                            </div>
                            </div>

								<br>
                                <button class="btn btn-primary" name="submit" type="submit">Register</button>
                                </form>
                                <br>
                                <br>
								<p>Already Have Account? <a href="index.php" >Signin</a></p>
							</div>
						</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Loading Scripts -->
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>
<script type="text/javascript">
 $("#designation").change(function () {
        var desg = $("#designation").val();
        //alert("DESIGNATION = "+ desg);
        //window.open("http://localhost:8080/dashboard/PDOWork.php?TeamID=" + teamid);
        //location.href = "ledger.php?TeamID=" + teamid;
        if (desg == "Shark") {
            $('#team').attr('disabled', true);
        } else {
            $('#team').attr('disabled', false);
        }
        });
$(document).ready(function () {   
    
});
</script>
</body>
</html>