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




	
if(isset($_POST['submit']))
  {
	//$TeamID=$_POST['TeamID'];
	//$TeamID = $editid;
	$TeamName=$_POST['TeamName'];
	//$ClassID=$_POST['ClassID'];
	$ClassID=$ClassIDGlobal;
	//$SchoolYearID=$_POST['SchoolYearID'];
	$SchoolYearID=$SchoolYearIDGlobal;
	$IGFollowers=$_POST['IGFollowers'];
	$credit=$_POST['credit'];
	$debit=$_POST['debit'];
	$balance=$_POST['balance'];


	//$sql="UPDATE team SET TeamName=(:TeamName), ClassID=(:ClassID), SchoolYearID=(:SchoolYearID), SharkID=(:SharkID), credit=(:credit), debit=(:debit), balance=(:balance) WHERE TeamID=(:TeamID)";
	$sql="INSERT INTO Team (TeamName,ClassID,SchoolYearID,IGFollowers,credit,debit,balance,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) VALUES (:TeamName,:ClassID,:SchoolYearID,:IGFollowers,:credit,:debit,:balance,1,'admin'	,now(3)	,'admin'	,now(3))";
	$query = $dbh->prepare($sql);
	$query-> bindParam(':TeamName', $TeamName, PDO::PARAM_STR);
	$query-> bindParam(':ClassID', $ClassID, PDO::PARAM_STR);
	$query-> bindParam(':SchoolYearID', $SchoolYearID, PDO::PARAM_STR);
	$query-> bindParam(':IGFollowers', $IGFollowers, PDO::PARAM_STR);
	$query-> bindParam(':credit', $credit, PDO::PARAM_STR);
	$query-> bindParam(':debit', $debit, PDO::PARAM_STR);
	$query-> bindParam(':balance', $balance, PDO::PARAM_STR);
	//$query-> bindParam(':TeamID', $TeamID, PDO::PARAM_STR);
	$query->execute();
	$lastInsertId = $dbh->lastInsertId();
	if($lastInsertId)
	{
	echo "<script type='text/javascript'>alert('Team Added Sucessfully!');</script>";
	echo "<script type='text/javascript'> document.location = 'teamlist.php'; </script>";
	}
	else 
	{
	$error="Something went wrong. Please try again";
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
		</style>
</head>

<body>

	<?php include('includes/header.php');?>
	<div class="ts-main-content">
	<?php include('includes/leftbar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<h3 class="page-title">New Team - <?php echo($CurrentSchoolyearGlobal) ?></h3>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">New Team</div>
<?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>

									<div class="panel-body">
<form method="post" class="form-horizontal" enctype="multipart/form-data" name="imgform">

<div class="form-group">
	<label class="col-sm-2 control-label">Name<span style="color:red">*</span></label>
	<div class="col-sm-4">
		<input type="text" name="TeamName" class="form-control" required value="">
	</div>
		<label class="col-sm-2 control-label">Class<span style="color:red">*</span></label>

	<div class="col-sm-4">
		<?php
				$sqlC = "SELECT * from Class where Status = 1";
				$queryC = $dbh -> prepare($sqlC);
				$queryC->execute();
				$resultC=$queryC->fetchAll(PDO::FETCH_OBJ);
				$cntC=1;	
		?>
		<select name="ClassID" class="form-control" disabled>
            <option value="">Select</option>
		<?php
			foreach($resultC as $resC)
				{
					if ($ClassIDGlobal == $resC->ClassID)
					{
						echo "<option  value=$resC->ClassID SELECTED>$resC->ClassName</option>";
					}
					else
					{
				echo "<option  value=$resC->ClassID>$resC->ClassName</option>";
					}
				}
					?>
 		</select>
			
</div><!-- END FORM GROUP-->

	
</div>

<div class="form-group">
		<label class="col-sm-2 control-label">IG Followers<span style="color:red">*</span></label>
	<div class="col-sm-4">
		<input type="text" name="IGFollowers" class="form-control" style="Width:200px" value="0" >
		
	</div>
		<label class="col-sm-2 control-label">    </label>
	<div class="col-sm-4">
		
	</div>
</div><!-- END FORM GROUP-->


<div class="form-group">
		<label class="col-sm-2 control-label">credit</label>
	<div class="col-sm-2">
		<input type="text" name="credit" class="form-control" value="0.00">
	</div>

		<label class="col-sm-2 control-label">debit</label>
	<div class="col-sm-2">
		<input type="text" name="debit" class="form-control"  value="0.00">
	</div>
	<label class="col-sm-2 control-label">balance</label>
	<div class="col-sm-2">
		<input type="text" name="balance" class="form-control"  value="0.00">
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
					});
	</script>

</body>
</html>
<?php 
$query = null;
$queryC = null;
include('includes/close.php');
} ?>