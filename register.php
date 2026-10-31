<?php
include 'config.php';
$alert_message = "";
$alert_type = "";

if(isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT id FROM users WHERE email='$email'");
    if($check->num_rows > 0) {
        $alert_message = "This email address is already registered!";
        $alert_type = "error";
    } else {
        $conn->query("INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");
        echo "<script>alert('Account created successfully! Please login.'); window.location.href='login.php';</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - FoodExpress</title>
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

        /* 🎨 Abstract Ambient UI Backdrop Glowing Blobs */
        body::before, body::after {
            content: '';
            position: absolute;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            z-index: 1;
            filter: blur(80px);
            opacity: 0.4;
            animation: floatBackground 8s infinite alternate ease-in-out;
        }
        body::before {
            background: #f43f5e;
            top: -60px;
            left: -60px;
        }
        body::after {
            background: #fda4af;
            bottom: -60px;
            right: -60px;
            animation-delay: 4s;
        }

        @keyframes floatBackground {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, 20px) scale(1.1); }
        }

        /* ✨ Frosted Glassmorphism Card Frame */
        .register-card { 
            background: rgba(255, 255, 255, 0.85); 
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            width: 100%; 
            max-width: 440px; 
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
            margin-bottom: 30px;
            letter-spacing: 0.2px;
        }

        .designer-credit span {
            color: #be123c;
            font-weight: 700;
        }
        
        .form-group { 
            margin-bottom: 22px; 
            position: relative;
        }
        
        label { 
            display: block; 
            font-size: 12px; 
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
            padding: 13px 16px; 
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
        }
        
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
        
        /* 🖱️ Interaction Physics */
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
            margin-top: 12px;
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
            margin-bottom: 22px; 
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

    <div class="register-card">
        <div class="brand">🍕 FoodExpress</div>
        <div class="designer-credit">Designed by <span>Sevabrata Dutta</span></div>
        
        <?php if(!empty($alert_message)): ?>
            <div class="alert">
                <span>⚠️</span> <?php echo $alert_message; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <div class="input-wrapper">
                    <input type="text" name="name" required placeholder="John Doe" autocomplete="name">
                </div>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <div class="input-wrapper">
                    <input type="email" name="email" required placeholder="name@example.com" autocomplete="email">
                </div>
            </div>
            
            <div class="form-group">
                <label>Create Password</label>
                <div class="input-wrapper">
                    <input type="password" name="password" id="regPasswordField" required placeholder="••••••••">
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">Show</button>
                </div>
            </div>
            
            <button type="submit" name="register" class="btn">Create Account</button>
        </form>
        
        <div class="footer-text">Already have an account? <a href="login.php">Sign In here</a></div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('regPasswordField');
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
