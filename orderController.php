<?php
require_once('models/OrderModel.php');
require_once('views/orderHistory.php');

class OrderController {
    public function createOrder($cartItems, $userId) {
        // 創建訂單
        $orderId = OrderModel::createOrder($cartItems, $userId);

        // 返回創建的訂單ID或其他信息
        return $orderId;
    }

    public function displayOrderHistory($userId) {
        // 獲取用戶的訂單歷史
        $orders = OrderModel::getOrderHistory($userId);

        // 顯示訂單歷史視圖
        orderHistory($orders); // Renders order history view
    }

    // 其他訂單相關的方法...
}
?>
