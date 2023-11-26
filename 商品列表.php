<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品列表</title>
</head>
<body>

<table width="500" border="1">
    <tr>
        <td>ID</td>
        <td>Name</td>
        <td>Description</td>
        <td>Price</td>
    </tr>
    <?php
    require("dbconfig.php");
    $sql = "SELECT * FROM `products`";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($rs = mysqli_fetch_assoc($result)) {
        echo "<tr><td>", $rs['ProductID'],
        "</td><td>", $rs['ProductName'],
        "</td><td>", $rs['Description'],
        "</td><td>", $rs['Price'];
    }
    ?>
</table>

</body>
</html>
