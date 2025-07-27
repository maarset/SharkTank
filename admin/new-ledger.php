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
		
	if(isset($_REQUEST['TeamID']))
	{
		$TeamID = $_REQUEST['TeamID'];
	}
	else
	{
		$ErrorFlag = TRUE;
	}
		$Name=$_POST['Name'];
		$Address=$_POST['Address'];

		
	$LedgerTypeID=$_POST['LedgerTypeID'];
	$Amount=$_POST['Amount'];
	echo ('dateentered' . ' |' . $_POST['DateEntered'] . '|');
	$dateQuery = date_create($_POST['DateEntered']);
	$DateEntered= date_format($dateQuery,"Y/m/d H:i:s");
	$VendorID = $_POST['VendorID'];
	$Comment =  $_POST['Comment'];


		$sqlL = "SELECT  LedgerTypeID,Description, Debit FROM LedgerType  WHERE LedgerTypeID = (:ledgertypeid)";
			$queryL = $dbh -> prepare($sqlL);
			$queryL->bindParam(':ledgertypeid',$LedgerTypeID,PDO::PARAM_INT);
			$queryL->execute();
			$resultL=$queryL->fetch(PDO::FETCH_OBJ);
			$cntL=1;	
			$Debit = $resultL->Debit;
			$Description = $resultL->Description;
	     if ($Amount > 0 && $Debit == 0)
		 {
			$ErrorFlag = TRUE;
			echo "<script type='text/javascript'>alert('For ' . $Description . ' you need to input a negative number!');</script>";
			$error = 'For ' . $Description . ' you need to input a negative number!';
		 }
		 else
		 {
			$ErrorFlag = FALSE;
		 }
	 
	     if ($ErrorFlag == FALSE)
		 {
		$sql ="INSERT INTO Ledger (LedgerTypeID,TeamID,SchoolYearID, ";
		if ($VendorID != "") { $sql .= "VendorID,"; }
		
		$sql .= "Amount,Comment,DateEntered,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) ";
		$sql .= "VALUES (:LedgerTypeID,:TeamID,:SchoolYearID, ";
		if ($VendorID != ""){ $sql .= ":VendorID, "; }
		if ($ProductID != "") { $sql .= ":ProductID, "; }
		//$sql .= ":Amount,:Comment,STR_TO_DATE(':DateEntered'),1,:createdby	,now(3)	,:updatedby	,now(3))";
		$sql .= ":Amount,:Comment,:DateEntered,1,:createdby	,now(3)	,:updatedby	,now(3))";
		
		$query = $dbh->prepare($sql);
		$query-> bindParam(':LedgerTypeID', $LedgerTypeID, PDO::PARAM_STR);
		$query-> bindParam(':TeamID', $TeamID, PDO::PARAM_STR);
		//$query-> bindParam(':StudentID', $StudentID, PDO::PARAM_STR);
		$query-> bindParam(':SchoolYearID', $SchoolYearIDGlobal, PDO::PARAM_STR);
		if ($VendorID != ""){
		$query-> bindParam(':VendorID', $VendorID, PDO::PARAM_STR);
		}
		
		$query-> bindParam(':Amount', $Amount, PDO::PARAM_STR);
		$query-> bindParam(':Comment', $Comment, PDO::PARAM_STR);
		$query-> bindParam(':DateEntered', $DateEntered, PDO::PARAM_STR);
		$query-> bindParam(':createdby', $_SESSION['alogin'], PDO::PARAM_STR);

		$query-> bindParam(':updatedby', $_SESSION['alogin'], PDO::PARAM_STR);

	echo $sql;

		$query->execute();
		$lastInsertId = $dbh->lastInsertId();
		if($lastInsertId)
		{
			//UPDAT TEAM BUCKETS
			$sql1 = "SELECT SUM(Amount) AS balance FROM Ledger where Status = 1 AND TeamID = (:teamid)";
			$sql2 = "SELECT SUM(Amount) AS debit FROM Ledger where Status = 1 and Amount < 0 AND TeamID = (:teamid)";
			$sql3 = "SELECT SUM(Amount) AS credit FROM Ledger where Status = 1 and Amount > 0 AND TeamID = (:teamid)";
			$query1= $dbh -> prepare($sql1);
			$query2= $dbh -> prepare($sql2);
			$query3= $dbh -> prepare($sql3);
			$query1-> bindParam(':teamid', $TeamID, PDO::PARAM_STR);
			$query2-> bindParam(':teamid', $TeamID, PDO::PARAM_STR);
			$query3-> bindParam(':teamid', $TeamID, PDO::PARAM_STR);
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

			$queryBal-> bindParam(':teamid', $TeamID, PDO::PARAM_STR);
			$queryDeb-> bindParam(':teamid', $TeamID, PDO::PARAM_STR);
			$queryCred-> bindParam(':teamid', $TeamID, PDO::PARAM_STR);

			$queryBal-> bindParam(':schoolyearid', $SchoolYearIDGlobal, PDO::PARAM_STR);
			$queryDeb-> bindParam(':schoolyearid', $SchoolYearIDGlobal, PDO::PARAM_STR);
			$queryCred-> bindParam(':schoolyearid', $SchoolYearIDGlobal, PDO::PARAM_STR);

			$queryBal-> execute();
			$queryDeb-> execute();
			$queryCred-> execute();


		echo "<script type='text/javascript'>alert('Ledger Added Sucessfully!');</script>";
		echo "<script type='text/javascript'> document.location = 'ledger.php'; </script>";
		}
		else 
		{
		$error="Something went wrong. Please try again";
		}
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
	
	<title>Add Transaction</title>

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
						<h3 class="page-title">New Ledger </h3>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">New Ledger</div>
<?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>

									<div class="panel-body">
<form method="post" class="form-horizontal" enctype="multipart/form-data" name="imgform">

<div class="form-group">
	<label class="col-sm-2 control-label">Trans Type<span style="color:red">*</span></label>
	<div class="col-sm-4">
		
	<?php
				$sqlLT = "SELECT * from LedgerType where Status = 1";
				$queryLT = $dbh -> prepare($sqlLT);
				$queryLT->execute();
				$resultLT=$queryLT->fetchAll(PDO::FETCH_OBJ);
				$cntLT=1;	
		?>
		<select name="LedgerTypeID" class="form-control" required>
            <option value="">Select</option>
		<?php
			foreach($resultLT as $resLT)
				{

					if(isset($_POST['LedgerTypeID']))
					{
						if ($_POST['LedgerTypeID'] == $resLT->LedgerTypeID)
						{
							echo "<option SELECTED value=$resLT->LedgerTypeID>$resLT->Description</option>";
						}
						else
						{
							echo "<option  value=$resLT->LedgerTypeID>$resLT->Description</option>";
						}
					}
					else
					{
						echo "<option  value=$resLT->LedgerTypeID>$resLT->Description</option>";
					}
				}
					?>
 		</select>



	</div>
		<label class="col-sm-2 control-label">Amount<span style="color:red">*</span></label>

	<div class="col-sm-4">
		<?php
		if(isset($_POST['Amount']))
			{ 
				?>
			$ <input type="number" name="Amount" class="form-control: min="0.01" step="0.01" max="250000000" value='<?php echo($_POST['Amount']) ?>'  required/>
			<?php 
			} else {
				?>
		 $ <input type="number" name="Amount" class="form-control: min="0.01" step="0.01" max="250000000" value=''  required/>
<?php } ?>

			
</div><!-- END FORM GROUP-->

	
</div>

<div class="form-group">
		<label class="col-sm-2 control-label">Vendor</label>
	<div class="col-sm-4">
		<?php
				$sqlV = "SELECT * from Vendor where Status = 1";
				$queryV = $dbh -> prepare($sqlV);
				$queryV->execute();
				$resultV=$queryV->fetchAll(PDO::FETCH_OBJ);
				$cntV=1;	
		?>
		<select name="VendorID" class="form-control" >
            <option value="">Select</option>
		<?php
			foreach($resultV as $resV)
				{
				echo "<option  value=$resV->VendorID>$resV->Name</option>";
				}
					?>
 		</select>
	</div>
		<label class="col-sm-2 control-label">Comment</label>
	<div class="col-sm-4">
		
<?php
		if(isset($_POST['Comment']))
			{ 
				?>
			
			<textarea id="Comment" name="Comment" rows="2" cols="50" ><?php echo($_POST['Comment']) ?></textarea>
			<?php 
			} else {
				?>
		<textarea id="Comment" name="Comment" rows="2" cols="50" value=""></textarea>
		<?php } ?>

	</div>
</div><!-- END FORM GROUP-->


<div class="form-group">
		
	

<label class="col-sm-2 control-label">DateEntered <span style="color:red">*</span></label>
		<div class="col-sm-4 input-group datepick">
        	<input type="text" name="DateEntered" id="DateEntered" class="form-control"  value="" required>
			<div class="input-group-addon">
      			<span class="glyphicon glyphicon-calendar"></span>
    		</div>
		</div>




</div>




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
				
				// function goPrev()
  				//{
  				//  window.history.back();
  				//}
					});
	</script>

</body>
</html>
<?php } ?>