<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track My Orders - FoodExpress</title>
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
        .back-menu { color: #ffffff; text-decoration: none; font-weight: 600; background: rgba(255,255,255,0.2); padding: 8px 18px; border-radius: 12px; transition: all 0.2s; }
        .back-menu:hover { background: #ffffff; color: #dc2626; transform: translateY(-2px); }
        
        .container { max-width: 900px; margin: 50px auto; padding: 0 24px; }
        h2 { font-size: 28px; font-weight: 800; margin-bottom: 30px; }
        
        .order-card { background: white; border-radius: 20px; padding: 25px; margin-bottom: 25px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }
        .order-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 15px; margin-bottom: 15px; }
        .order-id { font-weight: 700; font-size: 18px; }
        .order-date { color: #64748b; font-size: 14px; }
        
        /* Interactive Progress Trackers */
        .progress-bar-container { display: flex; justify-content: space-between; position: relative; margin: 30px 0 10px 0; }
        .progress-line { position: absolute; top: 15px; left: 5%; right: 5%; height: 4px; background: #e2e8f0; z-index: 1; }
        .progress-line-fill { position: absolute; top: 15px; left: 5%; height: 4px; background: #dc2626; z-index: 2; transition: width 0.5s ease; }
        
        .step { position: relative; z-index: 3; text-align: center; width: 25%; }
        .circle { width: 32px; height: 32px; border-radius: 50%; background: #e2e8f0; color: #64748b; display: flex; align-items: center; justify-content: center; margin: 0 auto 8px auto; font-weight: 700; font-size: 14px; transition: all 0.3s; }
        .step-text { font-size: 13px; font-weight: 600; color: #64748b; }
        
        /* Active States based on DB tracking records */
        .step.active .circle { background: #dc2626; color: white; box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.2); }
        .step.active .step-text { color: #0f172a; font-weight: 700; }
    </style>
</head>
<body>
    <header>
        <div>
            <a href="index.php" class="logo-container">🍕 FoodExpress</a>
            <div style="color: rgba(255, 255, 255, 0.7); font-size: 12px; font-weight: 500; margin-top: 2px; letter-spacing: 0.3px;">
                Designed by Sevabrata Dutta
            </div>
        </div>
        <a href="index.php" class="back-menu">Back to Menu</a>
    </header>
    
    <div class="container">
        <h2>Live Order Tracking</h2>
        
        <?php
        $orders = $conn->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY id DESC");
        if($orders->num_rows > 0):
            while($order = $orders->fetch_assoc()):
                $status = $order['status'];
                
                // Calculate progress line fill percentage widths
                $fill_width = "0%";
                if ($status == 'Preparing') $fill_width = '33%';
                if ($status == 'Out for Delivery') $fill_width = '66%';
                if ($status == 'Delivered') $fill_width = '90%';
        ?>
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <span class="order-id">Order #<?php echo $order['id']; ?></span>
                        <div class="order-date"><?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?></div>
                    </div>
                    <div style="font-weight: 800; font-size: 18px; color: #0f172a;">₹<?php echo number_format($order['total_amount'], 2); ?></div>
                </div>
                
                <!-- Live Pipeline Map View -->
                <div class="progress-bar-container">
                    <div class="progress-line"></div>
                    <div class="progress-line-fill" style="width: <?php echo $fill_width; ?>;"></div>
                    
                    <div class="step active">
                        <div class="circle">1</div>
                        <div class="step-text">Placed</div>
                    </div>
                    <div class="step <?php echo ($status=='Preparing' || $status=='Out for Delivery' || $status=='Delivered') ? 'active' : ''; ?>">
                        <div class="circle">2</div>
                        <div class="step-text">Preparing</div>
                    </div>
                    <div class="step <?php echo ($status=='Out for Delivery' || $status=='Delivered') ? 'active' : ''; ?>">
                        <div class="circle">3</div>
                        <div class="step-text">Out for Delivery</div>
                    </div>
                    <div class="step <?php echo ($status=='Delivered') ? 'active' : ''; ?>">
                        <div class="circle">4</div>
                        <div class="step-text">Delivered</div>
                    </div>
                </div>
            </div>
        <?php 
            endwhile;
        else: 
        ?>
            <p style="text-align:center; color:#64748b; padding:40px; background:white; border-radius:20px;">You haven't placed any food orders yet!</p>
        <?php endif; ?>
    </div>
</body>
</html>
