<?php
include 'config.php';

// Handle adding to cart
if(isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    if(!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    if(isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]++;
    } else {
        $_SESSION['cart'][$item_id] = 1;
    }
    header("Location: index.php");
    exit();
}

// Manual Image Mapping Helper Function
function getFoodImage($name) {
    $name = strtolower($name);
    if (strpos($name, 'burger') !== false) {
        return "images/burger.jpg";
    } elseif (strpos($name, 'pizza') !== false) {
        return "images/pizza.jpg";
    } elseif (strpos($name, 'sandwich') !== false) {
        return "images/sandwich.jpg";
    } elseif (strpos($name, 'fries') !== false) {
        return "images/fries.jpg";
    }
    return "images/default.jpg"; 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodExpress - Premium Menu</title>
    <link href="https://googleapis.com" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #f8fafc;
            color: #1e293b;
            overflow-x: hidden;
        }

        /* Premium Crimson Red Gradient Header */
        header {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            padding: 18px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(185, 28, 28, 0.15);
        }

        .logo-container {
            font-size: 24px;
            font-weight: 800;
            color: #ffffff;
            text-decoration: none;
        }

        nav {
            display: flex;
            align-items: center;
            gap: 32px;
        }

        nav a {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.2s ease;
            position: relative;
        }

        nav a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: #ffffff;
            transition: width 0.2s ease;
        }

        nav a:hover::after {
            width: 100%;
        }

        .cart-badge {
            background: #ffffff;
            color: #dc2626;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 700;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .cart-badge:hover {
            background: #f8fafc;
            transform: translateY(-2px) scale(1.03);
        }

        .user-tag {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 500;
        }

        .logout-btn {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
            text-decoration: none;
        }

        .logout-btn:hover {
            color: #fca5a5;
        }
        .container {
            max-width: 1300px;
            margin: 40px auto;
            padding: 0 24px;
        }

        .section-title {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 35px;
            position: relative;
            padding-left: 16px;
        }

        .section-title::before {
            content: '';
            position: absolute;
            left: 0;
            top: 4px;
            bottom: 4px;
            width: 5px;
            background: #dc2626;
            border-radius: 4px;
        }

        /* Forces exactly 4 columns side by side on big monitors */
        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        /* Interactive Menu Cards with Hover Effects */
        .card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: #fca5a5;
        }

        .card-img-wrapper {
            width: 100%;
            height: 160px;
            overflow: hidden;
            position: relative;
            background-color: #f1f5f9;
        }

        .card-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .card:hover .card-img-wrapper img {
            transform: scale(1.08);
        }

        .card-content {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            justify-content: space-between;
        }

        .card h3 {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
            transition: color 0.2s ease;
        }

        .card:hover h3 {
            color: #dc2626;
        }

        .card p {
            font-size: 13px;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 20px;
            min-height: 58px;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #f1f5f9;
            padding-top: 14px;
        }

        .price-tag {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
        }

        .btn {
            background: #dc2626;
            color: #ffffff;
            padding: 9px 16px;
            border: none;
            border-radius: 10px;
            text-decoration: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.2);
        }

        .btn:hover {
            background: #b91c1c;
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(185, 28, 28, 0.3);
        }

        /* Responsive Breakpoints for Tablet and Mobile devices */
        @media (max-width: 1024px) {
            .grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 640px) {
            .grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <header>
        <a href="index.php" class="logo-container">🍕 FoodExpress</a>
        <nav>
            <a href="index.php">Menu</a>
            <a href="orders.php">My Orders</a>
            <a href="cart.php" class="cart-badge">🛍️ Cart (<?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>)</a>
            <?php if(isset($_SESSION['user_name'])): ?>
                <div class="user-tag">👤 <?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
                <a href="logout.php" class="logout-btn">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="container">
        <h2 class="section-title">Explore Our Curated Menu</h2>
        <div class="grid">
            <?php
            $items = $conn->query("SELECT * FROM food_items");
            while($row = $items->fetch_assoc()) {
                $local_image = getFoodImage($row['name']);
                
                echo "<div class='card'>";
                echo "<div class='card-img-wrapper'>";
                echo "<img src='" . $local_image . "' alt='" . htmlspecialchars($row['name']) . "' onerror=\"this.src='https://picsum.photos';\">";
                echo "</div>";
                echo "<div class='card-content'>";
                echo "<div>";
                echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                echo "</div>";
                echo "<div class='card-footer'>";
                echo "<span class='price-tag'>₹" . number_format($row['price'], 0) . "</span>";
                echo "<form method='POST'>
                        <input type='hidden' name='item_id' value='" . $row['id'] . "'>
                        <button type='submit' name='add_to_cart' class='btn'>Add to Cart</button>
                      </form>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>
    </div>

</body>
</html>
