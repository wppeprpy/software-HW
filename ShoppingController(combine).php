<?php
// 引入必要的模型和其他文件
require_once('models/ProductModel.php');
require_once('models/CartModel.php');
require_once('models/OrderModel.php');
require_once('views/productListView.php');
require_once('views/productDetailsView.php');
require_once('views/cartView.php');
require_once('views/orderHistoryView.php');

class ShoppingController {
    public function displayProductList() {
        // 獲取所有商品列表
        $products = ProductModel::getAllProducts();

        // 顯示商品列表視圖
	renderProductList($products);
        // productListView.php 文件負責顯示商品列表 $products
    }

    public function displayProductDetails($productId) {
        // 獲取特定商品詳細信息
        $product = ProductModel::getProductById($productId);

        // 顯示單個商品的詳細信息視圖
	renderProductDetails($product);
        // productDetailsView.php 文件負責顯示單個商品 $product 的詳細信息
    }

    public function addToCart($productId) {
        // 將商品添加到購物車
        CartModel::addProductToCart($productId);

        // 可能重定向到購物車頁面或其他頁面
	header('Location: index.php?action=displayProductList');
        exit;
    }

    public function removeFromCart($productId) {
        // 從購物車中移除商品
        CartModel::removeProductFromCart($productId);

        // 可能重定向到購物車頁面或其他頁面
	header('Location: index.php?action=viewCart');
        exit;
    }

    public function viewCart() {
        // 獲取購物車內的所有商品
        $cartItems = CartModel::getCartItems();

        // 可能計算總價格或其他購物車相關邏輯
        $totalPrice = CartModel::calculateTotalPrice($cartItems);

        // 顯示購物車視圖
	renderCartView($cartItems);
        // cartView.php 文件負責顯示購物車內的商品 $cartItems 和總價格 $totalPrice
    }
	
	public function viewOrderHistory($userId) {
        // 獲取用戶的訂單歷史
        $orders = OrderModel::getOrderHistory($userId);

        // 顯示訂單歷史視圖
        renderOrderHistory($orders);
	}

    // 其他可能的功能，比如用戶登錄、結帳等...

}
?>
