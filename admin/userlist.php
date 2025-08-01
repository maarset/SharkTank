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

$sql = "delete from users WHERE id=:id";
$query = $dbh->prepare($sql);
$query -> bindParam(':id',$id, PDO::PARAM_STR);
$query -> execute();

$sql2 = "insert into deleteduser (email) values (:name)";
$query2 = $dbh->prepare($sql2);
$query2 -> bindParam(':name',$name, PDO::PARAM_STR);
$query2 -> execute();

$msg="Data Deleted successfully";
}

if(isset($_REQUEST['unconfirm']))
	{
	$aeid=intval($_GET['unconfirm']);
	$memstatus=1;
	$sql = "UPDATE users SET status=:status WHERE  id=:aeid";
	$query = $dbh->prepare($sql);
	$query -> bindParam(':status',$memstatus, PDO::PARAM_STR);
	$query-> bindParam(':aeid',$aeid, PDO::PARAM_STR);
	$query -> execute();
	$msg="Changes Sucessfully";
	}

	if(isset($_REQUEST['confirm']))
	{
	$aeid=intval($_GET['confirm']);
	$memstatus=0;
	$sql = "UPDATE users SET status=:status WHERE  id=:aeid";
	$query = $dbh->prepare($sql);
	$query -> bindParam(':status',$memstatus, PDO::PARAM_STR);
	$query-> bindParam(':aeid',$aeid, PDO::PARAM_STR);
	$query -> execute();
	$msg="Changes Sucessfully";
	}
    
	if(isset($_REQUEST['login']))
	{
		$_SESSION['alogin'] = $_REQUEST['email'];
		$msg="Login Successfull";
		echo "<script type='text/javascript'> document.location = '../dashboard.php'; </script>";
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
	
	<title>Manage Users</title>

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

						<h2 class="page-title">Manage Users</h2>
						
						<!-- Zero Configuration Table -->
						<div class="panel panel-default">
							<div class="panel-heading">List Users</div> 
							<div class="panel-body">
							<?php if($error){?><div class="errorWrap" id="msgshow"><?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap" id="msgshow"><?php echo htmlentities($msg); ?> </div><?php }?>
								<table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
										<th>#</th>
												<th>Image</th>
                                                <th>Name</th>
												<th>Team</th>
                                                <th>Email</th>
                                                <th>Gender</th>
                                                <th>Phone</th>
                                                <th>Designation</th>
                                                <th>Account</th>
											<th>Action</th>	
										</tr>
									</thead>
									
									<tbody>

									<?php $sql = "SELECT u.id,u.TeamID,t.TeamName,u.name,u.email,u.gender,u.mobile,u.designation,u.image,u.status from  users AS u,Team AS t WHERE t.TeamID = u.TeamID AND t.ClassID = (:ClassID)  ";
									$query = $dbh -> prepare($sql);
									$query-> bindParam(':ClassID', $ClassIDGlobal, PDO::PARAM_STR);
									$query->execute();
									$results=$query->fetchAll(PDO::FETCH_OBJ);
									$cnt=1;
									if($query->rowCount() > 0)
									{
									foreach($results as $result)
									{				?>	
										<tr>
											<td><?php echo htmlentities($cnt);?></td>
											<td><img src="../images/<?php echo htmlentities($result->image);?>" style="width:50px; border-radius:50%;"/></td>
                                            <td><?php echo htmlentities($result->name);?></td>
											<td><?php echo htmlentities($result->TeamName);?></td>
                                            <td><?php echo htmlentities($result->email);?></td>
                                            <td><?php echo htmlentities($result->gender);?></td>
                                            <td><?php echo htmlentities($result->mobile);?></td>
                                            <td><?php echo htmlentities($result->designation);?> 
                                            <td>
                                            <!--
                                            <?php if($result->status == 1)
                                                    {?>
                                                    <a href="userlist.php?confirm=<?php echo htmlentities($result->id);?>" onclick="return confirm('Do you really want to Un-Confirm the Account')">Confirmed <i class="fa fa-check-circle"></i></a> 
                                                    <?php } else {?>
                                                    <a href="userlist.php?unconfirm=<?php echo htmlentities($result->id);?>" onclick="return confirm('Do you really want to Confirm the Account')">Un-Confirmed <i class="fa fa-times-circle"></i></a>
                                                    <?php } ?>
													<a href="userlist.php?login=true&email=<?php echo htmlentities($result->email);?>" onclick="return confirm('Do you really want to login as $result->email')">login <i class="fa fa-times-circle"></i></a>
													-->
													<a href="userlist.php?login=true&email=<?php echo htmlentities($result->email);?>" onclick="return confirm('Do you really want to login as <?php echo ($result->email) ?>'">login as  <?php echo htmlentities($result->name);?><i class="fa fa-times-circle"></i></a>
											</td>												
											<td>
											<a href="edit-user.php?edit=<?php echo $result->id;?>" onclick="return confirm('Do you want to Edit');">&nbsp; <i class="fa fa-pencil"></i></a>&nbsp;&nbsp;
											<a href="userlist.php?del=<?php echo $result->id;?>&name=<?php echo htmlentities($result->email);?>" onclick="return confirm('Do you want to Delete');"><i class="fa fa-trash" style="color:red"></i></a>&nbsp;&nbsp;
											</td>
										</tr>
										<?php $cnt=$cnt+1; }} ?>
										
									</tbody>
								</table>
							</div>
						</div>
					


					<div class="panel panel-default">
						<div class="panel-heading">List Sharks</div> 
						<div class="panel-body">
							<table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
										<th>#</th>
												<th>Image</th>
                                                <th>Name</th>
												<th>Team</th>
                                                <th>Email</th>
                                                <th>Gender</th>
                                                <th>Phone</th>
                                                <th>Designation</th>
                                                <th>Account</th>
											<th>Action</th>	
										</tr>
									</thead>
									
									<tbody>

									<?php $sqlS = "SELECT u.id,u.TeamID,'' AS TeamName,u.name,u.email,u.gender,u.mobile,u.designation,u.image,u.status ";
									$sqlS .= "from  users AS u WHERE u.designation = 'Shark' AND u.ClassID = (:ClassID)  ";
									$queryS = $dbh -> prepare($sqlS);
									$queryS-> bindParam(':ClassID', $ClassIDGlobal, PDO::PARAM_STR);
									$queryS->execute();
									$resultsS=$queryS->fetchAll(PDO::FETCH_OBJ);
									$cnt=1;
									if($queryS->rowCount() > 0)
									{
									foreach($resultsS as $resultS)
									{				?>	
										<tr>
											<td><?php echo htmlentities($cnt);?></td>
											<td><img src="../images/<?php echo htmlentities($resultS->image);?>" style="width:50px; border-radius:50%;"/></td>
                                            <td><?php echo htmlentities($resultS->name);?></td>
											<td><?php echo htmlentities($resultS->TeamName);?></td>
                                            <td><?php echo htmlentities($resultS->email);?></td>
                                            <td><?php echo htmlentities($resultS->gender);?></td>
                                            <td><?php echo htmlentities($resultS->mobile);?></td>
                                            <td><?php echo htmlentities($resultS->designation);?> 
                                            <td>
                                            
                                            <?php if($result->status == 1)
                                                    {?>
                                                    <a href="userlist.php?confirm=<?php echo htmlentities($resultS->id);?>" onclick="return confirm('Do you really want to Un-Confirm the Account')">Confirmed <i class="fa fa-check-circle"></i></a> 
                                                    <?php } else {?>
                                                    <a href="userlist.php?unconfirm=<?php echo htmlentities($resultS->id);?>" onclick="return confirm('Do you really want to Confirm the Account')">Un-Confirmed <i class="fa fa-times-circle"></i></a>
                                                    <?php } ?>
											</td>												
											<td>
											<a href="edit-user.php?edit=<?php echo $resultS->id;?>" onclick="return confirm('Do you want to Edit');">&nbsp; <i class="fa fa-pencil"></i></a>&nbsp;&nbsp;
											<a href="userlist.php?del=<?php echo $resultS->id;?>&name=<?php echo htmlentities($resultS->email);?>" onclick="return confirm('Do you want to Delete');"><i class="fa fa-trash" style="color:red"></i></a>&nbsp;&nbsp;
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
