<?php
require("dbconfig.php");

$id = (int)$_POST['ProductID'];
$Name = $_POST['ProductName'];
$Des = $_POST['Description'];
$price = $_POST['Price'];
$qua = $_POST['StockQuantity'];

$sql = "UPDATE `products` SET  `ProductName` = (?), `Description` = (?), `Price` = (?), `StockQuantity` = (?) WHERE `ProductID` = (?);";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "ssdii", $Name, $Des, $price, $qua, $id);
mysqli_stmt_execute($stmt);
echo "message modified.";
?>
