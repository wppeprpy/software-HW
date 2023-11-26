<?php
require('dbconfig.php');

$ID = $_POST['ID']; // 假設你從前端獲取了 ID 參數
$quantity = $_POST['Quantity']; // 假設你從前端獲取了數量參數

// 檢查購物車中是否已經存在相同的商品
$checkSql = "SELECT * FROM shoppingcart WHERE ProductID = ?";
$checkStmt = mysqli_prepare($db, $checkSql);
mysqli_stmt_bind_param($checkStmt, "i", $ID);
mysqli_stmt_execute($checkStmt);
$checkResult = mysqli_stmt_get_result($checkStmt);

if (mysqli_num_rows($checkResult) > 0) {
    // 商品已存在於購物車中，更新數量
    $existingData = mysqli_fetch_assoc($checkResult);
    $newQuantity = $existingData['Quantity'] + $quantity;

    $updateSql = "UPDATE shoppingcart SET Quantity = ? WHERE ProductID = ?";
    $updateStmt = mysqli_prepare($db, $updateSql);
    mysqli_stmt_bind_param($updateStmt, "ii", $newQuantity, $ID);
    mysqli_stmt_execute($updateStmt);

    echo "商品數量已增加 " . $newQuantity;
} else {
    // 商品不存在於購物車中，插入新記錄
    $insertSql = "INSERT INTO shoppingcart (ProductID, Quantity) VALUES (?, ?)";
    $insertStmt = mysqli_prepare($db, $insertSql);
    mysqli_stmt_bind_param($insertStmt, "ii", $ID, $quantity);
    mysqli_stmt_execute($insertStmt);

    echo "商品已加入購物車，數量為 " . $quantity;
}

?>
