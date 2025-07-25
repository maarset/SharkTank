<?php
session_start(); 
 
ini_set('display_errors', '1');
include('includes/config.php');
//if(strlen($_SESSION['alogin'])==0)
if(strlen($_SESSION['alogin'])==0 || $_SESSION['alogin'] != 'admin')
	{	
header('location:index.php');
}
else{

if(isset($_GET['edit']))
	{
		$editid=$_GET['edit'];
	}


	
if(isset($_POST['submit']))
  {
	$passwordreset = "false";
	$file = $_FILES['image']['name'];
	$file_loc = $_FILES['image']['tmp_name'];
	$folder="../images/";
	$new_file_name = strtolower($file);
	$final_file=str_replace(' ','-',$new_file_name);
	
	$name=$_POST['name'];
	$email=$_POST['email'];
	$gender=$_POST['gender'];
	$mobileno=$_POST['mobileno'];
	$designation=$_POST['designation'];
	$idedit=$_POST['idedit'];
	$image=$_POST['image'];
	$TeamID=$_POST['TeamID'];
    $msg = "";
	if(isset($_POST['Password']))
  	{
		$passwordreset = "true";
		$passwordencrypt=md5($_POST['Password']);
		echo ("encrypted password " . $passwordencrypt);
	}
	if(move_uploaded_file($file_loc,$folder.$final_file))
		{
			$image=$final_file;
		}

	$sql="UPDATE users SET name=(:name), email=(:email), gender=(:gender), mobile=(:mobileno), designation=(:designation), Image=(:image) ";
	if ($designation == "Student")
	{
		 $sql .= ",TeamID=(:TeamID) ";
	}
	if ($passwordreset == "true")
	{
		$sql .= ",password=(:password) ";	
	}

	$sql .= " WHERE id=(:idedit)";
	$query = $dbh->prepare($sql);
	$query-> bindParam(':name', $name, PDO::PARAM_STR);
	$query-> bindParam(':email', $email, PDO::PARAM_STR);
	$query-> bindParam(':gender', $gender, PDO::PARAM_STR);
	$query-> bindParam(':mobileno', $mobileno, PDO::PARAM_STR);
	$query-> bindParam(':designation', $designation, PDO::PARAM_STR);
	$query-> bindParam(':image', $image, PDO::PARAM_STR);
	if ($designation == "Student")
	{
	$query-> bindParam(':TeamID', $TeamID, PDO::PARAM_STR);
	}
	$query-> bindParam(':idedit', $idedit, PDO::PARAM_STR);
	if ($passwordreset == "true")
	{
		$query-> bindParam(':password', $passwordencrypt, PDO::PARAM_STR);
	}
	$query->execute();
if ($passwordreset == "true")
	{
	$title = "Your password reset goatcrist.us";
	$description = "Hi " .$name. " your password has been reset to " . $_POST['Password'];
	$to = $email;
	$subject = $title;
	$txt = $description;
	$headers = "From: " . $AdminEmail . "\r\n" .
	"BCC: ". $AdminBCC;
	mail($to,$subject,$txt,$headers);
	$msg .= " Email sent to ".$email . " with new password. ";
	}
	$msg.=" Information Updated Successfully " .$description;
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
	<meta name="theme-color" content="#3e454c">
	
	<title>Edit User</title>

	<!-- Font awesome -->
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<!-- Sandstone Bootstrap CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<!-- Bootstrap Datatables -->
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<!-- Bootstrap social button library -->
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<!-- Bootstrap select -->
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<!-- Bootstrap file input -->
	<link rel="stylesheet" href="css/fileinput.min.css">
	<!-- Awesome Bootstrap checkbox -->
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<!-- Admin Stye -->
	<link rel="stylesheet" href="css/style.css">

	<script type= "text/javascript" src="../vendor/countries.js"></script>
	<style>
.errorWrap {
    padding: 10px;
    margin: 0 0 20px 0;
	background: #dd3d36;
	color:#fff;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
.succWrap{
    padding: 10px;
    margin: 0 0 20px 0;
	background: #5cb85c;
	color:#fff;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}

.field-icon {
  float: right;
  margin-left: -25px;
  margin-top: -25px;
  position: relative;
  z-index: 2;
}

.container{
  padding-top:50px;
  margin: auto;
}
		</style>
</head>

<body>
<?php
		$sql = "SELECT * from users where id = :editid";
		$query = $dbh -> prepare($sql);
		$query->bindParam(':editid',$editid,PDO::PARAM_INT);
		$query->execute();
		$result=$query->fetch(PDO::FETCH_OBJ);
		$cnt=1;	
?>
	<?php include('includes/header.php');?>
	<div class="ts-main-content">
	<?php include('includes/leftbar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<h3 class="page-title">Edit User : <?php echo htmlentities($result->name); ?></h3>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">Edit Info</div>
<?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>

									<div class="panel-body">
<form method="post" class="form-horizontal" enctype="multipart/form-data" name="imgform">
<div class="form-group">
<label class="col-sm-2 control-label">Name<span style="color:red">*</span></label>
<div class="col-sm-4">
<input type="text" name="name" class="form-control" required value="<?php echo htmlentities($result->name);?>">
</div>
<label class="col-sm-2 control-label">Email<span style="color:red">*</span></label>
<div class="col-sm-4">
<input type="email" name="email" class="form-control" required value="<?php echo htmlentities($result->email);?>">
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label">Gender<span style="color:red">*</span></label>
<div class="col-sm-4">
<select name="gender" class="form-control" required>
                            <option value="">Select</option>
							<?php 
							if ($result->gender == "Male")
							{
								echo "<option SELECTED value=Male>Male</option>";
								echo "<option  value=Female>Female</option>";
							}
							else
							{
								echo "<option  value=Male>Male</option>";
								echo "<option SELECTED value=Female>Female</option>";
							}
                            ?>
                            </select>
</div>
<label class="col-sm-2 control-label">Designation<span style="color:red">*</span></label>
<div class="col-sm-4">
<select name="designation" id="designation" class="form-control" required>
                            <option value="">Select</option>
						<?php if ($result->designation == "Student")
						{ 
							echo ("<option value='Student' SELECTED>Student</option>");
						}
						else
						{
							echo ("<option value='Student'>Student</option>");
						}
						if ($result->designation == "Shark")
						{ 
							echo ("<option value='Shark' SELECTED>Shark</option>");
						}
						else
						{
							echo ("<option value='Shark'>Shark</option>");
						}
						?>
                            </select>
</div>
</div>


<div class="form-group">
<label class="col-sm-2 control-label">Image<span style="color:red">*</span></label>
<div class="col-sm-4">
<input type="file" name="image" class="form-control">
</div>

<label class="col-sm-2 control-label">Mobile No.<span style="color:red">*</span></label>
<div class="col-sm-4">
<input type="number" name="mobileno" class="form-control" required value="<?php echo htmlentities($result->mobile);?>">
</div>
</div>

<div class="form-group">
	<div class="col-sm-4 col-sm-offset-2">
		<img src="../images/<?php echo htmlentities($result->image);?>" width="100px"/>
		<input type="hidden" name="image" value="<?php echo htmlentities($result->image);?>" >
		<input type="hidden" name="idedit" value="<?php echo htmlentities($result->id);?>" >
	</div>
	<label class="col-sm-2 control-label">Team<span style="color:red">*</span></label>
		<div class="col-sm-4">
			<?php
			$sqlT = "SELECT * from Team where Status = 1";
			$queryT = $dbh -> prepare($sqlT);
			$queryT->execute();
			$resultT=$queryT->fetchAll(PDO::FETCH_OBJ);
			$cntT=1;	
			if ($result->designation == "Student")
			{
			?>
			<select name="TeamID" class="form-control" required>
				<?php
			}
			else
			{ ?>
			<select name="TeamID" class="form-control">
			<?php } ?>
        	<option value="">Select</option>
			<?php
				foreach($resultT as $res)
					{
						if ($result->TeamID == $res->TeamID)
						{
							echo "<option SELECTED  value=$res->TeamID>$res->TeamName</option>";
						}
						else
						{
							echo "<option  value=$res->TeamID>$res->TeamName</option>";
						}
					}
			?>
			</select>
		</div>
</div>

<div class="form-group">
	<div class="col-sm-4 col-sm-offset-2">
		
	</div>
<label class="col-sm-2 control-label">Reset Password</label>
<div class="col-sm-4">
	<input type="text" name="Password" id="Password" class="form-control" value="" onclick="alert('This is for changing a users password! You can leave it blank.');">
	
</div>

</div>

<div class="form-group">
	<div class="col-sm-8 col-sm-offset-2">
		<button class="btn btn-primary" name="submit" type="submit">Save Changes</button>
		<button class="btn btn-cancel" onclick="history.go(-1); return false;" name="cancel" type="cancel">Cancel</button>
	</div>
</div>

</form>
									</div>
								</div>
							</div>
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
				 $(document).ready(function () {          
					setTimeout(function() {
						$('.succWrap').slideUp("slow");
					}, 3000);

					$(".toggle-password").click(function() {

				  $(this).toggleClass("fa-eye fa-eye-slash");
				  var input = $($(this).attr("toggle"));
				  if (input.attr("type") == "password") {
				    input.attr("type", "text");
				  } else {
				    input.attr("type", "password");
				  }
				});



					});
	</script>

</body>
</html>
<?php } ?>