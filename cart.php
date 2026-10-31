<?php
include 'config.php';

// --- NEW FEATURE: HANDLE REMOVING SINGLE ITEM FROM CART ---
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $remove_id = $_GET['id'];
    if (isset($_SESSION['cart'][$remove_id])) {
        unset($_SESSION['cart'][$remove_id]); // Delete item from session array
    }
    header("Location: cart.php"); // Refresh screen to show updated totals
    exit();
}

// Handle placing an order submission action
if (isset($_POST['checkout'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    
    $user_id = $_SESSION['user_id'];
    $total = $_POST['total_amount'];
    
    // Insert parent transaction row
    $conn->query("INSERT INTO orders (user_id, total_amount) VALUES ('$user_id', '$total')");
    $order_id = $conn->insert_id;
    
    // Map individual cart keys out to database records
    foreach ($_SESSION['cart'] as $id => $qty) {
        $item_data = $conn->query("SELECT price FROM food_items WHERE id=$id")->fetch_assoc();
        $price = $item_data['price'];
        $conn->query("INSERT INTO order_details (order_id, item_id, quantity, price) VALUES ('$order_id', '$id', '$qty', '$price')");
    }
    
    unset($_SESSION['cart']);
    echo "<script>alert('Order placed successfully via Cash on Delivery!'); window.location.href='index.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - FoodExpress</title>
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: #f8fafc; color: #1e293b; }
        
        header {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            padding: 18px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(185, 28, 28, 0.15);
        }
        .logo-container { font-size: 24px; font-weight: 800; color: #ffffff; text-decoration: none; }
        .back-menu { color: #ffffff; text-decoration: none; font-weight: 600; background: rgba(255,255,255,0.2); padding: 8px 18px; border-radius: 12px; transition: all 0.2s; cursor: pointer; }
        .back-menu:hover { background: #ffffff; color: #dc2626; transform: translateY(-2px); }
        
        .container { max-width: 1000px; margin: 50px auto; padding: 0 24px; }
        h2 { font-size: 28px; font-weight: 800; margin-bottom: 30px; }
        
        .cart-box { background: white; border-radius: 20px; padding: 30px; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { padding: 16px; color: #64748b; font-weight: 600; border-bottom: 2px solid #edf2f7; }
        td { padding: 20px 16px; border-bottom: 1px solid #edf2f7; font-size: 16px; vertical-align: middle; }
        
        .grand-total-row { background: #fff5f5; font-weight: 700; color: #dc2626; }
        
        /* Action Button Styling */
        .remove-link {
            color: #ef4444;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            padding: 6px 12px;
            border-radius: 8px;
            background: #fef2f2;
            transition: all 0.2s ease;
        }
        .remove-link:hover {
            background: #ef4444;
            color: #ffffff;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);
        }

        .btn { background: #dc2626; color: white; padding: 14px 28px; border: none; border-radius: 12px; cursor: pointer; font-weight: 700; font-size: 16px; width: 100%; margin-top: 25px; transition: all 0.3s; box-shadow: 0 4px 6px rgba(220, 38, 38, 0.2); text-align: center; display: inline-block; text-decoration: none; }
        .btn:hover { background: #b91c1c; transform: scale(1.01); box-shadow: 0 8px 16px rgba(185, 28, 28, 0.3); }
    </style>
</head>
<body>
    <header>
        <a href="index.php" class="logo-container">🍕 FoodExpress</a>
        <div style="color: rgba(255, 255, 255, 0.7); font-size: 12px; font-weight: 500; margin-top: 2px; letter-spacing: 0.3px;">
                Designed by Sevabrata Dutta
            </div>
        <a href="index.php" class="back-menu">Back to Menu</a>
    </header>
    <div class="container">
        <h2>Your Selected Dishes</h2>
        <div class="cart-box">
            <?php if (!empty($_SESSION['cart'])): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $grand_total = 0;
                        foreach ($_SESSION['cart'] as $id => $qty): 
                            $item = $conn->query("SELECT * FROM food_items WHERE id=$id")->fetch_assoc();
                            if ($item): 
                                $subtotal = $item['price'] * $qty; 
                                $grand_total += $subtotal;
                        ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($item['name']); ?></strong></td>
                            <td><?php echo (int)$qty; ?></td>
                            <td>₹<?php echo number_format($item['price'], 2); ?></td>
                            <td>₹<?php echo number_format($subtotal, 2); ?></td>
                            <td style="text-align: center;">
                                <!-- Triggers the PHP unset remove functionality via URL parameters -->
                                <a href="cart.php?action=remove&id=<?php echo $id; ?>" class="remove-link" onclick="return confirm('Remove this item from your cart?');">✕ Remove</a>
                            </td>
                        </tr>
                        <?php endif; endforeach; ?>
                        <tr class="grand-total-row">
                            <td colspan="3" align="right">Grand Total:</td>
                            <td colspan="2">₹<?php echo number_format($grand_total, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
                <form method="POST">
                    <input type="hidden" name="total_amount" value="<?php echo $grand_total; ?>">
                    <button type="submit" name="checkout" class="btn">Confirm & Place Order (COD)</button>
                </form>
            <?php else: ?>
                <p style="text-align:center; color:#64748b; padding:20px;">Your cart is empty. <a href="index.php" style="color:#dc2626; font-weight:600; text-decoration:none;">Go add food!</a></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
