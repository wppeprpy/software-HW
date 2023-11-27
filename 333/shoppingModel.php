<?php
require('dbconfig.php');

// function getJobList() {
// 	global $db;
// 	$sql = "select * from todo;";
// 	$stmt = mysqli_prepare($db, $sql ); //precompile sql指令，建立statement 物件，以便執行SQL
// 	mysqli_stmt_execute($stmt); //執行SQL
// 	$result = mysqli_stmt_get_result($stmt); //取得查詢結果

// 	$rows = array(); //要回傳的陣列
// 	while($r = mysqli_fetch_assoc($result)) {
// 		$rows[] = $r; //將此筆資料新增到陣列中
// 	}
// 	return $rows;
// }

// function addJob($jobName,$jobUrgent,$jobContent) {
// 	global $db;

// 	$sql = "insert into todo (jobName, jobUrgent, jobContent) values (?, ?, ?)"; //SQL中的 ? 代表未來要用變數綁定進去的地方
// 	$stmt = mysqli_prepare($db, $sql); //prepare sql statement
// 	mysqli_stmt_bind_param($stmt, "sss", $jobName, $jobUrgent,$jobContent); //bind parameters with variables, with types "sss":string, string ,string
// 	mysqli_stmt_execute($stmt);  //執行SQL
// 	return True;
// }

// function delJob($id) {
// 	global $db;

// 	$sql = "delete from todo where id=?;"; //SQL中的 ? 代表未來要用變數綁定進去的地方
// 	$stmt = mysqli_prepare($db, $sql); //prepare sql statement
// 	mysqli_stmt_bind_param($stmt, "i", $id); //bind parameters with variables, with types "sss":string, string ,string
// 	mysqli_stmt_execute($stmt);  //執行SQL
// 	return True;
// }

// 【客戶查看商品列表】
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
	// //結帳後，商品庫存量減一
	// $sql4 = "update product set stock = stock - 1 where pID=?";
	// $stmt = mysqli_prepare($db, $sql4);
	// mysqli_stmt_bind_param($stmt, "i", $pID);
	// mysqli_stmt_execute($stmt);
	// mysqli_stmt_close($stmt); 


	return True;
}

//【客戶查看指定商品詳細資訊】	
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
function delCart($pID){
	global $db;
	$sql="delete from cart where pID=?;";
	$stmt=mysqli_prepare($db,$sql);
	mysqli_stmt_bind_param($stmt,"i",$pID);
	mysqli_stmt_execute($stmt);
	return True;
}
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
function delProduct($pID){
	global $db;
	$sql="delete from product where pID=?;";
	$stmt=mysqli_prepare($db,$sql);
	mysqli_stmt_bind_param($stmt,"i",$pID);
	mysqli_stmt_execute($stmt);
	return True;
}

function updateProduct($pID, $name, $price, $stock, $content){
    global $db;
	$sql="update product set name=? , price=? , stock=? , content=? where pID=?;";
	$stmt=mysqli_prepare($db,$sql);
	mysqli_stmt_bind_param($stmt, "ssssi", $name, $price, $stock, $content, $pID);
	mysqli_stmt_execute($stmt);
	return True;
}

function addProduct($name, $price, $stock, $content){
    global $db;
	$sql="insert into product (name, price, stock, content) values (? , ? , ? , ?);";
	$stmt=mysqli_prepare($db,$sql);
	mysqli_stmt_bind_param($stmt, "ssss", $name, $price, $stock, $content);
	mysqli_stmt_execute($stmt);
	return True;
}

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