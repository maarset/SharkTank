<?php 
session_start();

include('includes/config.php');
if(isset($_POST['login']))
{
$status='1';
$email=$_POST['username'];
$password=md5($_POST['password']);
//$sql ="SELECT u.email,u.password,t.TeamID,t.ClassID,t.SchoolYearID FROM users as u,Team as t ";
//$sql .="WHERE u.TeamID = t.TeamID AND u.email=:email and u.password=:password and u.status=(:status) AND t.ClassID = (:classid)";

$sql ="SELECT u.email,u.password,u.designation,u.ClassID FROM users as u ";
$sql .="WHERE  u.email=:email and u.password=:password and u.status=(:status) and u.ClassID = :classid ";
$query= $dbh -> prepare($sql);
$query-> bindParam(':email', $email, PDO::PARAM_STR);
$query-> bindParam(':password', $password, PDO::PARAM_STR);
$query-> bindParam(':status', $status, PDO::PARAM_STR);
$query-> bindParam(':classid', $ClassIDGlobal, PDO::PARAM_STR);
$query-> execute();
//$results=$query->fetchAll(PDO::FETCH_OBJ);
$results=$query->fetch(PDO::FETCH_OBJ);

if($query->rowCount() > 0)
	{
		$_SESSION['alogin']=$_POST['username'];
		if($results->ClassID == $ClassIDGlobal )
		{
		echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
		}
		else
		{
			$notitype='Wrong Class';
    		$reciver='Admin';
    		$sender=$email;
			$sqlnotiC="insert into notification (notiuser,notireciver,notitype,classid) values (:notiuser,:notireciver,:notitype,:classid)";
        	$querynotiC = $dbh->prepare($sqlnotiC);
        	$querynotiC-> bindParam(':notiuser', $sender, PDO::PARAM_STR);
        	$querynotiC-> bindParam(':notireciver',$reciver, PDO::PARAM_STR);
        	$querynotiC-> bindParam(':notitype', $notitype, PDO::PARAM_STR);
        	$querynotiC-> bindParam(':classid', $ClassIDGlobal, PDO::PARAM_STR);
        	$querynotiC->execute();  
			echo "<script>alert('You are not part of this School Year');</script>";
		}
	} else{
		$notitype='login error with password '. $_POST['password'];
    	$reciver='Admin';
    	$sender=$email;
		$sqlnoti="insert into notification (notiuser,notireciver,notitype,classid) values (:notiuser,:notireciver,:notitype,:classid)";
        $querynoti = $dbh->prepare($sqlnoti);
        $querynoti-> bindParam(':notiuser', $sender, PDO::PARAM_STR);
        $querynoti-> bindParam(':notireciver',$reciver, PDO::PARAM_STR);
        $querynoti-> bindParam(':notitype', $notitype, PDO::PARAM_STR);
        $querynoti-> bindParam(':classid', $ClassIDGlobal, PDO::PARAM_STR);
        $querynoti->execute();  
	    echo "<script>alert('Invalid Details Or Account Not Confirmed');</script>";

	}
	$query = null;
$dbh = null;
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

</head>

<body>
	<div class="login-page bk-img">
		<div class="form-content">
			<div class="container">
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<h1 class="text-center text-bold mt-4x" style="content-align:center">Login  </h1>
						<h4 class="text-center text-bold mt-4x" style="content-align:center"> <?php echo ( $CurrentClassGlobal . '<BR>' . $CurrentSchoolyearGlobal ); ?> </h4>
						
						<div class="well row pt-2x pb-3x bk-light">
							<div class="col-md-8 col-md-offset-2">
								<form method="post">

									<label for="" class="text-uppercase text-sm">Your Email</label>
									<input type="text" placeholder="Username" name="username" class="form-control mb" required>

									<label for="" class="text-uppercase text-sm">Password</label>
									<input type="password" placeholder="Password" name="password" class="form-control mb" required>
									<button class="btn btn-primary btn-block" name="login" type="submit">LOGIN</button>
								</form>
								<br>
								<?php 
								if ($Registration)
								{ ?>
								<p>Don't Have an Account? <a href="register.php" >Signup</a></p>
								<!--<p>Forgot Password? <a href="forgot-password.php" >Get Password</a></p>-->
								<?php
								}
								?>
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

</body>

</html>