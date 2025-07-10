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

if(isset($_GET['ClassID'])) 
	{
		$classid=$_GET['ClassID'];

		


	
if(isset($_POST['submit']))
  {

	//$ClassID=$_POST['ClassID'];
	$ClassName=$_POST['ClassName'];
	$Room=$_POST['Room'];
	$SchoolYearID=$_POST['SchoolYearID'];

if(isset($_POST['CurrentClass']))
{
	$CurrentClass=$_POST['CurrentClass'];//
	echo('CURRENT CLASS = '.$CurrentClass);
	if ($CurrentClass == "TRUE")
	{
		if ($classid != $ClassIDGlobal || $SchoolYearID != $SchoolYearIDGlobal)
		{
			$sqlG = "UPDATE Setting SET Value = (:currentclass) WHERE Name = 'CurrentClassID'";
			$queryG = $dbh->prepare($sqlG);
			$queryG-> bindParam(':currentclass', $classid, PDO::PARAM_STR);
			$queryG->execute();

			$sqlG1 = "UPDATE Setting SET Value = (:schoolyearid) WHERE Name = 'CurrentSchoolYearID'";
			$queryG1 = $dbh->prepare($sqlG1);
			$queryG1-> bindParam(':schoolyearid', $SchoolYearID, PDO::PARAM_STR);
			$queryG1->execute();


		}
	}
}	

	

	$sql="UPDATE Class SET ClassName=(:classname), Room=(:room), SchoolYearID=(:schoolyearid),UpdatedBy = (:updatedby),UpdatedDate = now(3) WHERE ClassID=(:classid)";
	$query = $dbh->prepare($sql);
	$query-> bindParam(':classname', $ClassName, PDO::PARAM_STR);
	$query-> bindParam(':room', $Room, PDO::PARAM_STR);
	$query-> bindParam(':schoolyearid', $SchoolYearID, PDO::PARAM_STR);
	$query-> bindParam(':updatedby', $_SESSION['alogin'], PDO::PARAM_STR);
	$query-> bindParam(':classid', $classid, PDO::PARAM_STR);
	$query->execute();



	$msg="Class Updated Successfully |" . $CurrentClass . "|";
	//echo "<script type='text/javascript'> document.location = 'class.php'; </script>";
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
	
	<title>Edit Class</title>

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
<?php
		$sqlL = "SELECT ClassID,ClassName,Room,SchoolYearID,Status,UpdatedBy,UpdatedDate,CreatedBy,CreatedDate FROM Class WHERE ClassID = (:classid)";
    $queryL = $dbh -> prepare($sqlL);
    $queryL-> bindParam(':classid', $classid, PDO::PARAM_STR);
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
					<h3 class="page-title">Edit Class : <?php echo htmlentities($resultL->ClassName); ?></h3>
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading">Edit Info</div>
								<?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
								else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>

								<div class="panel-body">
									<form method="post" class="form-horizontal" enctype="multipart/form-data" name="imgform">
									<div class="form-group"><label class="col-sm-2 control-label">School Year<span style="color:red">*</span></label>
										<div class="col-sm-4">
										<?php
											$sqlLT = "SELECT * from SchoolYear where Status = 1";
											$queryLT = $dbh -> prepare($sqlLT);
											$queryLT->execute();
											$resultLT=$queryLT->fetchAll(PDO::FETCH_OBJ);
											$cntLT=1;	
										?>
										<select name="SchoolYearID" class="form-control" required>
        								    <option value="">Select</option>
										<?php
											foreach($resultLT as $resLT)
												{
														if ($resultL->SchoolYearID == $resLT->SchoolYearID)
														{
															echo "<option SELECTED value=$resLT->SchoolYearID>$resLT->YearName</option>";
														}
														else
														{
															echo "<option  value=$resLT->SchoolYearID>$resLT->YearName</option>";
														}
												}
										?>
 										</select>
										</div>
										<label class="col-sm-2 control-label">Class Name<span style="color:red">*</span></label>
										<div class="col-sm-4">
											<input type="text" name="ClassName" class="form-control" value='<?php echo($resultL->ClassName) ?>' required>
										</div>
									</div>

									<div class="form-group"><label class="col-sm-2 control-label">Room</label>
										<div class="col-sm-4">
										<input type="text" name="Room" class="form-control" value='<?php echo($resultL->Room) ?>'>
										</div>
										<label class="col-sm-2 control-label">Current Class</label>
										<div class="col-sm-4">
											<?php
											if ($ClassIDGlobal == $resultL->ClassID)
											{
												?>
												<input type="checkbox" id="CurrentClass" name="CurrentClass" class="form-control" value='TRUE'  CHECKED>
											<?php
											}
											else
											{
											?>
												<input type="checkbox" id="CurrentClass" name="CurrentClass" class="form-control" value='TRUE' >
												<?php
											}
											?>
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
					</div><!--END ROW-->
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

					dt = new Date(document.getElementById("DateEntered").value);
					
					
					$('.datepick').datetimepicker({
 					  format: 'L',
 					  ignoreReadonly: true
 					});

					$('#DateEntered').val(dt.toLocaleString());
					});
	</script>

</body>
</html>
<?php 
}
}
?>

