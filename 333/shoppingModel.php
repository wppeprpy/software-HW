<?php
require('dbconfig.php');


// 【客戶查看商品列表】
// 功能：取得商品清單。使用 SELECT 語句從 product 表格中擷取 pID, name, price, stock 欄位的資料。透過 mysqli_prepare、mysqli_stmt_execute、mysqli_stmt_get_result 和mysqli_fetch_assoc 從資料庫取得資料並回傳成陣列。

function listProduct(){
	global $db;
	$sql="select pID, name, price, stock from product;";
	$stmt=mysqli_prepare($db,$sql);
	mysqli_stmt_execute($stmt);
	$result=mysqli_stmt_get_result($stmt);
	$rows=array();
	while($r=mysqli_fetch_assoc($result)){
		$rows[]=$r;
	}
	return $rows;
}
// 【客戶將商品放入購物車】
// 功能：將商品加入購物車。檢查購物車中是否已有此商品，有的話增加其數量；否則新增一筆資料到購物車表格中。使用 mysqli_prepare 來準備 SQL 語句，透過 mysqli_stmt_bind_param 綁定參數，並執行相應的 SQL 語句。

function addCart($pID){

	global $db;
	//假如購物車裡面有此商品，則數量+1
	$sql1 = "select count(*) from cart where pID=?";
	$stmt = mysqli_prepare($db, $sql1);
	mysqli_stmt_bind_param($stmt, "i", $pID);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $count); //會將結果存到count變數中
	mysqli_stmt_fetch($stmt);
	mysqli_stmt_close($stmt); // 因為 prepare 所以需要用 close 關閉 statement，才能執行下一個 prepare

	if ($count > 0) {
		// 購物車裡已經有此商品，數量+1
		$sql2 = "update cart set amount = amount + 1 where pID=?";
		$stmt = mysqli_prepare($db, $sql2);
		mysqli_stmt_bind_param($stmt, "i", $pID);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt); // Close the first statement

	} else {
		// 購物車裡沒有此商品，新增一筆資料
		$sql3 = "insert into cart (pID, amount) values (?, 1)";
		$stmt = mysqli_prepare($db, $sql3);
		mysqli_stmt_bind_param($stmt, "i", $pID);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt); // Close the first statement

	}


	return True;
}

//【客戶查看指定商品詳細資訊】	
// 功能：取得特定商品的詳細資訊。使用 SELECT 語句根據商品編號 (pID) 從 product 表格中取得 name, price, stock, content 欄位的資料。

function getProductDetail($pID){
	global $db;
	$sql="select name, price, stock, content from product where pID=?";
	$stmt=mysqli_prepare($db,$sql);
	mysqli_stmt_bind_param($stmt,"i",$pID);
	mysqli_stmt_execute($stmt);
	$result=mysqli_stmt_get_result($stmt);
	$rows=array();
	while($r=mysqli_fetch_assoc($result)){
		$rows[]=$r;
	}
	return $rows;
}

//【客戶查看購物車內容】
// 功能：列出購物車內容。使用 SELECT 語句連接 product 表格和 cart 表格，取得商品名稱、價格和購買數量等資訊。

function listCart(){
	global $db;
	$sql="select product.pID,product.name, product.price, cart.amount from product inner join cart on product.pID = cart.pID;";
	$stmt=mysqli_prepare($db,$sql);
	mysqli_stmt_execute($stmt);
	$result=mysqli_stmt_get_result($stmt);
	$rows=array();
	while($r=mysqli_fetch_assoc($result)){
		$rows[]=$r;
	}
	return $rows;
}
//【客戶刪除購物車內容】
// 功能：刪除購物車內的特定商品。使用 DELETE 語句根據商品編號 (pID) 從 cart 表格中刪除對應的資料。

function delCart($pID){
	global $db;
	$sql="delete from cart where pID=?;";
	$stmt=mysqli_prepare($db,$sql);
	mysqli_stmt_bind_param($stmt,"i",$pID);
	mysqli_stmt_execute($stmt);
	return True;
}
// 功能：計算購物車中所有商品的總金額。使用 SELECT 語句計算購物車中每項商品的價格和數量總和，並返回總金額。
function cartTotal(){
    global $db;
    $sql="select sum(c.amount * p.price) as total_amount from cart c inner join product p on c.pID = p.pID;";
    $stmt=mysqli_prepare($db,$sql);
    mysqli_stmt_execute($stmt);
    $result=mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $row !== null ? $row : ['total_amount' => 0];
}

//【商家刪除商品項目】
//功能：刪除特定商品。使用 DELETE 語句根據商品編號 (pID) 從 product 表格中刪除對應的商品資料。

function delProduct($pID){
	global $db;
	$sql="delete from product where pID=?;";
	$stmt=mysqli_prepare($db,$sql);
	mysqli_stmt_bind_param($stmt,"i",$pID);
	mysqli_stmt_execute($stmt);
	return True;
}

//功能：更新商品資訊。使用 UPDATE 語句根據商品編號 (pID) 更新 product 表格中商品的名稱、價格、庫存和內容等資訊。

function updateProduct($pID, $name, $price, $stock, $content){
    global $db;
	$sql="update product set name=? , price=? , stock=? , content=? where pID=?;";
	$stmt=mysqli_prepare($db,$sql);
	mysqli_stmt_bind_param($stmt, "ssssi", $name, $price, $stock, $content, $pID);
	mysqli_stmt_execute($stmt);
	return True;
}
// 功能：新增商品。使用 INSERT INTO 語句將新商品的資訊插入到 product 表格中。

function addProduct($name, $price, $stock, $content){
    global $db;
	$sql="insert into product (name, price, stock, content) values (? , ? , ? , ?);";
	$stmt=mysqli_prepare($db,$sql);
	mysqli_stmt_bind_param($stmt, "siss", $name, $price, $stock, $content);
	mysqli_stmt_execute($stmt);
	return True;
}

//功能：列出所有商品的資訊。使用 SELECT * FROM product 語句從 product 表格中取得所有商品的詳細資訊。

function listProductInfo(){
	global $db;
	$sql="select * from product;";
	$stmt=mysqli_prepare($db,$sql);
	mysqli_stmt_execute($stmt);
	$result=mysqli_stmt_get_result($stmt);
	$rows=array();
	while($r=mysqli_fetch_assoc($result)){
		$rows[]=$r;
	}
	return $rows;
}
?>