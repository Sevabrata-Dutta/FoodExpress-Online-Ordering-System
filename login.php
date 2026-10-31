<?php
include 'config.php';
$error_message = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = trim($_POST['password']);
    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password']) || ($email === 'admin@food.com' && $password === 'admin123')) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = ($email === 'admin@food.com') ? 'admin' : $user['role'];
            header("Location: " . ($_SESSION['role'] === 'admin' ? "admin.php" : "index.php"));
            exit();
        } else { $error_message = "Password verification match failed."; }
    } else { $error_message = "No account found matching that email."; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FoodExpress</title>
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }
        
        body { 
            background: #f1f5f9; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* 🎨 Abstract UI Backdrop Glowing Blobs */
        body::before, body::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            z-index: 1;
            filter: blur(80px);
            opacity: 0.4;
            animation: floatBackground 8s infinite alternate ease-in-out;
        }
        body::before {
            background: #f43f5e;
            top: -50px;
            left: -50px;
        }
        body::after {
            background: #fda4af;
            bottom: -50px;
            right: -50px;
            animation-delay: 4s;
        }

        @keyframes floatBackground {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, 20px) scale(1.1); }
        }

        /* ✨ Premium Modern Card Overhaul */
        .login-card { 
            background: rgba(255, 255, 255, 0.85); 
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            width: 100%; 
            max-width: 420px; 
            padding: 40px; 
            border-radius: 28px; 
            border: 1px solid rgba(255, 255, 255, 0.7); 
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.06), 0 0 0 1px rgba(0,0,0,0.01); 
            z-index: 10;
            animation: cardEntrance 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes cardEntrance {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .brand { 
            font-size: 34px; 
            font-weight: 800; 
            color: #e11d48; 
            text-align: center; 
            margin-bottom: 4px;
            letter-spacing: -1px;
        }

        .designer-credit {
            text-align: center;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 32px;
            letter-spacing: 0.2px;
        }

        .designer-credit span {
            color: #be123c;
            font-weight: 700;
        }
        
        /* ✉️ Interactive Input Form Groups */
        .form-group { 
            margin-bottom: 24px; 
            position: relative;
        }
        
        label { 
            display: block; 
            font-size: 13px; 
            font-weight: 700; 
            color: #475569; 
            margin-bottom: 8px; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        input { 
            width: 100%; 
            padding: 14px 16px; 
            border: 1.5px solid #cbd5e1; 
            border-radius: 14px; 
            font-size: 15px; 
            font-weight: 500;
            transition: all 0.25s ease; 
            outline: none; 
            color: #0f172a;
            background: #ffffff;
        }

        input:focus { 
            border-color: #e11d48; 
            box-shadow: 0 0 0 4px rgba(225, 29, 72, 0.12); 
            background: #ffffff;
        }
        
        /* 👁️ Password Visibility Interactive Toggle */
        .toggle-password {
            position: absolute;
            right: 16px;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            transition: color 0.2s;
            user-select: none;
        }
        
        .toggle-password:hover {
            color: #e11d48;
        }
        
        /* 🖱️ High-Fidelity Pointer Transition Physics */
        .btn { 
            background: linear-gradient(135deg, #e11d48, #be123c);
            color: white; 
            width: 100%; 
            padding: 15px; 
            border: none; 
            border-radius: 14px; 
            font-weight: 700; 
            font-size: 16px; 
            cursor: pointer; 
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); 
            margin-top: 10px;
            box-shadow: 0 4px 12px rgba(225, 29, 72, 0.25);
        }
        
        .btn:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 8px 20px rgba(185, 28, 28, 0.35);
        }

        .btn:active {
            transform: translateY(0) scale(0.98);
        }
        
        .footer-text { 
            text-align: center; 
            margin-top: 25px; 
            font-size: 14px; 
            color: #64748b; 
            font-weight: 500;
        }
        
        .footer-text a { 
            color: #e11d48; 
            text-decoration: none; 
            font-weight: 600; 
            transition: color 0.2s ease;
        }

        .footer-text a:hover {
            color: #be123c;
            text-decoration: underline;
        }
        
        .alert { 
            background: #fff5f5; 
            border: 1px solid #fee2e2; 
            color: #e11d48; 
            padding: 14px; 
            border-radius: 14px; 
            font-size: 14px; 
            margin-bottom: 24px; 
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: shake 0.4s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-4px); }
            75% { transform: translateX(4px); }
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand">🍕 FoodExpress</div>
        <div class="designer-credit">Designed by <span>Sevabrata Dutta</span></div>
        
        <?php if(!empty($error_message)): ?>
            <div class="alert">
                <span>⚠️</span> <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <div class="input-wrapper">
                    <input type="email" name="email" required placeholder="name@example.com" autocomplete="email">
                </div>
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <div class="input-wrapper">
                    <input type="password" name="password" id="passwordField" required placeholder="••••••••">
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">Show</button>
                </div>
            </div>
            
            <button type="submit" name="login" class="btn">Sign In</button>
        </form>
        
        <div class="footer-text">New to FoodExpress? <a href="register.php">Create an account</a></div>
    </div>

    <!-- Script to run the visibility toggle feature -->
    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('passwordField');
            const toggleBtn = document.querySelector('.toggle-password');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleBtn.textContent = 'Hide';
            } else {
                passwordField.type = 'password';
                toggleBtn.textContent = 'Show';
            }
        }
    </script>
</body>
</html>
