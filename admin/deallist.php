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
//$query -> execute();

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
	
	<title>Manage Deals</title>

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

						<h2 class="page-title">Manage Deals</h2>
						
						<!-- Zero Configuration Table -->
						<div class="panel panel-default">
							<div class="panel-heading">List Deals <a href="new-deal.php">New</a></div>
							<div class="panel-body">
							<?php if($error){?><div class="errorWrap" id="msgshow"><?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap" id="msgshow"><?php echo htmlentities($msg); ?> </div><?php }?>
								<table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
										
												<th>Deal Name</th>
                                                <th>Shark Name</th>
												<th>Team Name</th>
                                                <th>Class</th>
												<th>Total Invested</TH>
                                                <th>% company</th>
												<th>Created By</th>
                                               <th>Created Date</th>
                                                <th>Updated By</th>
												<th>Updated Date</th>
                                                
											<th>Action</th>	
										</tr> 
									</thead>
									
									<tbody>

<?php 
$sql = "SELECT D.DealID,D.DealName,D.SharkID,D.TeamID,D.ClassID,D.TotalInvested,D.PercentOwned,D.Status, ";
$sql .= "D.CreatedBy, D.CreatedDate,D.UpdatedBy, D.UpdatedDate, C.ClassID, C.ClassName,T.TeamName,  ";
$sql .= "S.SharkID,S.SharkName ";
$sql .= "from  Deal D,Team AS T,Class AS C,Shark AS S ";
$sql .= "WHERE D.TeamID = T.TeamID AND D.ClassID = C.ClassID AND D.SharkID = S.SharkID ";
$sql .= "AND D.ClassID = (:classid)  ";
$query1 = $dbh -> prepare($sql);
//$query-> bindParam(':ClassID', $ClassIDGlobal, PDO::PARAM_STR);
$query1-> bindParam(':classid', $ClassIDGlobal, PDO::PARAM_STR);
$query1->execute();
$results=$query1->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query1->rowCount() > 0)
{
foreach($results as $result)
{				?>	
										<tr>
											<td><?php echo htmlentities($result->DealName);?></td>
											<td><?php echo htmlentities($result->SharkName);?></td>
                                            <td><?php echo htmlentities($result->TeamName);?></td>
											<td><?php echo htmlentities($result->ClassName);?></td>
                                            <td><?php echo htmlentities($result->TotalInvested) ;?></td>
											<td><?php echo htmlentities($result->PercentOwned);?></td>
                                            <td><?php echo htmlentities($result->CreatedBy);?></td>
                                            <td><?php echo htmlentities($result->CreatedDate);?></td>
                                            <td><?php echo htmlentities($result->UpdatedBy);?></td>
                                            <td><?php echo htmlentities($result->UpdatedDate);?></td>
                                           
											
<td>
<a href="edit-deal.php?edit=<?php echo $result->DealID;?>" onclick="return confirm('Do you want to Edit Deal');">&nbsp; <i class="fa fa-pencil"></i></a>&nbsp;&nbsp;

<a href="deallist.php?del=<?php echo $result->TeamID;?>&name=<?php echo htmlentities($result->TeamName);?>" onclick="return confirm('Do you want to Delete');"><i class="fa fa-trash" style="color:red"></i></a>&nbsp;&nbsp;
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
<?php 
$query = null;
$query1 = null;
include('includes/close.php');
} ?>
