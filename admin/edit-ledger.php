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

if(isset($_GET['LedgerID'])) 
	{
		$ledgerid=$_GET['LedgerID'];

		


	
if(isset($_POST['submit']))
  {

$ledgerid=$_POST['LedgerID'];
$teamid =$_POST['TeamID'];
	$ledgertypeid=$_POST['LedgerTypeID'];
	$amount=$_POST['Amount'];
	echo ('dateentered' . ' |' . $_POST['DateEntered'] . '|');
	$dateQuery = date_create($_POST['DateEntered']);
	$dateentered= date_format($dateQuery,"Y/m/d H:i:s");

	
	

	$sql="UPDATE Ledger SET LedgerTypeID=(:ledgertypeid), Amount=(:amount), DateEntered=(:dateentered),UpdatedBy = (:updatedby),UpdatedDate = now(3) WHERE LedgerID=(:ledgerid)";
	$query = $dbh->prepare($sql);
	$query-> bindParam(':ledgertypeid', $ledgertypeid, PDO::PARAM_STR);
	$query-> bindParam(':amount', $amount, PDO::PARAM_STR);
	$query-> bindParam(':ledgerid', $ledgerid, PDO::PARAM_STR);
	$query-> bindParam(':dateentered', $dateentered, PDO::PARAM_STR);
	$query-> bindParam(':updatedby', $_SESSION['alogin'], PDO::PARAM_STR);
	$query->execute();

//UPDAT TEAM BUCKETS
		$sql1 = "SELECT SUM(Amount) AS balance FROM Ledger where Status = 1 AND TeamID = (:teamid)";
		$sql2 = "SELECT SUM(Amount) AS debit FROM Ledger where Status = 1 and Amount < 0 AND TeamID = (:teamid)";
		$sql3 = "SELECT SUM(Amount) AS credit FROM Ledger where Status = 1 and Amount > 0 AND TeamID = (:teamid)";
		$query1= $dbh -> prepare($sql1);
		$query2= $dbh -> prepare($sql2);
		$query3= $dbh -> prepare($sql3);
		$query1-> bindParam(':teamid', $teamid, PDO::PARAM_STR);
		$query2-> bindParam(':teamid', $teamid, PDO::PARAM_STR);
		$query3-> bindParam(':teamid', $teamid, PDO::PARAM_STR);
		$query1-> execute();
		$query2-> execute();
		$query3-> execute();
		$results1=$query1->fetch(PDO::FETCH_OBJ);
		$results2=$query2->fetch(PDO::FETCH_OBJ);
		$results3=$query3->fetch(PDO::FETCH_OBJ);
		$Balance = $results1->balance;
		$Debit = $results2->debit;
		$Credit = $results3->credit;

		$sqlBal = "Update Team set balance = (:balance) WHERE TeamID = (:teamid) and SchoolYearID = (:schoolyearid) ";
		$sqlDeb = "Update Team set debit = (:debit) WHERE TeamID = (:teamid) and SchoolYearID = (:schoolyearid) ";
		$sqlCred = "Update Team set credit = (:credit) WHERE TeamID = (:teamid) and SchoolYearID = (:schoolyearid) ";

		$queryBal= $dbh -> prepare($sqlBal);
		$queryDeb= $dbh -> prepare($sqlDeb);
		$queryCred= $dbh -> prepare($sqlCred);

		$queryBal-> bindParam(':balance', $Balance, PDO::PARAM_STR);
		$queryDeb-> bindParam(':debit', $Debit, PDO::PARAM_STR);
		$queryCred-> bindParam(':credit', $Credit, PDO::PARAM_STR);

		$queryBal-> bindParam(':teamid', $teamid, PDO::PARAM_STR);
		$queryDeb-> bindParam(':teamid', $teamid, PDO::PARAM_STR);
		$queryCred-> bindParam(':teamid', $teamid, PDO::PARAM_STR);

		$queryBal-> bindParam(':schoolyearid', $SchoolYearIDGlobal, PDO::PARAM_STR);
		$queryDeb-> bindParam(':schoolyearid', $SchoolYearIDGlobal, PDO::PARAM_STR);
		$queryCred-> bindParam(':schoolyearid', $SchoolYearIDGlobal, PDO::PARAM_STR);
												
		$queryBal-> execute();
		$queryDeb-> execute();
		$queryCred-> execute();

	

	$msg="Ledger Updated Successfully";
	echo "<script type='text/javascript'> document.location = 'ledger.php'; </script>";
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
	
	<title>Edit Ledger</title>

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
		$sqlL = "SELECT LedgerID,Amount,LedgerTypeID,TeamID,DateEntered FROM Ledger WHERE LedgerID = (:ledgerid)";
    $queryL = $dbh -> prepare($sqlL);
    $queryL-> bindParam(':ledgerid', $ledgerid, PDO::PARAM_STR);
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
						<h3 class="page-title">Edit Ledger : <?php echo htmlentities($resultL->LedgerID); ?></h3>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">Edit Info</div>
<?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>

									<div class="panel-body">
<form method="post" class="form-horizontal" enctype="multipart/form-data" name="imgform">
<div class="form-group">
	<input type="hidden" name="LedgerID" id="LedgerID" class="form-control" required value="<?php echo htmlentities($resultL->LedgerID);?>">
	<input type="hidden" name="TeamID" id="TeamID" class="form-control" required value="<?php echo htmlentities($resultL->TeamID);?>">
	<label class="col-sm-2 control-label">Amount<span style="color:red">*</span></label>
		<div class="col-sm-4">
		<input type="text" name="Amount" id="Amount" class="form-control" required value="<?php echo htmlentities($resultL->Amount);?>">
		</div>
	<label class="col-sm-2 control-label">DateEntered <span style="color:red">*</span></label>
		<div class="col-sm-4 input-group datepick">
        	<input type="text" name="DateEntered" id="DateEntered" class="form-control"  value="<?php echo htmlentities($resultL->DateEntered);?>" required>
			<div class="input-group-addon">
      			<span class="glyphicon glyphicon-calendar"></span>
    		</div>
		</div>
</div>


<div class="form-group">
	
<label class="col-sm-2 control-label">Ledger Type<span style="color:red">*</span></label>
<div class="col-sm-4">
<!--<input type="text" name="TeamID" class="form-control" required value="<?php echo htmlentities($result->TeamID);?>">-->
	<?php
			$sqlT = "SELECT * from LedgerType where Status = 1";
			$queryT = $dbh -> prepare($sqlT);
			$queryT->execute();
			$resultT=$queryT->fetchAll(PDO::FETCH_OBJ);
			$cntT=1;	
	?>
	<select name="LedgerTypeID" id="LedgerTypeID" class="form-control" required>
                            <option value="">Select</option>
	<?php
			foreach($resultT as $res)
				{
					if ($res->LedgerTypeID == $resultL->LedgerTypeID)
					{
						echo "<option SELECTED  value=$res->LedgerTypeID>$res->Description</option>";
					}
					else
					{
						echo "<option  value=$res->LedgerTypeID>$res->Description</option>";
					}
				}
					?>
 	</select>
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

