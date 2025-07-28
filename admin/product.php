<?php
session_start();

include('includes/config.php'); 
if(strlen($_SESSION['alogin'])==0)
	{	
header('location:index.php');
}
else{

 

   if(isset($_GET['TeamID'])) 
	{
		$teamid=$_GET['TeamID']; 
    }
    else
    {
        $sqlT = "SELECT T.TeamID,T.TeamName,T.ClassID,T.IGFollowers FROM  Team AS T WHERE  T.ClassID = (:classid)";
        $queryT = $dbh -> prepare($sqlT);
        //$queryT-> bindParam(':email', $email, PDO::PARAM_STR);
        $queryT-> bindParam(':classid', $ClassIDGlobal, PDO::PARAM_STR);
        $queryT->execute();
        $resultT=$queryT->fetch(PDO::FETCH_OBJ);
        $teamid = $resultT->TeamID;
        $TeamName = $resultT->TeamName;
        $igfollowers = $resultT->IGFollowers;
    }
    

        $sqlP = "SELECT ProductID,ProductName,Description,RetailPrice,WholeSalePrice,QtySoldRetail,QtySoldWholesale,NumberofPotentialCustomers,InputCost,image,TeamID,StudentID,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate FROM Product WHERE TeamID = (:TeamID)";
        $queryP = $dbh -> prepare($sqlP);
        $queryP-> bindParam(':TeamID', $teamid, PDO::PARAM_STR);
        $queryP->execute();
        $resultP=$queryP->fetch(PDO::FETCH_OBJ);
        //if($queryP->rowCount() > 0)
        if($queryP->rowCount() == 1)
        {
            $ProductStatus = "Update";
        }
        elseif ($queryP->rowCount() > 1)
        {
            $ProductStatus = "Multiple";
        }
        else
        {
            $ProductStatus = "Insert";
        
        }
        echo ($ProductStatus);
    
if(isset($_POST['submit']))
{

$file = $_FILES['image']['name'];
$file_loc = $_FILES['image']['tmp_name'];
$folder="../images/";
$new_file_name = strtolower($file);
$final_file=str_replace(' ','-',$new_file_name);

$ProductID = $_POST['ProductID'];
$ProductName = $_POST['ProductName'];
$Description = $_POST['Description'];
$RetailPrice = $_POST['RetailPrice'];
$WholeSalePrice = $_POST['WholeSalePrice'];
$QtySoldRetail = $_POST['QtySoldRetail'];
$QtySoldWholesale = $_POST['QtySoldWholesale'];
$NumberofPotentialCustomers = $_POST['NumberofPotentialCustomers'];
$InputCost = $_POST['InputCost'];
$teamid  = $_POST['TeamID'];

if(move_uploaded_file($file_loc,$folder.$final_file))
	{
		$image=$final_file;
    }

    if ($ProductStatus == "Update")
    {
        
        $sqlU = "UPDATE Product SET ProductName = (:productname),Description = (:description),RetailPrice = (:retailprice),WholeSalePrice = (:wholesaleprice), ";
        $sqlU .= "QtySoldRetail = (:qtysoldretail),QtySoldWholesale = (:qtysoldwholesale),NumberofPotentialCustomers = (:numberofpotentialcustomers),InputCost = (:inputcost),image = (:image),";
        $sqlU .= "UpdatedBy = (:updatedby),UpdatedDate = now(3)   WHERE TeamID = (:teamid) ";
        
        $queryU = $dbh->prepare($sqlU);
        $queryU-> bindParam(':productname',$ProductName, PDO::PARAM_STR);
        $queryU-> bindParam(':description',$Description, PDO::PARAM_STR);
        $queryU-> bindParam(':retailprice',$RetailPrice, PDO::PARAM_STR);
        $queryU-> bindParam(':wholesaleprice',$WholeSalePrice, PDO::PARAM_STR);
        $queryU-> bindParam(':qtysoldretail',$QtySoldRetail, PDO::PARAM_STR);
        $queryU-> bindParam(':qtysoldwholesale',$QtySoldWholesale, PDO::PARAM_STR);
        $queryU-> bindParam(':numberofpotentialcustomers',$NumberofPotentialCustomers, PDO::PARAM_STR);
        $queryU-> bindParam(':inputcost',$InputCost, PDO::PARAM_STR);
        $queryU-> bindParam(':image', $image, PDO::PARAM_STR);
        $queryU-> bindParam(':updatedby',$_SESSION['alogin'], PDO::PARAM_STR);
        $queryU-> bindParam(':teamid',$teamid, PDO::PARAM_STR);
         
        $queryU->execute();
	    $msg="Information Updated Successfully";
         echo "<script type='text/javascript'>alert('Product Updated Sucessfull!');</script>";
        echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
    }
    else
    {
        $sqlI = "INSERT INTO Product (ProductName,Description,RetailPrice,WholeSalePrice,QtySoldRetail,QtySoldWholesale,NumberofPotentialCustomers,InputCost,image,TeamID,Status,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate) ";
        $sqlI .= " VALUES (:productname,:description,:retailprice,:wholesaleprice,:qtysoldretail,:qtysoldwholesale,:numberofpotentialcustomers,:inputcost,:image,:teamid,1,:createdby,now(3),:updatedby,now(3))";
        $queryI = $dbh->prepare($sqlI);
        $queryI-> bindParam(':productname',$ProductName, PDO::PARAM_STR);
        $queryI-> bindParam(':description',$Description, PDO::PARAM_STR);
        $queryI-> bindParam(':retailprice',$RetailPrice, PDO::PARAM_STR);
        $queryI-> bindParam(':wholesaleprice',$WholeSalePrice, PDO::PARAM_STR);
        $queryI-> bindParam(':qtysoldretail',$QtySoldRetail, PDO::PARAM_STR);
        $queryI-> bindParam(':qtysoldwholesale',$QtySoldWholesale, PDO::PARAM_STR);
        $queryI-> bindParam(':numberofpotentialcustomers',$NumberofPotentialCustomers, PDO::PARAM_STR);
        $queryI-> bindParam(':inputcost', $InputCost, PDO::PARAM_STR);
         $queryI-> bindParam(':image', $image, PDO::PARAM_STR);
         $queryI-> bindParam(':teamid',$teamid, PDO::PARAM_STR);
         $queryI-> bindParam(':createdby',$_SESSION['alogin'], PDO::PARAM_STR);
        $queryI-> bindParam(':updatedby',$_SESSION['alogin'], PDO::PARAM_STR);
         
         
        
        $queryI->execute();
        $lastInsertId = $dbh->lastInsertId();
        if($lastInsertId)
        {
        echo "<script type='text/javascript'>alert('Product Insert Sucessfull!');</script>";
        echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
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

	
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
    <script type="text/javascript">

	function validate()
        {
            var extensions = new Array("jpg","jpeg","png");
            var image_file = document.regform.image.value;
            var image_length = document.regform.image.value.length;
            var pos = image_file.lastIndexOf('.') + 1;
            var ext = image_file.substring(pos, image_length);
            
            var final_ext = ext.toLowerCase();
            
            for (i = 0; i < extensions.length; i++)
            {
                if(extensions[i] == final_ext)
                {
                return true;
                
                }
            }
           // alert("Image Extension Not Valid (Use Jpg,jpeg)");
            //TODOD FIX IMAGE VALIDATE
            //return false;
            return true;
        }
        
</script>
</head>

<body> 
    <?php include('includes/header.php');?>
	<div class="ts-main-content">
        <?php include('includes/leftbar.php');?>
        <div class="content-wrapper">
		<div class="form-content">
			<div class="container">

                <div class="row">
					<div class="col-md-4">
					
					
					<?php
							$sqlT = "SELECT * from Team where ClassID = (:classid) AND Status = 1";
							$queryT = $dbh -> prepare($sqlT);
							$queryT-> bindParam(':classid', $ClassIDGlobal, PDO::PARAM_STR);
							$queryT->execute();
							$resultT=$queryT->fetchAll(PDO::FETCH_OBJ);
							$cntT=1;	
					?>
					<select name="TeamIDselectProduct" id="TeamIDselectProduct" class="form-control" required>
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
						<h1 class="text-center text-bold mt-2x">Product</h1>
                        <div class="hr-dashed"></div>
						<div class="well row pt-2x pb-3x bk-light text-center">
                         <form method="post" class="form-horizontal" enctype="multipart/form-data" name="regform" onSubmit="return validate();">
                            <div class="form-group">
                                <?php
                                if ($ProductStatus == "Insert")
                                { ?>
                                <input type="hidden" name= "ProductID" id = "ProductID" value="">
                                <input type="hidden" name= "TeamID" id = "TeamID" value="<?php echo ($teamid);?>">
                                <?php
                                } else { ?>
                                <input type="hidden" name= "ProductID" id = "ProductID" value="<?php echo ($resultP->ProductID);?>">
                                <input type="hidden" name= "TeamID" id = "TeamID" value="<?php echo ($resultP->TeamID);?>">
                                <?php
                                }
                                ?>
                                
                            <label class="col-sm-1 control-label">ProductName<span style="color:red">*</span></label>
                            <div class="col-sm-5">
                            <?php
                                if ($ProductStatus == "Insert")
                                { ?>
                            <input type="text" name="ProductName" class="form-control" style="width:300px" value="" required>
                             <?php
                                } else { ?>
                                <input type="text" name="ProductName" class="form-control" style="width:300px" value="<?php echo ( htmlentities($resultP->ProductName));?>" required>
                                <?php } ?>
                            </div>
                            <label class="col-sm-1 control-label">Description<span style="color:red">*</span></label>
                            <div class="col-sm-5">
                                 <?php
                                if ($ProductStatus == "Insert")
                                { ?>
                                    <input type="text" name="Description" class="form-control" style="width:300px"  value="" required>
                                         <?php
                                } else { ?>
                                    <input type="text" name="Description" class="form-control" style="width:300px"  value="<?php echo ( htmlentities($resultP->Description) );?>" required>
                                   <?php
                                }
                                ?> 
                            
                            </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-1 control-label">Retail $<span style="color:red">*</span></label>
                                <div class="col-sm-5">
                                    <?php
                                if ($ProductStatus == "Insert")
                                { ?>
                                    $ <input type="number" name="RetailPrice" class="form-control" style="Width:100px" id="RetailPrice" min="0.01" step="0.01" max="1000" value="" required >
                                    <?php
                                } else { ?>
                                    <input type="number" name="RetailPrice" class="form-control" style="Width:100px" id="RetailPrice" min="0.01" step="0.01" max="1000" value="<?php echo htmlentities($resultP->RetailPrice);?>" required >
                                    <?php } ?>
                                </div>

                                

                                <label class="col-sm-1 control-label">WholeSale$<span style="color:red">*</span></label>
                                <div class="col-sm-5">
                                    <?php
                                if ($ProductStatus == "Insert")
                                { ?>
                                    <input type="number" name="WholeSalePrice" class="form-control" style="Width:100px" min="0.01" step="0.01" max="1000" value="" required>
                                    <?php
                                } else { ?>
                                    <input type="number" name="WholeSalePrice" class="form-control" style="Width:100px" min="0.01" step="0.01" max="1000" value="<?php echo htmlentities($resultP->WholeSalePrice);?>" required>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-1 control-label">Qty Sold Retail<span style="color:red">*</span></label>
                                <div class="col-sm-2">
                                    <?php
                                if ($ProductStatus == "Insert")
                                { ?>
                                    <input type="number" name="QtySoldRetail" class="form-control" style="Width:100px" min="0" step="1" max="1000" value="" required>
                                    <?php
                                } else { ?>
                                    <input type="number" name="QtySoldRetail" class="form-control" style="Width:100px" min="0" step="1" max="1000" value="<?php echo $resultP->QtySoldRetail;?>" required>
                                    <?php } ?>
                                </div>
                                <label class="col-sm-1 control-label">Qty Sold Wholesale<span style="color:red">*</span></label>
                                <div class="col-sm-2">
                                    <?php
                                if ($ProductStatus == "Insert")
                                { ?>
                                    <input type="number" name="QtySoldWholesale" class="form-control" style="Width:100px" min="0" step="1" max="1000" value="" required>
                                    <?php
                                } else { ?>
                                    <input type="number" name="QtySoldWholesale" class="form-control" style="Width:100px" min="0" step="1" max="1000" value="<?php echo $resultP->QtySoldWholesale;?>" required>
                                    <?php } ?>
                                </div>

                                <label class="col-sm-1 control-label">Potential Customers<span style="color:red">*</span></label>
                                <div class="col-sm-2">
                                    <?php
                                if ($ProductStatus == "Insert")
                                { ?>
                                    <input type="number" name="NumberofPotentialCustomers" class="form-control" style="Width:100px" min="0" step="1" max="1000" value="" required>
                                    <?php
                                } else { ?>
                                    <input type="number" name="NumberofPotentialCustomers" class="form-control" style="Width:100px" min="0" step="1" max="1000" value="<?php echo $resultP->NumberofPotentialCustomers;?>" required>
                                    <?php } ?>
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-sm-1 control-label">Input Cost<span style="color:red">*</span></label>
                                <div class="col-sm-5">
                                    <?php
                                if ($ProductStatus == "Insert")
                                { ?>
                                    <input type="number" name="InputCost" class="form-control" style="Width:100px" min="0.01" step="0.01" max="1000" value="" required>
                                    <?php
                                } else { ?>
                                    <input type="number" name="InputCost" class="form-control" style="Width:100px" min="0.01" step="0.01" max="1000" value="<?php echo $resultP->InputCost;?>" required>
                                    <?php } ?>
                                </div>
                                <?php
                                if ($ProductStatus == "Insert")
                                { ?>
                                <label class="col-sm-1 control-label">Image<span style="color:red">*</span></label>
                                 <?php
                                } else { ?>
                                <label class="col-sm-1 control-label">Image<span style="color:red">*</span></label><img src=../images/<?php echo htmlentities($resultP->image);?> width="40" height="40">
                                 <?php } ?>
                                <div class="col-sm-3">
                                    <div>
                                        <?php
                                if ($ProductStatus == "Insert")
                                { ?>
                                        <input type="file" name="image" class="form-control" value="" >
                                    <?php
                                } else { ?>
                                        <input type="file" name="image" class="form-control" value="<?php echo htmlentities($resultP->image);?>" >
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                           
                                <button class="btn btn-primary" name="submit" type="submit">Submit</button>
                                <button class="btn btn-cancel" onclick="history.go(-1); return false;" name="cancel" type="cancel">Cancel</button>
                                </form>
                                <?php
                                if ($ProductStatus != "Insert")
                                { ?>
                                <div class="form-group">
                                    <div class="col-sm-5">
                                    <table id="Ledger2" class="display table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" width="100%">
									<thead>
										<tr>
							    			<th width="30%">Name</th>
											<th width="30%">Value</th>
										</tr>
									</thead>
									<tbody>
                                    <tr>
											<td>Retail Margins</td>
                                            <td><?php echo htmlentities('$'.$resultP->RetailPrice - $resultP->InputCost );?></td>
                                    </tr>
									 <tr>
											<td>Whole Sale Margins</td>
                                            <td><?php echo htmlentities('$'.$resultP->WholeSalePrice - $resultP->InputCost );?></td>
                                    </tr>
									 <tr>
											<td>Retail Total</td>
                                            <td><?php echo htmlentities('$'.($resultP->RetailPrice - $resultP->InputCost ) * ($resultP->QtySoldRetail)  );?></td>
                                    </tr>
                                     <tr>
											<td>Wholesale Total</td>
                                            <td><?php echo htmlentities('$'.($resultP->WholeSalePrice - $resultP->InputCost ) * ($resultP->QtySoldWholesale)  );?></td>
                                    </tr>
                                    	
                                     <tr>
											<td>Sale Inpuiry</td>
                                            <td><?php echo htmlentities('$'.(($resultP->WholeSalePrice - $resultP->InputCost) / (2) ) * ($resultP->NumberofPotentialCustomers)  );?></td>
                                    </tr>	
                                    <tr>
											<td>Total Order Input</td>
                                            <td><?php echo htmlentities('$'.(($resultP->RetailPrice - $resultP->InputCost ) * ($resultP->QtySoldRetail)) + (($resultP->WholeSalePrice - $resultP->InputCost ) * ($resultP->QtySoldWholesale)) + ((($resultP->WholeSalePrice - $resultP->InputCost) / (2) ) * ($resultP->NumberofPotentialCustomers))  );?></td>
                                    </tr>	
									</tbody>
                                    </table>
                                    </div>
                                </div>
                                    <?php } ?>
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

</body>


<?php
}
?>