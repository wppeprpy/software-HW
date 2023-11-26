<?php
require('dbconfig.php');

$Name = $_POST['ProductName'];
$Des = $_POST['Description'];
$price = $_POST['Price'];
$qua = $_POST['StockQuantity'];

$sql = "INSERT INTO `products` (`ProductName`, `Description`, `Price`, `StockQuantity`) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($db, $sql);

mysqli_stmt_bind_param($stmt, "ssdi", $Name, $Des, $price, $qua);
mysqli_stmt_execute($stmt);

echo "message added.";

mysqli_stmt_close($stmt);
mysqli_close($db);
?>
