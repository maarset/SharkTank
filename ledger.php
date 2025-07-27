<?php
session_start();

include('includes/config.php');
if(strlen($_SESSION['alogin'])==0) 
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
	<?php
$email = $_SESSION['alogin'];
$sqlT = "SELECT U.TeamID AS TeamID,U.designation,T.TeamName AS TeamName FROM users AS U, Team AS T WHERE U.TeamID = T.TeamID AND U.email = (:email) AND T.ClassID = (:classid)";
$queryT = $dbh -> prepare($sqlT);
$queryT-> bindParam(':email', $email, PDO::PARAM_STR);
$queryT-> bindParam(':classid', $ClassIDGlobal, PDO::PARAM_STR);
$queryT->execute();
$resultT=$queryT->fetch(PDO::FETCH_OBJ);
$TeamID = $resultT->TeamID;
$TeamName = $resultT->TeamName;
$Designation = $resultT->designation;




?>
	<title>Ledger <?php echo($TeamName) ?></title>

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
th { white-space: nowrap; }

		</style>

</head>


<body>
	<?php include('includes/header.php');?>

	<div class="ts-main-content" >
		<?php include('includes/leftbar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">

				<div class="row">
					<div class="col-md-12">

						<h2 class="page-title">Ledgers: <?php echo htmlentities($TeamName); ?></h2>

						<!-- Zero Configuration Table -->
						<div class="panel panel-default">
							<div class="panel-heading"><H4>List Transactions <?php if ($Designation == "Student") { ?><a href="new-ledger.php?TeamID=<?php echo($TeamID)?>">New</a><?php } ?></H4></div>
							<div class="panel-body">
							<?php if($error){?><div class="errorWrap" id="msgshow"><?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap" id="msgshow"><?php echo htmlentities($msg); ?> </div><?php }?>
				<table border="0" cellspacing="5" cellpadding="5">
				    <tbody><tr>
				        <td>Start date:</td>
				        <td><input type="text" id="min1" name="min1"></td>
				    </tr>
				    <tr>
				        <td>End date:</td>
				        <td><input type="text" id="max1" name="max1"></td>
				    </tr>
					</tbody>
				</table>
					<table id="zctb1" class="display table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" width="100%">
						<thead>
							<tr>
							    <th width="10">#</th>
								<th width="30">Trans Type</th>
								<th width="10">Amount</th>
								<th width="30">DateEntered</th>
								<th width="30">Comment</th>
							</tr>
						</thead>
						
						<tbody>

<?php 
$sqlL = "SELECT L.LedgerID, L.LedgerTypeID,LT.Description,L.TeamID,L.SchoolYearID,L.Amount,L.Comment,CAST(L.dateentered AS DATE) AS DateEntered from  Ledger AS L, LedgerType AS LT where LT.LedgerTypeID = L.LedgerTypeID AND L.Status = 1 AND L.TeamID = (:TeamID)";
$queryL = $dbh -> prepare($sqlL);
$queryL-> bindParam(':TeamID', $TeamID, PDO::PARAM_STR);
$queryL->execute();
$resultLs=$queryL->fetchAll(PDO::FETCH_OBJ);

$queryL = null;

$cnt=1;

foreach($resultLs as $result)
{				?>	
										<tr>
											<td><?php echo htmlentities($result->LedgerID);?></td>
                                            <td><?php echo htmlentities($result->Description);?></td>
											<td><?php echo htmlentities($result->Amount);?></td>
											<td><?php echo htmlentities($result->DateEntered);?></td>
											<td><?php echo $result->Comment;?></td>
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
							</div>
						</div>
					</div>

					

				</div> <!-- END ROW -->

				<div class="row">
					<div class="col-md-12">
<?php 
						$sqlS = "SELECT SUM(L.Amount) AS SUM,week(L.dateentered) AS WEEK,CAST(L.dateentered AS DATE) AS dateentered ";
						$sqlS .= "from Ledger AS L WHERE    L.TeamID = (:TeamID)  ";
						$sqlS .= "group by week(L.dateentered) ORDER BY L.dateentered asc";
						$queryS = $dbh -> prepare($sqlS);
						$queryS-> bindParam(':TeamID', $TeamID, PDO::PARAM_STR);
						$queryS->execute();
						$resultS=$queryS->fetchAll(PDO::FETCH_OBJ);
						
						$queryS = null;

						$cntS=1;
						?>
						<div class="panel panel-default" >
							<div class="panel-heading">Weekly SUM </div>
							<div class="panel-body">
						<table border="0" cellspacing="5" cellpadding="5">
					       <tbody><tr>
					           <td>Start date:</td>
					           <td><input type="text" id="min3" name="min3"></td>
					       </tr>
					       <tr>
					           <td>End date:</td>
					           <td><input type="text" id="max3" name="max3"></td>
					       </tr>
					   </tbody></table>
						<table id="zctb3" class="display nowrap" cellpadding="0" cellspacing="0" width="100%">
									<thead>
										<tr>
										       <th width="30">Week</th>
												<th width="30">Amount</th>
												<th width="30">Date</th>

												
										</tr>
									</thead>
									
									<tbody>
										<?php
										foreach($resultS as $resS)
										{				?>	
										<tr>
											<td><?php echo htmlentities($resS->WEEK); ?></td>
                                            <td><?php echo htmlentities($resS->SUM); ?></td>
											<td><?php echo htmlentities($resS->dateentered); ?></td>
											
										</tr>
										<?php 
										}
										?>
										</tbody>
										<tfoot>
            								<tr>
                								<th style="text-align:right">Total:</th>
												<th></th>
                								<th></th>
            								</tr>
        								</tfoot>
								</table>
									</div>
									</div>
									</div>


					</div>
					<div class="col-md-6">||</div>
				</div>
			</div>
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
	<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
	<script type="text/javascript">
				 $(document).ready(function () {          
							setTimeout(function() {
							$('.succWrap').slideUp("slow");
							}, 3000);
					});//$(document).ready
		</script>
</body>
</html>
<?php
$queryT = null;
$dbh = null;
} ?>
