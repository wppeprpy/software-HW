<?php
// 引入必要的模型和其他文件
require_once('UserModel.php');
require_once('models/ProductModel.php');
require_once('models/CartModel.php');
require_once('models/OrderModel.php');
require_once('views/productListView.php');
require_once('views/productDetailsView.php');
require_once('views/cartView.php');
require_once('views/orderHistoryView.php');

class ShoppingController {
    public function showLoginForm() {
        include('loginView.php');
    }

    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 檢查是否提交了登入表單
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];

                // 驗證用戶是否存在
                $isAuthenticated = $userModel->authenticate($username, $password);

                if ($isAuthenticated) {
                    // 如果登入成功，會跳轉到商品頁面
                    session_start();
                    $_SESSION['loggedIn'] = true;
                    $_SESSION['username'] = $username;

                    // 跳轉到商品頁面
                    header('Location: productListView.php');
                    exit;
                } else {
                    // 登入失敗，顯示錯誤消息或定向到登入頁面
                    echo "Login failed. Please try again.";
                    // 或者定向到登入頁面
                    // header('Location: loginView.php');
                    // exit;
                }
            } else {
                // 缺少用戶名稱或密碼
                echo "Username or password missing.";
            }
        }
    }

    public function logout() {
        session_start();
        // 清除登入狀態
        $_SESSION = array();
        session_destroy();

        // 可以跳轉到登入頁面
        header('Location: loginView.php');
        exit;
    }
}
	
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
	header('Location: productListView.php?action=displayProductList');
        exit;
    }

    public function removeFromCart($productId) {
        // 從購物車中移除商品
        CartModel::removeProductFromCart($productId);

        // 可能重定向到購物車頁面或其他頁面
	header('Location: productListView.php?action=viewCart');
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
    public function checkout($userId) {
        if ($_SESSION['loggedIn']) {
            // 獲取購物車內的商品
            $cartItems = CartModel::getCartItems();

            // 創建訂單
            $orderId = OrderModel::createOrder($cartItems, $userId);

            // 清空購物車內容
            CartModel::clearCart();

            // 顯示結帳成功頁面或其他相關操作
            echo "Checkout successful. Your order ID is: $orderId";
        } else {
            // 未登錄，可能重新導向到登錄頁面
            // header('Location: login.php');
            // exit;
            echo "Please login to proceed with checkout.";
        }
    }
    // 其他可能的功能，比如用戶登錄、結帳等...
}
// 根據請求處理相應的功能(控制台)
$shoppingController = new ShoppingController();

if (isset($_POST['submit'])) {
    $shoppingController->processLogin();
} else if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $shoppingController->logout();
} else {
    $shoppingController->displayProductList();
}
?>
