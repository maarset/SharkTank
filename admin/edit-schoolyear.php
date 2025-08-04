<?php
session_start();

ini_set('display_errors', '1');
include('includes/config.php');
//if(strlen($_SESSION['alogin'])==0)
if(strlen($_SESSION['alogin'])==0 || $_SESSION['alogin'] != 'admin')
	{	
header('location:index.php');
}
else {

if(isset($_GET['SchoolYearID'])) 
	{
		$schoolyearid=$_GET['SchoolYearID'];

		


	
if(isset($_POST['submit']))
  {

//$schoolyearid=$_POST['SchoolYearID'];
	$YearName =$_POST['YearName'];
	$StartDate_=date_create($_POST['StartDate']);
	$StartDate= date_format($StartDate_,"Y/m/d H:i:s");
	$EndDate_=date_create($_POST['EndDate']);
	$EndDate= date_format($EndDate_,"Y/m/d H:i:s");

	
	$sql="UPDATE SchoolYear SET YearName=(:yearname), StartDate=(:startdate), EndDate=(:enddate),UpdatedBy = (:updatedby),UpdatedDate = now(3) WHERE SchoolYearID=(:schoolyearid)";
	$query = $dbh->prepare($sql);
	$query-> bindParam(':yearname', $YearName, PDO::PARAM_STR);
	$query-> bindParam(':startdate', $StartDate, PDO::PARAM_STR);
	$query-> bindParam(':enddate', $EndDate, PDO::PARAM_STR);
	
	$query-> bindParam(':updatedby', $_SESSION['alogin'], PDO::PARAM_STR);
	$query-> bindParam(':schoolyearid', $schoolyearid, PDO::PARAM_STR);
	$query->execute();

	$msg="SchoolYear Updated Successfully";
	echo "<script type='text/javascript'> document.location = 'schoolyear.php'; </script>";
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
	
	<title>Edit School Year</title>

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

	<!--<script type= "text/javascript" src="../vendor/countries.js"></script>-->
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
<?php
		$sqlL = "SELECT SchoolYearID,YearName,StartDate,EndDate FROM SchoolYear WHERE SchoolYearID = (:schoolyearid)";
    $queryL = $dbh -> prepare($sqlL);
    $queryL-> bindParam(':schoolyearid', $schoolyearid, PDO::PARAM_STR);
    $queryL->execute();
    $resultL=$queryL->fetch(PDO::FETCH_OBJ);
    if($queryL->rowCount() > 0)
    //if($queryL->rowCount() == 1)
    {
		echo("NO TRANSACTION");
	}
?>
	<?php include('includes/header.php');?>
	<div class="ts-main-content">
	<?php include('includes/leftbar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<h3 class="page-title">Edit School Year : <?php echo htmlentities($resultL->YearName); ?></h3>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">Edit Info</div>
<?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>

									<div class="panel-body">
<form method="post" class="form-horizontal" enctype="multipart/form-data" name="imgform">
<div class="form-group">
	<input type="hidden" name="SchoolYearID" id="SchoolYearID" class="form-control" required value="<?php echo htmlentities($resultL->SchoolYearID);?>">
	
	<label class="col-sm-2 control-label">YearName<span style="color:red">*</span></label>
		<div class="col-sm-4">
		<input type="text" name="YearName" id="YearName" class="form-control" required value="<?php echo htmlentities($resultL->YearName);?>">
		</div>
	<label class="col-sm-2 control-label">Start Date <span style="color:red">*</span></label>
		<div class="col-sm-4 input-group datepick">
        	<input type="text" name="StartDate" id="StartDate" class="form-control"  value="<?php echo htmlentities($resultL->StartDate);?>" required>
			<div class="input-group-addon">
      			<span class="glyphicon glyphicon-calendar"></span>
    		</div>
		</div>
</div>


<div class="form-group">
	
<label class="col-sm-2 control-label">End Date<span style="color:red">*</span></label>

		<div class="col-sm-4 input-group datepick">
        	<input type="text" name="EndDate" id="EndDate" class="form-control"  value="<?php echo htmlentities($resultL->EndDate);?>" required>
			<div class="input-group-addon">
      			<span class="glyphicon glyphicon-calendar"></span>
    		</div>
		</div>	
	<BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>
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
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script language="javascript" src="https://momentjs.com/downloads/moment.js"></script>
<script language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/css/bootstrap-datetimepicker.min.css">
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
	<!--<script src="js/main.js"></script>-->
	<script type="text/javascript">
				 $(document).ready(function () {          
					setTimeout(function() {
						$('.succWrap').slideUp("slow");
					}, 3000);

					dts = new Date(document.getElementById("StartDate").value);
					dte = new Date(document.getElementById("EndDate").value);
					
					$('.datepick').datetimepicker({
 					  format: 'L',
 					  ignoreReadonly: true
 					});

					$('#StartDate').val(dts.toLocaleString());
					$('#EndDate').val(dte.toLocaleString());
					});
	</script>

</body>
</html>
<?php 
}
$query = null;
$queryL = null;
include('includes/close.php');
}
?>

