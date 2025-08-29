<?php
session_start();

include('includes/config.php');
//if(strlen($_SESSION['alogin'])==0)
if(strlen($_SESSION['alogin'])==0 || $_SESSION['alogin'] != 'admin') 
	{	
header('location:index.php');
}
else{
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
	
	<title>Admin Dashboard</title>

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

						<h2 class="page-title">Dashboard</h2>
						
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-3">
										<div class="panel panel-default">
											<div class="panel-body bk-primary text-light">
												<div class="stat-panel text-center">
												<?php 
												$sql ="SELECT id from users WHERE classid = (:classid)";
												$query = $dbh -> prepare($sql);
												$query-> bindParam(':classid', $ClassIDGlobal, PDO::PARAM_STR);
												$query->execute();
												$results=$query->fetchAll(PDO::FETCH_OBJ);
												$bg=$query->rowCount();
												?>
													<div class="stat-panel-number h1 "><?php echo htmlentities($bg);?></div>
													<div class="stat-panel-title text-uppercase">Total Users</div>
												</div>
											</div>
											<a href="userlist.php" class="block-anchor panel-footer">Full Detail <i class="fa fa-arrow-right"></i></a>
										</div>
									</div>
									<div class="col-md-3">
										<div class="panel panel-default">
											<div class="panel-body bk-success text-light">
												<div class="stat-panel text-center">
													<?php 
													$reciver = 'Admin';
													$sql1 ="SELECT id from feedback where reciver = (:reciver) AND classid = (:classid)";
													$query1 = $dbh -> prepare($sql1);;
													$query1-> bindParam(':reciver', $reciver, PDO::PARAM_STR);
													$query1-> bindParam(':classid', $ClassIDGlobal, PDO::PARAM_STR);
													$query1->execute();
													$results1=$query1->fetchAll(PDO::FETCH_OBJ);
													$regbd=$query1->rowCount();
													?>
													<div class="stat-panel-number h1 "><?php echo htmlentities($regbd);?></div>
													<div class="stat-panel-title text-uppercase">Feedback Messages</div>
												</div>
											</div>
											<a href="feedback.php" class="block-anchor panel-footer text-center">Full Detail &nbsp; <i class="fa fa-arrow-right"></i></a>
										</div>
									</div>

									<div class="col-md-3">
										<div class="panel panel-default">
											<div class="panel-body bk-danger text-light">
												<div class="stat-panel text-center">
													<?php 
													$reciver = 'Admin';
													$sql12 ="SELECT id from notification where notireciver = (:reciver) AND classid = (:classid)";
													$query12 = $dbh -> prepare($sql12);;
													$query12-> bindParam(':reciver', $reciver, PDO::PARAM_STR);
													$query12-> bindParam(':classid', $ClassIDGlobal, PDO::PARAM_STR);
													$query12->execute();
													$results12=$query12->fetchAll(PDO::FETCH_OBJ);
													$regbd2=$query12->rowCount();
													?>
													<div class="stat-panel-number h1 "><?php echo htmlentities($regbd2);?></div>
													<div class="stat-panel-title text-uppercase">Notifications</div>
												</div>
											</div>
											<a href="notification.php" class="block-anchor panel-footer text-center">Full Detail &nbsp; <i class="fa fa-arrow-right"></i></a>
										</div>
									</div>
									
									<div class="col-md-3">
										<div class="panel panel-default">
											<div class="panel-body bk-info text-light">
												<div class="stat-panel text-center">
												<?php 
												$sql6 ="SELECT id from deleteduser ";
												$query6 = $dbh -> prepare($sql6);;
												$query6->execute();
												$results6=$query6->fetchAll(PDO::FETCH_OBJ);
												$query=$query6->rowCount();
												?>
													<div class="stat-panel-number h1 "><?php echo htmlentities($query);?></div>
													<div class="stat-panel-title text-uppercase">Deleted Users</div>
												</div>
											</div>
											<a href="deleteduser.php" class="block-anchor panel-footer text-center">Full Detail &nbsp; <i class="fa fa-arrow-right"></i></a>
										</div>
									</div>
							
								</DIV>
							</div>
						</DIV>

						<?php
							$sqlT = "SELECT * from Team where ClassID = (:classid) AND Status = 1";
							$queryT = $dbh -> prepare($sqlT);
							$queryT-> bindParam(':classid', $ClassIDGlobal, PDO::PARAM_STR);
							$queryT->execute();
							$resultT=$queryT->fetchAll(PDO::FETCH_OBJ);
							$cntT=1;	
						?>

						<DIV class="row">
							<div class="col-md-12">
								<DIV class="row">
								<?php 
								$x = 1;
								foreach($resultT as $res)
								{
									$sqlP = "SELECT ProductID,ProductName,Description,RetailPrice,WholeSalePrice,QtySoldRetail,QtySoldWholesale,NumberofPotentialCustomers,InputCost,image,TeamID,StudentID,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate FROM Product WHERE TeamID = (:TeamID)";
        							$queryP = $dbh -> prepare($sqlP);
        							$queryP-> bindParam(':TeamID', $res->TeamID, PDO::PARAM_STR);
        							$queryP->execute();
        							$resultP=$queryP->fetch(PDO::FETCH_OBJ);

									$teamid = $res->TeamID;
									if ($x == 5)
									{
									?>
									</DIV><DIV class="row">
									<?php 
									} ?>
									<div class="col-md-3">
										<div class="panel panel-default">
											<div class="stat-panel-number h3 text-center"><?php echo htmlentities($res->TeamName);?> </div>
												<div class="panel-body bk-primary text-light">
													<div class="stat-panel text-left">
													<?php 
													if($queryP->rowCount() > 0)
													{

														//AMOUNT LEFT
														$remaining = 0;
														$sql3 = "SELECT SUM(L.AMOUNT) AS Sum FROM Ledger AS L where L.Status = 1 AND L.TeamID = (:TeamID)";
														$query4 = $dbh -> prepare($sql3);
														$query4-> bindParam(':TeamID', $teamid, PDO::PARAM_STR);
														$query4->execute();
														$result3=$query4->fetch(PDO::FETCH_OBJ);
														$remaining = $result3->Sum;

													//	echo ('Remaining Amount = ' . $remaining. '<BR>');
													//	echo ('$teamid = ' . $teamid. '<BR>');
													//	echo ('SQL = ' . $sql3 . '<BR>');

													$p1 = "SELECT SUM(Amount) AS P1 FROM  Ledger where TeamID = :teamid AND LedgerTypeID = 7 AND Status = 1  AND MONTH(dateentered) IN (8,9,10,11,12)";
													$p2 = "SELECT SUM(Amount) AS P2 FROM Ledger where TeamID = :teamid AND LedgerTypeID = 7 AND  Status = 1 AND  MONTH(dateentered) IN (1,2,3)";
													$p3 = "SELECT SUM(Amount) AS P3 FROM  Ledger where TeamID = :teamid AND LedgerTypeID = 7 AND Status = 1  AND MONTH(dateentered) IN (4,5,6)";
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
													if ($P1Amt == null)
													{
														$P1Amt = 0;
													}
													if ($P2Amt == null)
													{
														$P2Amt = 0;
													}
													if ($P3Amt == null)
													{
														$P3Amt = 0;
													}
												//	echo('P1Amt = ' . $P1Amt . '<BR>');
												//	echo('P2Amt = ' . $P2Amt . '<BR>');
												//	echo('P3Amt = ' . $P3Amt . '<BR>');
													//$teamid = $res->TeamID;
													
												?>
													<table class=" table  " cellspacing="0" width="100%">
													<TR><TD>Retail Margin: </TD><TD><?php echo htmlentities('$'.$resultP->RetailPrice - $resultP->InputCost );?></td></tr>
													<TR><TD>Wholesale Margins: </TD><TD><?php echo htmlentities('$'.$resultP->WholeSalePrice - $resultP->InputCost );?></td></tr>
													<TR><TD>Retail Total: </TD><TD><?php echo htmlentities('$'.($resultP->RetailPrice - $resultP->InputCost ) * ($resultP->QtySoldRetail)  );?></td></tr>
													<TR><TD>Wholesale Total: </TD><TD><?php echo htmlentities('$'.($resultP->WholeSalePrice - $resultP->InputCost ) * ($resultP->QtySoldWholesale)  );?></td></tr>
													<TR><TD>Sale Inpuiry: </TD><TD><?php echo htmlentities('$'.(($resultP->WholeSalePrice - $resultP->InputCost) / (2) ) * ($resultP->NumberofPotentialCustomers)  );?></td></tr>
													<TR><TD>Total Order Input: </TD><TD><?php echo htmlentities('$'.(($resultP->RetailPrice - $resultP->InputCost ) * ($resultP->QtySoldRetail)) + (($resultP->WholeSalePrice - $resultP->InputCost ) * ($resultP->QtySoldWholesale)) + ((($resultP->WholeSalePrice - $resultP->InputCost) / (2) ) * ($resultP->NumberofPotentialCustomers))  );?></td></tr>
												</table>
												<?php
										

												//	echo('RetailPrice =' . $resultP->RetailPrice . '<BR>');
												//	echo('InputCost =' . $resultP->InputCost . '<BR>');
												//	echo('QtySoldRetail =' . $resultP->QtySoldRetail . '<BR>');
												//	echo('WholeSalePrice =' . $resultP->WholeSalePrice . '<BR>');
												//	echo('InputCost =' . $resultP->InputCost . '<BR>');
												//	echo('QtySoldWholesale =' . $resultP->QtySoldWholesale . '<BR>');
												//	echo('WholeSalePrice =' . $resultP->WholeSalePrice . '<BR>');
												//	echo('InputCost =' . $resultP->InputCost . '<BR>');
												//	echo('NumberofPotentialCustomers =' . $resultP->NumberofPotentialCustomers . '<BR>');

												$V11= (($resultP->RetailPrice - $resultP->InputCost ) * ($resultP->QtySoldRetail)) + (($resultP->WholeSalePrice - $resultP->InputCost ) * ($resultP->QtySoldWholesale)) + ((($resultP->WholeSalePrice - $resultP->InputCost) / (2) ) * ($resultP->NumberofPotentialCustomers));  
												$S28 = ($V11 * -321);
												$S32 = (28 * -1234);
												
												//echo('$V11 = ' . $V11 .  '<BR>');
												//echo('$S28 = ' . $S28 .  '<BR>');
												//echo('$S32 = ' . $S32 .  '<BR>');

												$TotalValue = (($P1Amt * 72.5) + ($P2Amt * 55.1) + ($P3Amt * 20) + ($S28 * 80) + ($S32 * 60));
												//echo ( 'MARKETING TOTAL = ' . ($P1Amt + $P2Amt + $P3Amt));
												}
												//We must have some value from LedgerType Marketing transactions. If we don't then we give sum of Ledger Table for that team
												if ($TotalValue <> 0 && (($P1Amt + $P2Amt + $P3Amt) <> 0 ))
												{
												?>
													<div class="stat-panel-title text-uppercase">Company Value: <?php echo htmlentities('$'. number_format(abs($TotalValue), 2, '.', ',') );?> 
												<?php 
												} else {											
												?>	
													<div class="stat-panel-title text-uppercase">Company Value: <?php echo htmlentities('$'. $remaining );?> 
												<?php
												}												
												?>

													<?php
											$sql1 = "SELECT L.LedgerID, L.LedgerTypeID,LT.Description,L.TeamID,L.SchoolYearID,L.Amount,CAST(L.dateentered AS DATE) AS DateEntered from  Ledger AS L, LedgerType AS LT where LT.LedgerTypeID = L.LedgerTypeID AND L.Status = 1 AND L.TeamID = (:TeamID) AND L.LedgerTypeID = 15";
											$query2 = $dbh -> prepare($sql1);
											$query2-> bindParam(':TeamID', $teamid, PDO::PARAM_STR);
											$query2->execute();
											$result1=$query2->fetchAll(PDO::FETCH_OBJ);

											//Wire Amounts
											$sql2 = "SELECT L.LedgerID, L.LedgerTypeID,LT.Description,L.TeamID,L.SchoolYearID,L.Amount,CAST(L.dateentered AS DATE) AS DateEntered from  Ledger AS L, LedgerType AS LT where LT.LedgerTypeID = L.LedgerTypeID AND L.Status = 1 AND L.TeamID = (:TeamID) AND L.LedgerTypeID = 16";
											$query3 = $dbh -> prepare($sql2);
											$query3-> bindParam(':TeamID', $teamid, PDO::PARAM_STR);
											$query3->execute();
											$result2=$query3->fetchAll(PDO::FETCH_OBJ);
											//Get Total Wire Amounts
											$TotalInvested = 0;
											foreach($result2 as $res2)
											{
												$TotalInvested += $res2->Amount;
											}

				//							//AMOUNT LEFT
				//							$sql3 = "SELECT SUM(L.AMOUNT) AS Sum FROM Ledger AS L where L.Status = 1 AND L.TeamID = (:TeamID)";
				//							$query4 = $dbh -> prepare($sql3);
				//							$query4-> bindParam(':TeamID', $teamid, PDO::PARAM_STR);
				//							$query4->execute();
				//							$result3=$query4->fetchAll(PDO::FETCH_OBJ);

											$DealExists = false;
											$sql4 = "SELECT D.DealID,D.DealName,D.PercentOwned,D.TotalInvested,S.SharkName ";
											$sql4 .= "FROM Deal AS D, Shark AS S WHERE D.SharkID = S.SharkID AND D.Status = 1 AND D.TeamID = (:teamid)";
											$query5 = $dbh -> prepare($sql4);
											$query5-> bindParam(':teamid', $teamid, PDO::PARAM_STR);
											$query5->execute();
											$result4=$query5->fetch(PDO::FETCH_OBJ);
											if($query5->rowCount() > 0)
												{
													$DealExists = true;
												}
											?>
											<?php
											$teamid = null;
											if ($DealExists == true)
											{ ?>
											<div class="stat-panel-number h3 text-center"> SHARK <?php echo($result4->SharkName ); ?> </div>
											<?php
											} else { ?>
											<div class="stat-panel-number h3 text-center"> NO DEAL YET </div>
											<?php
											}
											?>
											
											<table id="Ledger1" class="display table  table-bordered " cellpadding="0" cellspacing="0" width="40%">
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
											//echo('<TH>Remaining:  </TH><TH>$' . $res3->Sum . '</TH><TH></TH>');
											echo('<TH>Remaining:  </TH><TH>$' .$remaining. '</TH><TH></TH>');
											echo('</tr>');
											}
											?>
											</tfoot>
											</table>
											<?php
											if ($DealExists == true)
											{ ?>
											<table id="Ledger1" class="display table  table-bordered " cellpadding="0" cellspacing="0" width="40%">
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
											</div>
										</div>
									</DIV> <!--col-md-3-->
									<?php
									//if ($x == 8)
									//{
									//	echo ('</DIV>');
									//}
									$x++;
									$TotalValue = null;
									$remaining = null;
									$P1Amt = null;
									$P2Amt = null;
									$P3Amt = null;
									$V11 = null;
									$S28 = null;
									$S32 = null;
								}
								?>
									
								</div>
						</DIV>

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
	
	<script>
		
	window.onload = function(){
    
		// Line chart from swirlData for dashReport
		var ctx = document.getElementById("dashReport").getContext("2d");
		window.myLine = new Chart(ctx).Line(swirlData, {
			responsive: true,
			scaleShowVerticalLines: false,
			scaleBeginAtZero : true,
			multiTooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>",
		}); 
		
		// Pie Chart from doughutData
		var doctx = document.getElementById("chart-area3").getContext("2d");
		window.myDoughnut = new Chart(doctx).Pie(doughnutData, {responsive : true});

		// Dougnut Chart from doughnutData
		var doctx = document.getElementById("chart-area4").getContext("2d");
		window.myDoughnut = new Chart(doctx).Doughnut(doughnutData, {responsive : true});

	}
	</script>
</body>
</html>
<?php 
$query = null;
$query1 = null;
$query12 = null;
$query6 = null;
$queryT = null;
$queryP = null;

$queryP1 = null;
$queryP2 = null;
$queryP3 = null;

$query2 = null;
$query3 = null;
$query4 = null;
$query5 = null;

include('includes/close.php');
} ?>