<?php  
// DB credentials.
define('DB_HOST','localhost');
define('DB_USER','web');
define('DB_PASS','2007Me@thead');
//define('DB_PASS','password');
define('DB_NAME','test');
// Establish database connection.
try
{
$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
}
catch (PDOException $e)
{
exit("Error: " . $e->getMessage());
}

global $ClassIDGlobal;
global $CurrentClassGlobal;
global $SchoolYearIDGlobal;
global $CurrentSchoolyearGlobal;
$sql = "SELECT SettingID,Value,Name from Setting ";
$queryS = $dbh -> prepare($sql);
$queryS->execute();
$results=$queryS->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($queryS->rowCount() > 0)
{
    foreach($results as $result) 
    {
        if ($result->Name == "CurrentClassID")
        {
            $ClassIDGlobal = $result->Value;
        }
        elseif ($result->Name == "CurrentSchoolYearID") 
        {
            $SchoolYearIDGlobal = $result->Value;
        }
    }

}
else 
{
    echo("CONFIG DATA NOT FOUND");
}

$sqlC = "SELECT ClassID,ClassName,Room FROM Class WHERE ClassID = (:classid)";
    $queryC = $dbh -> prepare($sqlC);
    $queryC-> bindParam(':classid', $ClassIDGlobal, PDO::PARAM_STR);
    $queryC->execute();
    $resultC=$queryC->fetch(PDO::FETCH_OBJ);
    if($queryC->rowCount() > 0)
    {
        $CurrentClassGlobal = $resultC->ClassName;
    }

$sqlY = "SELECT SchoolYearID,YearName FROM SchoolYear WHERE SchoolYearID = (:schoolyearid)";
    $queryY = $dbh -> prepare($sqlY);
    $queryY-> bindParam(':schoolyearid', $SchoolYearIDGlobal, PDO::PARAM_STR);
    $queryY->execute();
    $resultY=$queryY->fetch(PDO::FETCH_OBJ);
    if($queryY->rowCount() > 0)
    {
        $CurrentSchoolyearGlobal = $resultY->YearName;
    }


global $AdminEmail;
$AdminEmail = "scott.seacrist@goatcrist.us";

global $AdminCC;
$AdminCC = "slseacrist@gmail.com";

global $AdminBCC;
$AdminBCC = "michael.aarset@gmail.com";

error_reporting(E_ALL);
//error_reporting(0);
?>
