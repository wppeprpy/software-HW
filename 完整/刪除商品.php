<?php
require("dbconfig.php");
$id=(int)$_POST['id'];
$sql = "DELETE FROM `products` WHERE `ProductID` = ?";
$stmt = mysqli_prepare($db, $sql); //prepare sql statement
mysqli_stmt_bind_param($stmt, "i", $id); //bind parameters with variables, with types "sss":string, string ,string
mysqli_stmt_execute($stmt);  //執行SQL
echo "message deleted.";



?>
</table>
