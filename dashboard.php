<?php
session_start();

include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
	{	
header('location:index.php');
}
else{
$sqlU ="SELECT TeamID from users where email = (:email)";
$queryU = $dbh -> prepare($sqlU);;
$queryU-> bindParam(':email', $_SESSION['alogin'], PDO::PARAM_STR);
$queryU->execute();
$resultsU=$queryU->fetch(PDO::FETCH_OBJ);

$teamid = $resultsU->TeamID;


$sqlT ="SELECT TeamID,name,email,gender,mobile,designation,image from users where teamid = (:teamid) and status = 1";
$queryUT = $dbh -> prepare($sqlT);;
$queryUT-> bindParam(':teamid', $teamid, PDO::PARAM_STR);
$queryUT->execute();
$resultsUT=$queryUT->fetchall(PDO::FETCH_OBJ);

$sqlTeam = "SELECT TeamName FROM Team WHERE TeamID = (:teamid)";
$queryTT = $dbh -> prepare($sqlTeam);;
$queryTT-> bindParam(':teamid', $teamid, PDO::PARAM_STR);
$queryTT->execute();
$resultsTT=$queryTT->fetch(PDO::FETCH_OBJ);
$teamname = $resultsTT->TeamName;


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
	
	<title>Student Dashboard</title>

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
</head>

<body>
<?php include('includes/header.php');?>

	<div class="ts-main-content">
<?php include('includes/leftbar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">

				<div class="row">
					<div class="col-md-12">

						<h2 class="page-title">Dashboard <?php echo ($teamname); ?></h2>
						
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<?php
									foreach($resultsUT as $resUT)
									{
									?>
									<div class="col-md-4">
										<div class="panel panel-default">
											<div class="panel-body bk-primary text-light">
												<div class="stat-panel text-center">
													<div class="stat-panel-number h5 "><?php echo($resUT->name); ?></DIV>
													<DIV><img src="../images/<?php echo($resUT->image); ?>" width="100" height="100"/></DIV>
												</div>
											</div>
											<a href="userlist.php" class="block-anchor panel-footer">Full Detail <i class="fa fa-arrow-right"></i></a>
										</div>
									</div>
									<?php } ?>
									
							
								</DIV>
							</div>
						</DIV>

						<?php
							$sqlT = "SELECT * from Team where TeamID = (:teamid) AND Status = 1";
							$queryT = $dbh -> prepare($sqlT);
							$queryT-> bindParam(':teamid', $teamid, PDO::PARAM_STR);
							$queryT->execute();
							$resultT=$queryT->fetchAll(PDO::FETCH_OBJ);
							$cntT=1;	
						?>

						<DIV class="row">
							<div class="col-md-12">
								<DIV class="row">
								<?php 
								
								$bk[1] = 'bk-success text-dark';
								$bk[2] = 'bk-primary text-light';
								
								$bk[3] = 'bk-info text-light';
								$bk[4] = 'bk-primary text-light';
								$bk[5] = 'bk-warning text-light';
								$bk[6] = 'bk-danger text-light';
								$bk[7] = 'bk-success text-dark';
								$bk[8] = 'bk-warning text-light';
								
								$x = 1;
								foreach($resultT as $res)
								{
									$sqlP = "SELECT ProductID,ProductName,Description,RetailPrice,WholeSalePrice,QtySoldRetail,QtySoldWholesale,NumberofPotentialCustomers,InputCost,image,TeamID,StudentID,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate FROM Product WHERE TeamID = (:TeamID)";
        							$queryP = $dbh -> prepare($sqlP);
        							$queryP-> bindParam(':TeamID', $res->TeamID, PDO::PARAM_STR);
        							$queryP->execute();
        							$resultP=$queryP->fetch(PDO::FETCH_OBJ);
								
								?>
									<div class="col-md-4">
										<div class="panel panel-default">
											<div class="stat-panel-number h3 text-center">Company Output </div>
												<div class="panel-body ">
												
												<div class="stat-panel text-left">
												<?php 											
												if($queryP->rowCount() > 0)
												{
													$p1 = "SELECT SUM(Amount) AS P1 FROM  Ledger where TeamID = :teamid AND LedgerTypeID = 7 AND MONTH(dateentered) IN (8,9,10,11,12)";
													$p2 = "SELECT SUM(Amount) AS P2 FROM Ledger where TeamID = :teamid AND LedgerTypeID = 7 AND MONTH(dateentered) IN (1,2,3)";
													$p3 = "SELECT SUM(Amount) AS P3 FROM  Ledger where TeamID = :teamid AND LedgerTypeID = 7 AND MONTH(dateentered) IN (4,5,6)";
													$queryP1 = $dbh -> prepare($p1);
													$queryP2 = $dbh -> prepare($p2);
													$queryP3 = $dbh -> prepare($p3);
													$queryP1-> bindParam(':teamid', $res->TeamID, PDO::PARAM_STR);
													$queryP2-> bindParam(':teamid', $res->TeamID, PDO::PARAM_STR);
													$queryP3-> bindParam(':teamid', $res->TeamID, PDO::PARAM_STR);
													$queryP1->execute();
													$queryP2->execute();
													$queryP3->execute();
													$resultP1=$queryP1->fetch(PDO::FETCH_OBJ);
													$resultP2=$queryP2->fetch(PDO::FETCH_OBJ);
													$resultP3=$queryP3->fetch(PDO::FETCH_OBJ);
													$P1Amt = $resultP1->P1;
													$P2Amt = $resultP2->P2;
													$P3Amt = $resultP3->P3;
												?> 
													<table  class="display table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" width="80%">
													<TR><TD>Retail Margin: </TD><TD><?php echo htmlentities('$'.$resultP->RetailPrice - $resultP->InputCost );?></td></tr>
													<TR><TD>Wholesale Margins: </TD><TD><?php echo htmlentities('$'.$resultP->WholeSalePrice - $resultP->InputCost );?></td></tr>
													<TR><TD>Retail Total: </TD><TD><?php echo htmlentities('$'.($resultP->RetailPrice - $resultP->InputCost ) * ($resultP->QtySoldRetail)  );?></td></tr>
													<TR><TD>Wholesale Total: </TD><TD><?php echo htmlentities('$'.($resultP->WholeSalePrice - $resultP->InputCost ) * ($resultP->QtySoldWholesale)  );?></td></tr>
													<TR><TD>Sale Inpuiry: </TD><TD><?php echo htmlentities('$'.(($resultP->WholeSalePrice - $resultP->InputCost) / (2) ) * ($resultP->NumberofPotentialCustomers)  );?></td></tr>
													<TR><TD>Total Order Input: </TD><TD><?php echo htmlentities('$'.(($resultP->RetailPrice - $resultP->InputCost ) * ($resultP->QtySoldRetail)) + (($resultP->WholeSalePrice - $resultP->InputCost ) * ($resultP->QtySoldWholesale)) + ((($resultP->WholeSalePrice - $resultP->InputCost) / (2) ) * ($resultP->NumberofPotentialCustomers))  );?></td></tr>
												</table>
												<?php
												$V11= (($resultP->RetailPrice - $resultP->InputCost ) * ($resultP->QtySoldRetail)) + (($resultP->WholeSalePrice - $resultP->InputCost ) * ($resultP->QtySoldWholesale)) + ((($resultP->WholeSalePrice - $resultP->InputCost) / (2) ) * ($resultP->NumberofPotentialCustomers));  
												$S28 = ($V11 * -321);
												$S32 = (28 * -1234);
												$TotalValue = (($P1Amt * 72.5) + ($P2Amt * 55.1) + ($P3Amt * 20) + ($S28 * 80) + ($S32 * 60));
												}
												?>
													<div class="stat-panel-title text-uppercase"><strong>Company Value: <?php echo htmlentities('$'. number_format(abs($TotalValue), 2, '.', ',') );?></strong></div>  
												</div>
											</div>
										</div>
									</div>
									<?php
									//$sql5 = "SELECT AVG(L.Amount) AS AVG,month(L.dateentered) AS MONTH,L.dateentered from Ledger AS L WHERE  L.TeamID = (:TeamID) and L.Amount <> 0  group by month(L.dateentered) ORDER BY month(L.dateentered) asc";
									$sql5 = "SELECT AVG(L.Amount) AS AVG,week(L.dateentered) AS WEEK,L.dateentered from Ledger AS L WHERE  L.TeamID = (:TeamID) and L.Amount < 0  group by week(L.dateentered) ORDER BY L.dateentered asc";
									$query5 = $dbh -> prepare($sql5);
									$query5-> bindParam(':TeamID', $teamid, PDO::PARAM_STR);
									$query5->execute();
									$result5=$query5->fetchAll(PDO::FETCH_OBJ);
									?>
										<div class="col-md-4">
											<div class="panel panel-default">
												<div class="stat-panel-number h3 text-center">Weekly AVG Expense</div>
												<div class="stat-panel text-left">
												<table id="LedgerAVG" class="display table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" width="80%">
													<thead>
													<tr>
														<th width="10%">Week Date</th>
														<th width="20%">Average Expenses</th>	
													</tr>
													</thead>
													<tbody>
															<?php

																	foreach($result5 as $res5)
																	{
																	echo('<TR>');
																	$d = new DateTime($res5->dateentered);
																		echo('<TD>'. $d->format('m-d-Y') . ':</td>');
																		echo('<TD>$' . number_format((float)$res5->AVG, 2, '.', '')  . '</TD>');
																	echo('</TR>');
																	}

															?>
													</tbody>
													
												</table>	
												</div>
											</DIV>
										</DIV>
									

									<div class="col-md-4">
										<div class="panel panel-default">
											
											<?php
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
											//Get Total Wire Amounts
											$TotalInvested = 0;
											foreach($result2 as $res2)
											{
												$TotalInvested += $res2->Amount;
											}

											//AMOUNT LEFT
											$sql3 = "SELECT SUM(L.AMOUNT) AS Sum FROM Ledger AS L where L.Status = 1 AND L.TeamID = (:TeamID)";
											$query3 = $dbh -> prepare($sql3);
											$query3-> bindParam(':TeamID', $teamid, PDO::PARAM_STR);
											$query3->execute();
											$result3=$query3->fetchAll(PDO::FETCH_OBJ);

											$DealExists = false;
											$sql4 = "SELECT D.DealID,D.DealName,D.PercentOwned,D.TotalInvested,S.SharkName ";
											$sql4 .= "FROM Deal AS D, Shark AS S WHERE D.SharkID = S.SharkID AND D.Status = 1 AND D.TeamID = (:teamid)";
											$query4 = $dbh -> prepare($sql4);
											$query4-> bindParam(':teamid', $teamid, PDO::PARAM_STR);
											$query4->execute();
											$result4=$query4->fetch(PDO::FETCH_OBJ);
											if($query4->rowCount() > 0)
												{
													$DealExists = true;
												}
											?>
											<?php
											if ($DealExists == true)
											{ ?>
											<div class="stat-panel-number h3 text-center"> SHARK <?php echo($result4->SharkName ); ?> </div>
											<?php
											} ?>
												
											
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
											echo( '<td>'.$res1->Description . ':</td>  <TD>$' . $res1->Amount . '</td>');
											$d = new DateTime($res1->DateEntered);
											echo('<TD>'. $d->format('m-d-Y') . ':</td>');
											echo('</tr>');
											}
											$cnt = 1;

											foreach($result2 as $res2)
											{
											echo('<TR>');
											echo( '<TD>'.$res2->Description . '#'.$cnt.':</TD><TD>  $' . $res2->Amount . ' </TD>');
											$d = new DateTime($res2->DateEntered);
											echo('<TD>' . $d->format('m-d-Y') . '</TD>');
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
											<?php
											if ($DealExists == true)
											{ ?>
											<table id="Ledger1" class="display table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" width="40%">
											<thead>
												<thead>
												<tr><TH></TH><TH></TH></TR>
												</thead>
											<tbody>
													<td>Shark Total Invested</td>
													<td>$<?php echo($result4->TotalInvested); ?> </td> 
												</tr>
												<tr>
													<td>Shark Company Percent Owned</td>
													<td><?php echo($result4->PercentOwned); ?>%</td> 
												</tr>
												<tr>
													<td>Shark Project ROI</td>
													<td><?php echo htmlentities('$'. number_format(abs(($TotalValue * (floatval($result4->PercentOwned) / 100.00) ) - ($result4->TotalInvested)), 2, '.', ',') );?></td> 
												</tr>
										</tbody>
										</table>
											<?php
											}
											?>
											
										</div>
									</div>
									<?php
									$x++;
								}
								?>

								</DIV>
							</div>
						</DIV>

					</div>
				</div>
			</div>
		</div>
	</div>
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
	<!-- Loading Scripts -->
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>

	
	<script>
		
	 $(document).ready(function () { 
		
new DataTable('#LedgerAVG', {
    info: false,
    ordering: true,
    paging: true,
	 order: [[3, 'asc']],
	lengthMenu: [10, 25, 50, -1]
	
    // ,lengthMenu: [
    //    [10, 25, 50, "All"]
    //]
});    
});    

	
	</script>
</body>
</html>
<?php  ?>