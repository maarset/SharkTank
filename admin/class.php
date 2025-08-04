<?php
session_start();

include('includes/config.php');
if(strlen($_SESSION['alogin'])==0 || $_SESSION['alogin'] != 'admin')
	{	
header('location:index.php');
}
else{
	
 

	if(isset($_REQUEST['del']))
	{
		$Class_id = $_REQUEST['del'];
		$memstatus=0;
		$sql = "UPDATE Class SET status=:status WHERE  ClassID =:aeid";
		$query = $dbh->prepare($sql);
		$query -> bindParam(':status',$memstatus, PDO::PARAM_STR);
		$query-> bindParam(':aeid',$Class_id, PDO::PARAM_STR);
		$query -> execute();

		$msg="Changes Sucessfully";
		header('location:class.php?');
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
	
	<title>Class </title>

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
					<div class="col-md-12">
						<div class="panel panel-default"><div class="panel-heading">List Classes <a href="new-class.php?">New</a></div>
								<div class="panel-body">
								<?php if($error){?><div class="errorWrap" id="msgshow"><?php echo htmlentities($error); ?> </div><?php } 
								else if($msg){?><div class="succWrap" id="msgshow"><?php echo htmlentities($msg); ?> </div><?php }?>
				
									<table id="Ledger2" class="display table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" width="100%">
									<thead>
										<tr>
							    			<th width="30%">Name</th>
											<th width="30%">SchoolYear</th>
											<th width="20%">Room</th>
											<th width="20%"></TH>
										</tr>
									</thead>
									<tbody>

									<?php 
									$sqlL = "SELECT C.ClassID, C.ClassName,C.Status,C.Room,SY.SchoolYearID,SY.YearName,SY.StartDate,SY.EndDate, C.CreatedBy,C.CreatedDate,C.UpdatedBy, C.UpdatedDate from  Class AS C, SchoolYear AS SY ";
									$sqlL .= "WHERE C.SchoolYearID = SY.SchoolYearID AND C.Status = 1 AND SY.Status = 1";
									$queryL = $dbh -> prepare($sqlL);
									$queryL->execute();
									$resultLs=$queryL->fetchAll(PDO::FETCH_OBJ);

									$cnt=1;

									foreach($resultLs as $result)
									{				
										if ($ClassIDGlobal == $result->ClassID)
										{
											$current = "background-color:#ffb1a3";
										}
										else
										{
											$current = "";
										}
										?>	
										<tr style="<?php echo($current); ?>">
											<td><?php echo htmlentities($result->ClassName );?></td>
                                            <td><?php echo htmlentities($result->YearName);?></td>
											<td><?php echo htmlentities($result->Room);?></td>
											<td class="CellWithComment">
											<a href="edit-class.php?ClassID=<?php echo $result->ClassID;?>" onclick="return confirm('Do you want to Edit');">&nbsp; <i class="fa fa-pencil"></i></a>&nbsp;&nbsp;

											<a href="class.php?del=<?php echo $result->ClassID;?>" onclick="return confirm('Do you want to Delete');"><i class="fa fa-trash" style="color:red"></i></a>&nbsp;&nbsp;
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
                							<th style="text-align:right"></th>
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
<?php 
$query = null;
$queryL = null;
include('includes/close.php');
} ?>
