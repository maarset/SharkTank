<?php
session_start();

include('includes/config.php');
//if(strlen($_SESSION['alogin'])==0)
if(strlen($_SESSION['alogin'])==0 || $_SESSION['alogin'] != 'admin')
	{	
header('location:index.php');
}
else{
if(isset($_GET['del']) && isset($_GET['name']))
{
$id=$_GET['del'];
$name=$_GET['name'];

//$sql = "delete from users WHERE id=:id";
$sql = "delete from Team WHERE TeamID=:id";
$query = $dbh->prepare($sql);
$query -> bindParam(':id',$id, PDO::PARAM_STR);
$query -> execute();

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
	
	<title>Manage Teams</title>

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

						<h2 class="page-title">Manage Teams</h2>
						
						<!-- Zero Configuration Table -->
						<div class="panel panel-default">
							<div class="panel-heading">List Teams <a href="new-team.php">New</a></div>
							<div class="panel-body">
							<?php if($error){?><div class="errorWrap" id="msgshow"><?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap" id="msgshow"><?php echo htmlentities($msg); ?> </div><?php }?>
								<table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
										<th>#</th>
												<th>Team Name</th>
                                                <th>Class Name</th>
												<th>School Year</th>
                                                <th>Shark</th>
												<th>IG Followers</TH>
                                                <th>Credit</th>
                                                <th>Debit</th>
                                                <th>Balance</th>
                                               
											<th>Action</th>	
										</tr> 
									</thead>
									
									<tbody>

<?php 
$sql = "SELECT T.TeamID,T.TeamName,C.ClassName,SY.YearName,S.SharkName,T.IGFollowers,T.credit,T.debit,T.balance ";
$sql .= "from  Team AS T,Class AS C,SchoolYear AS SY, Shark AS S WHERE T.ClassID = C.ClassID AND T.SchoolYearID = SY.SchoolYearID ";
$sql .= "AND T.SharkID = S.SharkID AND T.SchoolyearID = (:schoolyearid) ";
$query = $dbh -> prepare($sql);
//$query-> bindParam(':ClassID', $ClassIDGlobal, PDO::PARAM_STR);
$query-> bindParam(':schoolyearid', $SchoolYearIDGlobal, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{				?>	
										<tr>
											<td><?php echo htmlentities($result->TeamID);?></td>
											<td><?php echo htmlentities($result->TeamName);?></td>
                                            <td><?php echo htmlentities($result->ClassName);?></td>
											<td><?php echo htmlentities($result->YearName);?></td>
                                            <td><?php echo htmlentities($result->SharkName) ;?></td>
											<td><?php echo htmlentities($result->IGFollowers);?></td>
                                            <td><?php echo htmlentities($result->credit);?></td>
                                            <td><?php echo htmlentities($result->debit);?></td>
                                            <td><?php echo htmlentities($result->balance);?> </td>
                                           
											
<td>
<a href="edit-team.php?edit=<?php echo $result->TeamID;?>" onclick="return confirm('Do you want to Edit');">&nbsp; <i class="fa fa-pencil"></i></a>&nbsp;&nbsp;

<a href="teamlist.php?del=<?php echo $result->TeamID;?>&name=<?php echo htmlentities($result->TeamName);?>" onclick="return confirm('Do you want to Delete');"><i class="fa fa-trash" style="color:red"></i></a>&nbsp;&nbsp;
</td>
										</tr>
										<?php $cnt=$cnt+1; }} ?>
										
									</tbody>
								</table>
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
