<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購物車列表</title>
    <!-- 這裡可以加入 CSS 樣式表的連結 -->
</head>
<body>

<table width="500" border="1">
    <tr>
        <td>ProductID</td>
        <td>Quantity</td>
        <td>Total Price</td>
    </tr>
    <?php
    require("dbconfig.php");

    // Initialize total price variable
    $totalPrice = 0;

    $sql = "SELECT ProductID, Quantity FROM `shoppingcart`";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($rs = mysqli_fetch_assoc($result)) {
        // 獲取商品資訊
        $productID = $rs['ProductID'];
        $quantity = $rs['Quantity'];

        // 根據商品ID查詢商品價格
        $productSql = "SELECT Price FROM `products` WHERE ProductID = ?";
        $productStmt = mysqli_prepare($db, $productSql);
        mysqli_stmt_bind_param($productStmt, "i", $productID);
        mysqli_stmt_execute($productStmt);
        $productResult = mysqli_stmt_get_result($productStmt);
        $productData = mysqli_fetch_assoc($productResult);
        $price = $productData['Price'];

        // 計算總價
        $itemTotalPrice = $quantity * $price;

        // 顯示在表格中
        echo "<tr><td>" , $productID,
        "</td><td>" , $quantity,
        "</td><td>" , $itemTotalPrice,
        "</td></tr>";

        // 累加總價
        $totalPrice += $itemTotalPrice;

        // 關閉商品價格查詢的 statement
        mysqli_stmt_close($productStmt);
    }

    // 關閉主要的 statement 和連接
    mysqli_stmt_close($stmt);
    mysqli_close($db);

    // 顯示總價
    echo "<tr><td colspan='2'>Total:</td><td>$totalPrice</td></tr>";
    ?>
</table>

</body>
</html>
