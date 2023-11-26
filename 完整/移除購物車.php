<?php
require('dbconfig.php');

$ID = $_POST['ID']; // 從前端獲取商品 ID
$quantity = $_POST['Quantity']; // 從前端獲取要移除的數量

// 檢查購物車中是否已經存在相同的商品
$checkSql = "SELECT * FROM shoppingcart WHERE ProductID = ?";
$checkStmt = mysqli_prepare($db, $checkSql);
mysqli_stmt_bind_param($checkStmt, "i", $ID);
mysqli_stmt_execute($checkStmt);
$checkResult = mysqli_stmt_get_result($checkStmt);

if (mysqli_num_rows($checkResult) > 0) {
    $existingData = mysqli_fetch_assoc($checkResult);
    $currentQuantity = $existingData['Quantity'];

    if ($quantity >= $currentQuantity) {
        // 移除整個商品
        $deleteSql = "DELETE FROM shoppingcart WHERE ProductID = ?";
        $deleteStmt = mysqli_prepare($db, $deleteSql);
        mysqli_stmt_bind_param($deleteStmt, "i", $ID);
        mysqli_stmt_execute($deleteStmt);

        echo "商品已從購物車移除";
    } else {
        // 更新商品數量
        $newQuantity = $currentQuantity - $quantity;

        $updateSql = "UPDATE shoppingcart SET Quantity = ? WHERE ProductID = ?";
        $updateStmt = mysqli_prepare($db, $updateSql);
        mysqli_stmt_bind_param($updateStmt, "ii", $newQuantity, $ID);
        mysqli_stmt_execute($updateStmt);

        echo "商品數量已更新為 " . $newQuantity;
    }
} else {
    echo "購物車中找不到該商品";
}

// 關閉 statement 和連接
mysqli_stmt_close($checkStmt);
mysqli_close($db);
?>
