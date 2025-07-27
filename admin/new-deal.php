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
    $ExistingDeal = false;
	$DealName=$_POST['DealName'];
	$SharkID=$_POST['SharkID'];
	$TeamID=$_POST['TeamID'];
	$TotalInvested=$_POST['TotalInvested'];
	$PercentOwned=$_POST['PercentOwned'];   //$SchoolYearIDGlobal

	$sqlCheck = "SELECT D.DealID, D.DealName, D.SharkID, D.TeamID, D.ClassID, T.TeamName, D.Status FROM Deal D, Team T ";
	$sqlCheck .= "where D.TeamID = T.TeamID AND D.ClassID = :classid AND D.TeamID = :teamid ";
	$queryCheck = $dbh->prepare($sqlCheck);
	$queryCheck-> bindParam(':classid', $ClassIDGlobal, PDO::PARAM_STR);
	$queryCheck-> bindParam(':teamid', $TeamID, PDO::PARAM_STR);
	$queryCheck->execute();
	$resultCheck=$queryCheck->fetch(PDO::FETCH_OBJ);
	if($queryCheck->rowCount() > 0)
	{
		$ExistingDeal = true; 
	}

	if ($ExistingDeal == false)
	{
		$sqlI = "INSERT INTO Deal (DealName,SharkID,TeamID,ClassID,TotalInvested,PercentOwned,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) ";
		$sqlI .= " VALUES         (:dealname,:sharkid,:teamid,:classid,:totalinvested,:percentowned,1,:createdby,now(3),:updatedby,now(3))";
		$sqlH = "INSERT INTO DealHistory (DealID,DealName,SharkID,TeamID,ClassID,TotalInvested,PercentOwned,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) ";
		$sqlH .= " VALUES         (:dealid,:dealname,:sharkid,:teamid,:classid,:totalinvested,:percentowned,1,:createdby,now(3)	,:updatedby,now(3))";
		$queryI = $dbh->prepare($sqlI);
		
		$queryI-> bindParam(':dealname', $DealName, PDO::PARAM_STR);
		$queryI-> bindParam(':sharkid', $SharkID, PDO::PARAM_STR);
		$queryI-> bindParam(':teamid', $TeamID, PDO::PARAM_STR);
		$queryI-> bindParam(':classid', $ClassIDGlobal, PDO::PARAM_STR);
		$queryI-> bindParam(':totalinvested', $TotalInvested, PDO::PARAM_STR);
		$queryI-> bindParam(':percentowned', $PercentOwned, PDO::PARAM_STR);
		$queryI-> bindParam(':createdby', $_SESSION['alogin'], PDO::PARAM_STR);
		$queryI-> bindParam(':updatedby', $_SESSION['alogin'], PDO::PARAM_STR);
		$queryI->execute();
		$lastInsertId = $dbh->lastInsertId();
		if($lastInsertId)
		{
			$queryH = $dbh->prepare($sqlH);
			$queryH-> bindParam(':dealid', $lastInsertId, PDO::PARAM_STR);
			$queryH-> bindParam(':dealname', $DealName, PDO::PARAM_STR);
			$queryH-> bindParam(':sharkid', $SharkID, PDO::PARAM_STR);
			$queryH-> bindParam(':teamid', $TeamID, PDO::PARAM_STR);
			$queryH-> bindParam(':classid', $ClassIDGlobal, PDO::PARAM_STR);
			$queryH-> bindParam(':totalinvested', $TotalInvested, PDO::PARAM_STR);
			$queryH-> bindParam(':percentowned', $PercentOwned, PDO::PARAM_STR);
			$queryH-> bindParam(':createdby', $_SESSION['alogin'], PDO::PARAM_STR);
			$queryH-> bindParam(':updatedby', $_SESSION['alogin'], PDO::PARAM_STR);
			$queryH->execute();
			echo "<script type='text/javascript'>alert('Deal Added Sucessfully!');</script>";
			echo "<script type='text/javascript'> document.location = 'deallist.php'; </script>";
		}
		else 
		{
			$error="Something went wrong. Please try again";
		}

	}
	else
	{
		$error="Team ".$resultCheck->TeamName." already has a deal."; 
		echo "<script type='text/javascript'>alert('Team '.$resultCheck->TeamName.' already has a deal.');</script>";
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
	
	<title>New Deal</title>

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
						<h3 class="page-title">New Deal </h3>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">New Deal</div>
<?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>

									<div class="panel-body">
<form method="post" class="form-horizontal" enctype="multipart/form-data" name="imgform">

<div class="form-group">
	<label class="col-sm-2 control-label">Deal Name<span style="color:red">*</span></label>
	<div class="col-sm-4">
		<input type="text" name="DealName" class="form-control" required value="">
	</div>
		<label class="col-sm-2 control-label">Shark<span style="color:red">*</span></label>

	
	<div class="col-sm-4">
		<?php
				$sqlSS = "SELECT * from Shark where Status = 1";
				$querySS = $dbh -> prepare($sqlSS);
				$querySS->execute();
				$resultSS=$querySS->fetchAll(PDO::FETCH_OBJ);
				$cntSS=1;	
		?>
		<select name="SharkID" class="form-control" required>
            <option value="">Select</option>
		<?php
			foreach($resultSS as $resSS) 
				{
					echo "<option  value=$resSS->SharkID>$resSS->SharkName</option>";
				}
					?>
 		</select>
</div><!-- END FORM GROUP-->

	
</div>

<div class="form-group">
		<label class="col-sm-2 control-label">Team<span style="color:red">*</span></label>
	<div class="col-sm-4">
		<?php
				$sqlS = "SELECT * from Team where Status = 1 AND ClassID = (:classid)";
				$queryS = $dbh -> prepare($sqlS);
				$queryS-> bindParam(':classid', $ClassIDGlobal, PDO::PARAM_STR);
				
				$queryS->execute();
				$resultS=$queryS->fetchAll(PDO::FETCH_OBJ);
				$cntS=1;	
		?>
		<select name="TeamID" class="form-control" required>
            <option value="">Select</option>
		<?php
			foreach($resultS as $resS)
				{
					echo "<option  value=$resS->TeamID>$resS->TeamName</option>";
				}
					?>
 		</select>
	</div>
		<label class="col-sm-2 control-label">Total Invested<span style="color:red">*</span></label>
	<div class="col-sm-4">
		$ <input type="number" class="form-control" name="TotalInvested" class="form-control: min="0.01" step="0.01" max="250000000" value=''  required/>
	</div>
</div><!-- END FORM GROUP-->


<div class="form-group">
		<label class="col-sm-2 control-label">Percent Owned</label>
	<div class="col-sm-2">
		<input type="text" name="PercentOwned" class="form-control" value="">
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
<?php } ?>