<?php 
session_start();

include('includes/config.php');
if(strlen($_SESSION['alogin'])==0 || $_SESSION['alogin'] != 'admin')
	{	
header('location:index.php');
}
else{
	if (isset($_REQUEST["TeamID"])) {
	$TeamID = $_REQUEST['TeamID'];
	$teamid = $TeamID;
	}
	//else
	//{
	//$teamid = 1; 
	//}
 

	if(isset($_REQUEST['del']))
	{
		$Ledger_id = $_REQUEST['del'];
		$memstatus=0;
		$sql = "UPDATE Ledger SET status=:status WHERE  LedgerID =:aeid";
		$query = $dbh->prepare($sql);
		$query -> bindParam(':status',$memstatus, PDO::PARAM_STR);
		$query-> bindParam(':aeid',$Ledger_id, PDO::PARAM_STR);
		$query -> execute();

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

		$msg="Changes Sucessfully";
		header('location:ledger.php?TeamID='.$teamid);
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
	
	<title>Ledger </title>

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
th 
{ 
	white-space: nowrap; 
}

//#Ledger1{
//  border-style:solid;
//  border-color:black;
//  border-width:2px
//}

//#Ledger1 td{
//  border-style:solid;
//  border-color:black;
//  border-width:1px;
//  padding:3px;
//}

.CellWithComment{
  position:relative;
}

.CellComment{
  display:none;
  position:absolute; 
  z-index:100;
  border:1px;
  background-color:white;
  border-style:solid;
  border-width:1px;
  border-color:tan;
  padding:3px;
  color:red; 
  top:20px; 
  left:20px;
}

.CellWithComment:hover span.CellComment{
  display:block;
}

		</style>

</head>


<body>
	<?php include('includes/header.php');?>

	<div class="ts-main-content" >
		<?php include('includes/leftbar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-4">
					
					<!--<input type="text" name="TeamID" class="form-control" required value="<?php echo htmlentities($result->TeamID);?>">-->
					<?php
							$sqlT = "SELECT * from Team where ClassID = (:classid) AND Status = 1";
							$queryT = $dbh -> prepare($sqlT);
							$queryT-> bindParam(':classid', $ClassIDGlobal, PDO::PARAM_STR);
							$queryT->execute();
							$resultT=$queryT->fetchAll(PDO::FETCH_OBJ);
							$cntT=1;	
					?>
					<select name="TeamIDselect" id="TeamIDselect" class="form-control" required>
					                            <option value="">Select</option>
					<?php
						foreach($resultT as $res)
							{
								if ($teamid == $res->TeamID)
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
					</div><!--col-md-4-->
				</div><!-- ROW-->

				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default"><div class="panel-heading">List Transactions <a href="new-ledger.php?TeamID=<?php echo($teamid) ?>">New</a></div>
								<div class="panel-body">
								<?php if($error){?><div class="errorWrap" id="msgshow"><?php echo htmlentities($error); ?> </div><?php } 
								else if($msg){?><div class="succWrap" id="msgshow"><?php echo htmlentities($msg); ?> </div><?php }?>
									<table border="0" cellspacing="5" cellpadding="5">
				    				<tbody>
										<tr>
				        					<td>Start date:</td>
				        					<td><input type="text" id="min1" name="min1"></td>
				    					</tr>
				    					<tr>
				        					<td>End date:</td>
				        					<td><input type="text" id="max1" name="max1"></td>
				    					</tr>
									</tbody>
									</table>
									<table id="Ledger1" class="display table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" width="100%">
									<thead>
										<tr>
							    			<th width="10%">#</th>
											<th width="30%">Trans Type</th>
											<th width="10%">Amount</th>
											<th width="40%">DateEntered</th>
											<th width="10%">Action</th>	
										</tr>
									</thead>
									<tbody>

									<?php 
									$sqlL = "SELECT L.LedgerID, L.LedgerTypeID,LT.Description,L.TeamID,L.SchoolYearID,L.Amount,CAST(L.dateentered AS DATE) AS DateEntered, L.CreatedBy,L.CreatedDate,L.UpdatedBy, L.UpdatedDate from  Ledger AS L, LedgerType AS LT where LT.LedgerTypeID = L.LedgerTypeID AND L.Status = 1 AND L.TeamID = (:TeamID)";
									$queryL = $dbh -> prepare($sqlL);
									//$queryL-> bindParam(':TeamID', $TeamID, PDO::PARAM_STR);
									$queryL-> bindParam(':TeamID', $teamid, PDO::PARAM_STR);
									$queryL->execute();
									$resultLs=$queryL->fetchAll(PDO::FETCH_OBJ);

									$cnt=1;

									foreach($resultLs as $result)
									{				?>	
										<tr>
											<td><?php echo htmlentities($result->LedgerID);?></td>
                                            <td><?php echo htmlentities($result->Description);?></td>
											<td><?php echo htmlentities($result->Amount);?></td>
											<td>
													<?php echo htmlentities($result->DateEntered);?> 
												    
											</td>
											<td class="CellWithComment">
											<a href="edit-ledger.php?LedgerID=<?php echo $result->LedgerID;?>" onclick="return confirm('Do you want to Edit');">&nbsp; <i class="fa fa-pencil"></i></a>&nbsp;&nbsp;

											<a href="ledger.php?del=<?php echo $result->LedgerID;?>&TeamID=<?php echo htmlentities($teamid);?>" onclick="return confirm('Do you want to Delete');"><i class="fa fa-trash" style="color:red"></i></a>&nbsp;&nbsp;
											<span class="CellComment">
														<?php echo htmlentities('Created By'.$result->CreatedBy);?> <BR>
														<?php echo htmlentities('Created Date ' .$result->CreatedDate);?> <BR>
														<?php echo htmlentities('Updated By ' .$result->UpdatedBy);?> <BR>
														<?php echo htmlentities('Updated Date' .$result->UpdatedDate);?> 
													</span>
											</td>
										</tr>
										<?php } ?>
										
									</tbody>
									<tfoot>
            							<tr>
                							<th style="text-align:right">Total:</th>
											<th></th>
                							<th></th>
											<th></th>
											<th></th>
            							</tr>
        							</tfoot>
								</table>
								</div><!-- panel-body -->
						</div><!--panel panel-default -->
					</div><!--col-md-12"-->
				</div> <!-- row -->
			<!--</div>ROW-->

			

						<?php 
									//Start Up Capital Value
									$sql1 = "SELECT L.LedgerID, L.LedgerTypeID,LT.Description,L.TeamID,L.SchoolYearID,L.Amount,CAST(L.dateentered AS DATE) AS DateEntered from  Ledger AS L, LedgerType AS LT where LT.LedgerTypeID = L.LedgerTypeID AND L.Status = 1 AND L.TeamID = (:TeamID) AND L.LedgerTypeID = 15";
									$query1 = $dbh -> prepare($sql1);
									$query1-> bindParam(':TeamID', $teamid, PDO::PARAM_STR);
									$query1->execute();
									$result1=$query1->fetchAll(PDO::FETCH_OBJ);
						
									//Wire Amounts
									$sql2 = "SELECT L.LedgerID, L.LedgerTypeID,LT.Description,L.TeamID,L.SchoolYearID,L.Amount,CAST(L.dateentered AS DATE) AS DateEntered from  Ledger AS L, LedgerType AS LT where LT.LedgerTypeID = L.LedgerTypeID AND L.Status = 1 AND L.TeamID = (:TeamID) AND L.LedgerTypeID = 16";
									$query2 = $dbh -> prepare($sql2);
									$query2-> bindParam(':TeamID', $teamid, PDO::PARAM_STR);
									$query2->execute();
									$result2=$query2->fetchAll(PDO::FETCH_OBJ);

									//AMOUNT LEFT
									$sql3 = "SELECT SUM(L.AMOUNT) AS Sum FROM Ledger AS L where L.Status = 1 AND L.TeamID = (:TeamID)";
									$query3 = $dbh -> prepare($sql3);
									$query3-> bindParam(':TeamID', $teamid, PDO::PARAM_STR);
									$query3->execute();
									$result3=$query3->fetchAll(PDO::FETCH_OBJ);

									//Product
									$sql4 = "SELECT * from  Product where TeamID = (:TeamID) AND Status = 1";
									$query4 = $dbh -> prepare($sql4);
									$query4-> bindParam(':TeamID', $teamid, PDO::PARAM_STR);
									$query4->execute();
									$result4=$query4->fetchAll(PDO::FETCH_OBJ);

									//Monthly AVG\
									$sql5 = "SELECT AVG(L.Amount) AS AVG,month(L.dateentered) AS MONTH,L.dateentered from Ledger AS L WHERE  L.TeamID = (:TeamID) and L.Amount <> 0  group by month(L.dateentered) ORDER BY month(L.dateentered) asc";
									$query5 = $dbh -> prepare($sql5);
									$query5-> bindParam(':TeamID', $teamid, PDO::PARAM_STR);
									$query5->execute();
									$result5=$query5->fetchAll(PDO::FETCH_OBJ);
?>
			<div class="row">
				<div class="col-md-4">
					<div class="panel panel-default"><!--<div class="panel-heading">List Transactions </div>-->
						<div class="panel-body">	<div class="panel-heading">Investments </div>
						<table id="Ledger1" class="display table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" width="40%">
									<thead>
										<tr>
							    			
											<th>Trans Type</th>
											<th>Amount</th>
											<th>DateEntered</th>	
										</tr>
									</thead>
									<tbody>
							<?php
							        
									foreach($result1 as $res1)
									{
									echo('<TR>');
									echo( '<td>'.$res1->Description . ':</td>  <TD>$' . $res1->Amount . '</td><TD>' . $res1->DateEntered . '</TD>');
									echo('</tr>');
									}
									$cnt = 1;
									
									foreach($result2 as $res2)
									{
									echo('<TR>');
									echo( '<TD>'.$res2->Description . '#'.$cnt.':</TD><TD>  $' . $res2->Amount . ' </TD><TD>' . $res2->DateEntered . '</TD>');
									echo('</tr>');
									$cnt++;
									}
									echo('</tbody><tfoot>');
            							
									foreach($result3 as $res3)
									{
									echo('<TR>');
									echo('<TH>Remaining:  </TH><TH>$' . $res3->Sum . '</TH><TH></TH>');
									echo('</tr>');
									}
							?>
									</tfoot>
						</table>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="panel panel-default"><!--<div class="panel-heading">List Transactions </div>-->
						<div class="panel-body">	<div class="panel-heading">Monthly Average </div>
						<table id="Ledger1" class="display table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" width="40%">
									<thead>
										<tr>
											<th width="10%">Month</th>
											<th width="20%">Average</th>	
										</tr>
									</thead>
									<tbody>
							<?php
							        
									foreach($result5 as $res5)
									{
									echo('<TR>');
										echo('<TD>'. $res5->MONTH . ':</td>  <TD>$' . number_format((float)$res5->AVG, 2, '.', '')  . '</TD>');
									echo('</TR>');
									}
									
							?>
							</tbody>
						</table>
						</div>
					</div>
				</div>
				<div class="col-md-5">
					<div class="panel panel-default"><!--<div class="panel-heading">List Transactions </div>-->
						<div class="panel-body"><div class="panel-heading">Product Details </div>
						<table id="Ledger1" class="display table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" width="40%">
									<thead>
										<tr>
											<th>Description</th>
											<th>Amount</th>	
										</tr>
									</thead>
									<tbody>
							<?php
									foreach($result4 as $res4)
									{
									echo('<TR>');
									echo('<TD>ProductName:</TD><TD>' . $res4->ProductName . '</TD>');
									echo('</TR>');
									echo('<TR>');
									echo('<TD>RetailPrice:</TD><TD>$' . $res4->RetailPrice . '</TD>');
									echo('</TR>');
									echo('<TR>');
									echo('<TD>WholeSalePrice:</TD><TD>$' . $res4->WholeSalePrice . '</TD>');
									echo('</TR>');
									echo('<TR>');
									echo('<TD>Input Cost:</TD><TD>' . $res4->InputCost . '</TD>');
									echo('</TR>');
									echo('<TR>');
									echo('<TD>Retail Sold:</TD><TD>' . $res4->QtySoldRetail . '</TD>');
									echo('</TR>');
									echo('<TR>');
									echo('<TD>Wholesale Sold:</TD><TD>' . $res4->QtySoldWholesale . '</TD>');
									echo('</TR>');
									echo('<TR>');
									echo('<TD>Potential Sold:</TD><TD>' . $res4->NumberofPotentialCustomers . '</TD>');
									echo('</TR>');
									echo('<TR>');
									?>
									<td colspan=2><img src="../../images/<?php echo htmlentities($res4->image);?>" style="width:100px; border-radius:50%;"/></td>
									</TR>
									<?php
									}
							?>
							</tbody>
						</table>
						</div><!--panel-body-->
					</div><!--panel-default-->
				</div><!--col-md-6-->
			</div><!--row-->




		</div>
	</div>

	<!-- Loading Scripts -->

	<!--<script src="js/jquery.min.js"></script> <!--v1.10.2
	<script src="js/bootstrap-select.min.js"></script>-->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
	<!--<script src="js/bootstrap.min.js"></script>-->
	<!--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>-->
<!--<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>-->
	<!--<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>-->

<link href="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.3.2/datatables.min.css" rel="stylesheet" integrity="sha384-dG72sN6C6+JA9moN/5eRa0GqXlYOpTivxgRRV4rTctUeb4ZNF6uuJ5NXmz+8+3Qi" crossorigin="anonymous">
 <link href="https://cdn.datatables.net/datetime/1.5.5/css/dataTables.dateTime.min.css" rel="stylesheet" crossorigin="anonymous">
 <link href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" rel="stylesheet" crossorigin="anonymous">
<link href="https://cdn.datatables.net/buttons/3.2.3/css/buttons.dataTables.css" rel="stylesheet" crossorigin="anonymous">

<script src="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.3.2/datatables.min.js" integrity="sha384-qLLX0jMaWXMZrun5/ry13tv5MX78CJNleGaaJVXRuJCDiAwyjhYWsTM3Qk3VaKC3" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"  crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/datetime/1.5.5/js/dataTables.dateTime.min.js"   crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/buttons/3.2.3/js/dataTables.buttons.js"   crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.dataTables.js"   crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"   crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"   crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"   crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.html5.min.js"   crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.print.min.js"   crossorigin="anonymous"></script>

	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>
	<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js">
	<script type="text/javascript">
				 $(document).ready(function () {          
							setTimeout(function() {
							$('.succWrap').slideUp("slow");
							}, 3000);



					});//$(document).ready
		</script>
</body>
</html>
<?php } ?>
