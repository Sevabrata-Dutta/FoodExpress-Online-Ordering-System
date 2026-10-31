<?php
include 'config.php';

// Force protection: Prevent standard customers from sneaking into the administrative office panel
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("<div style='font-family:sans-serif; text-align:center; padding:50px;'><h2>Access Denied. Internal Administration Only.</h2><a href='login.php'>Return to Safety</a></div>");
}

// Update order status workflow routing
if(isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    $conn->query("UPDATE orders SET status='$new_status' WHERE id=$order_id");
    header("Location: admin.php"); // Refresh screen immediately to redraw badges
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operations Center - FoodExpress</title>
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: #f8fafc; color: #1e293b; }
        
        /* Matching Premium Crimson Red Header */
        header {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            padding: 18px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(185, 28, 28, 0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .logo-container { font-size: 24px; font-weight: 800; color: #ffffff; text-decoration: none; display: flex; align-items: center; gap: 8px; }
        .logout-btn { color: rgba(255, 255, 255, 0.8); text-decoration: none; font-weight: 600; background: rgba(255,255,255,0.2); padding: 8px 18px; border-radius: 12px; transition: all 0.2s; cursor: pointer; }
        .logout-btn:hover { background: #ffffff; color: #dc2626; transform: translateY(-2px); }
        
        .container { max-width: 1200px; margin: 40px auto; padding: 0 24px; }
        
        /* Metric Summary Cards for a true Backoffice feel */
        .metrics-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 35px; }
        .metric-card { background: white; padding: 24px; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }
        .metric-card h3 { font-size: 14px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
        .metric-card p { font-size: 28px; font-weight: 800; color: #0f172a; }

        h2 { font-size: 26px; font-weight: 800; color: #0f172a; margin-bottom: 25px; }
        
        /* Premium Operations Data Workspace Table Matrix */
        .table-box { background: white; border-radius: 20px; padding: 25px; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { padding: 16px; color: #64748b; font-weight: 600; border-bottom: 2px solid #edf2f7; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 18px 16px; border-bottom: 1px solid #edf2f7; font-size: 15px; color: #334155; vertical-align: middle; }
        tr:hover { background-color: #f8fafc; }
        
        /* High-Fidelity Professional Status Micro-Badges */
        .status-badge { display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 700; text-align: center; }
        .Pending { background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
        .Preparing { background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .Out_for_Delivery { background-color: #faf5ff; color: #6b21a8; border: 1px solid #e9d5ff; } /* Handles enum spaces */
        .Delivered { background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
        
        /* Sleek Status Selection Dropdown elements */
        select { padding: 8px 12px; border-radius: 10px; border: 1px solid #cbd5e1; background-color: #ffffff; font-weight: 500; color: #334155; outline: none; transition: border 0.2s; cursor: pointer; }
        select:focus { border-color: #dc2626; }
        
        .update-btn { background: #0f172a; color: white; padding: 8px 14px; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; font-size: 13px; margin-left: 6px; transition: all 0.2s; }
        .update-btn:hover { background: #dc2626; transform: scale(1.03); }
    </style>
</head>
<body>

    <header>
        <div>
            <a href="index.php" class="logo-container">📊 FoodExpress Operations Center</a>
            <div style="color: rgba(255, 255, 255, 0.7); font-size: 12px; font-weight: 500; margin-top: 2px; letter-spacing: 0.3px;">
                Designed by Sevabrata Dutta
            </div>
        </div>
        <a href="logout.php" class="logout-btn">Secure Logout</a>
    </header>

    <div class="container">
        
        <!-- Live Management Insights Row Counters -->
        <div class="metrics-grid">
            <div class="metric-card">
                <h3>Total Orders Placed</h3>
                <p><?php echo $conn->query("SELECT id FROM orders")->num_rows; ?></p>
            </div>
            <div class="metric-card">
                <h3>Pending Kitchen Load</h3>
                <p style="color: #b45309;"><?php echo $conn->query("SELECT id FROM orders WHERE status='Pending'")->num_rows; ?></p>
            </div>
            <div class="metric-card">
                <h3>Total Revenue Generated</h3>
                <p style="color: #047857;">₹<?php 
                    $rev = $conn->query("SELECT SUM(total_amount) as total FROM orders")->fetch_assoc();
                    echo number_format((float)$rev['total'], 2);
                ?></p>
            </div>
        </div>

        <h2>Live Orders Realtime Pipeline</h2>
        
        <div class="table-box">
            <?php
            $orders = $conn->query("SELECT o.*, u.name as customer_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.id DESC");
            if ($orders->num_rows > 0):
            ?>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Bill Amount</th>
                            <th>Timestamp</th>
                            <th>Pipeline Status</th>
                            <th>Dispatch Control</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while($row = $orders->fetch_assoc()) {
                            // Convert spaces in status text for CSS class compatibility mapping
                            $css_status = str_replace(' ', '_', $row['status']);
                            
                            echo "<tr>";
                            echo "<td><strong>#".$row['id']."</strong></td>";
                            echo "<td>".htmlspecialchars($row['customer_name'])."</td>";
                            echo "<td style='font-weight:700; color:#0f172a;'>₹" . number_format($row['total_amount'], 2) . "</td>";
                            echo "<td style='color:#64748b; font-size:13px;'>".date('d M Y, h:i A', strtotime($row['created_at']))."</td>";
                            echo "<td><span class='status-badge ".$css_status."'>".$row['status']."</span></td>";
                            echo "<td>
                                    <form method='POST' style='display:flex; align-items:center;'>
                                        <input type='hidden' name='order_id' value='".$row['id']."'>
                                        <select name='status'>
                                            <option value='Pending' ".($row['status']=='Pending'?'selected':'').">Pending</option>
                                            <option value='Preparing' ".($row['status']=='Preparing'?'selected':'').">Preparing</option>
                                            <option value='Out for Delivery' ".($row['status']=='Out for Delivery'?'selected':'').">Out for Delivery</option>
                                            <option value='Delivered' ".($row['status']=='Delivered'?'selected':'').">Delivered</option>
                                        </select>
                                        <button type='submit' name='update_status' class='update-btn'>Update</button>
                                    </form>
                                  </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align:center; color:#64748b; padding:20px;">No incoming customer transactions have logged into the system pipeline yet.</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
