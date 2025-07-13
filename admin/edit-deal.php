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
	$DealID=$_POST['idedit']; //MAKE HIDDEN IN FORM
	$DealName=$_POST['DealName'];
	$SharkID=$_POST['SharkID']; //MAKE HIDDEN
	$TeamID=$_POST['TeamID'];   //MAKE HIDDEN
	$ClassID=$_POST['ClassID'];   //MAKE HIDDEN
	$TotalInvested=$_POST['TotalInvested'];
	$PercentOwned=$_POST['PercentOwned'];   //$SchoolYearIDGlobal



	$sql="UPDATE Deal SET DealName=(:dealname),TotalInvested = (:totalinvested),PercentOwned = (:percentowned), ";
	$sql .= "Status = 1,UpdatedBy = (:updatedby),UpdatedDate = now(3)  WHERE DealID=(:dealid)";
	$query = $dbh->prepare($sql);
	$query-> bindParam(':dealname', $DealName, PDO::PARAM_STR);
	$query-> bindParam(':totalinvested', $TotalInvested, PDO::PARAM_STR);
	$query-> bindParam(':percentowned', $PercentOwned, PDO::PARAM_STR);
	$query-> bindParam(':updatedby', $_SESSION['alogin'], PDO::PARAM_STR);
	$query-> bindParam(':dealid', $editid, PDO::PARAM_STR);
	$query->execute();

	$sqlH = "INSERT INTO DealHistory (DealID,DealName,SharkID,TeamID,ClassID,TotalInvested,PercentOwned,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) ";
	$sqlH .= " VALUES         (:dealid,:dealname,:sharkid,:teamid,:classid,:totalinvested,:percentowned,1,:createdby,now(3)	,:updatedby,now(3))";
	$queryH = $dbh->prepare($sqlH);
	$queryH-> bindParam(':dealid', $editid, PDO::PARAM_STR);
	$queryH-> bindParam(':dealname', $DealName, PDO::PARAM_STR);
	$queryH-> bindParam(':sharkid', $SharkID, PDO::PARAM_STR);
	$queryH-> bindParam(':teamid', $TeamID, PDO::PARAM_STR);
	$queryH-> bindParam(':classid', $ClassID, PDO::PARAM_STR);
	$queryH-> bindParam(':totalinvested', $TotalInvested, PDO::PARAM_STR);
	$queryH-> bindParam(':percentowned', $PercentOwned, PDO::PARAM_STR);
	$queryH-> bindParam(':createdby', $_SESSION['alogin'], PDO::PARAM_STR);
	$queryH-> bindParam(':updatedby', $_SESSION['alogin'], PDO::PARAM_STR);
	$queryH->execute();
	$msg="Information Updated Successfully";
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
	
	<title>Edit Team</title>

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
		$sql = "SELECT * from Deal where DealID = :editid";
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
						<h3 class="page-title">Edit Deal : <?php echo htmlentities($result->DealName); ?></h3>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">Edit Info</div>
<?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>

									<div class="panel-body">
<form method="post" class="form-horizontal" enctype="multipart/form-data" name="imgform">
	<input type="hidden" name="DealID" value="<?php echo htmlentities($result->DealID); ?>">

	<input type="hidden" name="SharkID" value="<?php echo htmlentities($result->SharkID); ?>">
	<input type="hidden" name="TeamID" value="<?php echo htmlentities($result->TeamID); ?>">
	<input type="hidden" name="ClassID" value="<?php echo htmlentities($result->ClassID); ?>">

<div class="form-group">
	<label class="col-sm-2 control-label">Deal Name<span style="color:red">*</span></label>
	<div class="col-sm-4">
		<input type="text" name="DealName" style="Width:300px"  class="form-control" required value="<?php echo htmlentities($result->DealName);?>">
	</div>
		<label class="col-sm-2 control-label">Total Invested<span style="color:red">*</span></label>

	<div class="col-sm-4">
		<input type="text" name="TotalInvested" style="Width:200px"  class="form-control" required value="<?php echo htmlentities($result->TotalInvested);?>">
	</div>
	<label class="col-sm-2 control-label">Percent Owned<span style="color:red">*</span></label>
	<div class="col-sm-4">
		<input type="text" name="PercentOwned" style="Width:200px"  class="form-control" required value="<?php echo htmlentities($result->PercentOwned);?>">
	</div>
		
			
</div><!-- END FORM GROUP-->

	
</div>









<div class="form-group">
	<div class="col-sm-4 col-sm-offset-2"><input type="hidden" name="idedit" value="<?php echo htmlentities($result->DealID);?>" ></div>
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
<?php } ?>