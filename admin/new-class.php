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
	
	$ClassName=$_POST['ClassName'];
	$Room=$_POST['Room'];
	$SchoolYearID=$_POST['SchoolYearID'];
	
    
	$sql ="INSERT INTO Class (ClassName,Room,SchoolYearID, ";
	
	$sql .= "Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) ";
	$sql .= "VALUES (:classname,:room,:schoolyearid, ";
	$sql .= "1,:createdby	,now(3)	,:updatedby	,now(3))";
	
	$query = $dbh->prepare($sql);
	$query-> bindParam(':classname', $ClassName, PDO::PARAM_STR);
	$query-> bindParam(':room', $Room, PDO::PARAM_STR);
	$query-> bindParam(':schoolyearid', $SchoolYearID, PDO::PARAM_STR);
	$query-> bindParam(':createdby', $_SESSION['alogin'], PDO::PARAM_STR);
	$query-> bindParam(':updatedby', $_SESSION['alogin'], PDO::PARAM_STR);
	
	$query->execute();
	$lastInsertId = $dbh->lastInsertId();
	if($lastInsertId)
	{
		


	echo "<script type='text/javascript'>alert('Class Added Sucessfully!');</script>";
	echo "<script type='text/javascript'> document.location = 'class.php'; </script>";
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
	
	<title>Add Class</title>

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
						<h3 class="page-title">New Class </h3>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">New Class</div>
<?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>

									<div class="panel-body">
<form method="post" class="form-horizontal" enctype="multipart/form-data" name="imgform">

<div class="form-group">
	<label class="col-sm-2 control-label">School Year<span style="color:red">*</span></label>
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

					if(isset($_POST['SchoolYearID']))
					{
						if ($_POST['SchoolYearID'] == $resLT->SchoolYearID)
						{
							echo "<option SELECTED value=$resLT->SchoolYearID>$resLT->Description</option>";
						}
						else
						{
							echo "<option  value=$resLT->SchoolYearID>$resLT->Description</option>";
						}
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
		<?php
		if(isset($_POST['ClassName']))
			{ 
				?>
			<input type="text" name="ClassName" class="form-control" value='<?php echo($_POST['ClassName']) ?>' required>
			<?php 
			} else {
				?>
		 <input type="text" name="ClassName" class="form-control" required value="">
<?php } ?>

			
</div><!-- END FORM GROUP-->

	
</div>

<div class="form-group">
		<label class="col-sm-2 control-label">Room</label>
	<div class="col-sm-4">
		<?php
		if(isset($_POST['Room']))
			{ 
				?>
			<input type="text" name="Room" class="form-control" value='<?php echo($_POST['Room']) ?>' required>
			<?php 
			} else {
				?>
		 <input type="text" name="Room" class="form-control" required value="">
<?php } ?>
	</div>
	
</div><!-- END FORM GROUP-->


<div class="form-group">
		
	






<div class="form-group">
		<div class="col-sm-8 col-sm-offset-2">
		<button class="btn btn-primary" name="submit" type="submit">Save Changes</button>
		<button class="btn btn-cancel" onclick="history.go(-1); return false;" name="cancel" type="cancel">Cancel</button>
	</div>
</div>
<BR><BR><BR><BR><BR>
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
	<script type="text/javascript">
				 $(document).ready(function () {          
					setTimeout(function() {
						$('.succWrap').slideUp("slow");
					}, 3000);

				$('.datepick').datetimepicker({
 					  format: 'L',
 					  ignoreReadonly: true
 					});
				
				 function goPrev()
  				{
  				  window.history.back();
  				}
					});
	</script>

</body>
</html>
<?php 
$query = null;
$queryLT = null;
include('includes/close.php');
} ?>