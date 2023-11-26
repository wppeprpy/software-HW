	<?php
	require("dbconfig.php");

	$id=(int)$_POST['ProductID'];
	if ($id <=0) {
		echo "error!! empty ID";
		exit(0);
	} 	
	$sql = "select  ProductID, ProductName, Description,Price, StockQuantity from products where ProductID=(?);"; 
	//SQL中的 ? 代表未來要用變數綁定進去的地方
	$stmt = mysqli_prepare($db, $sql ); //precompile sql指令，建立statement 物件，以便執行SQL
	mysqli_stmt_bind_param($stmt, "i", $id); //綁定參數到變數 $id 上, 型態為 i (integer)
	mysqli_stmt_execute($stmt); //執行SQL
	$result = mysqli_stmt_get_result($stmt); //取得查詢結果
	if ($rs=mysqli_fetch_array($result)) { //將查詢結果取出轉成註標型陣列 (類似python 的dict)

	//若結果不為空值，代表有找到，將結果帶入html表單中作為預設值
?>
<form id="myForm">
	<input type='hidden' name='ProductID' value="<?php echo $id;?>" />
	名稱:<input name="ProductName" type="text"  value="<?php echo $rs['ProductName'];?>" /> <br>
	敘述:<input name="Description" type="text"  value="<?php echo $rs['Description'];?>" /> <br>
	錢:<input name="Price" type="number"  value="<?php echo $rs['Price'];?>" /> <br>
	庫存:<input name="StockQuantity" type="number"  value="<?php echo $rs['StockQuantity'];?>" /> <br>
	<input type='button' onClick="postForm('修改商品.php')" value="save"> 
</form>

<?php
	} 
	else {
		echo "cannot find the message to edit.";
	}
?>