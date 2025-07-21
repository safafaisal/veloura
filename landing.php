<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Veloura</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
            margin: 0;
            /* background: url('images/bg.jpg') no-repeat center center fixed !important;
            background-size: cover !important; */
            position: relative;
            color: #333;
        }
        /*
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(255,255,255,0.5);
            z-index: 0;
            pointer-events: none;
        }
        */
        .hero {
            background: url('images/flower.png.jpg') center center no-repeat;
            background-size: cover;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0 0 30px 30px;
            box-shadow: 0 8px 32px rgba(216,27,96,0.08);
            position: relative;
            z-index: 2;
        }
        .hero h1 {
            color: #fff;
            font-size: 48px;
            font-weight: bold;
            background: linear-gradient(90deg, #d81b60 0%, #ffb6c1 100%);
            padding: 18px 48px;
            border-radius: 18px;
            box-shadow: 0 2px 12px rgba(216,27,96,0.12);
            letter-spacing: 2px;
            text-shadow: 0 2px 8px #c2185b44;
            margin: 0;
        }
        .landing-content {
            max-width: 600px;
            margin: 60px auto 0 auto;
            background: linear-gradient(135deg, #fff 70%, #fce4ec 100%);
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(216,27,96,0.10);
            padding: 40px 32px 32px 32px;
            text-align: center;
            position: relative;
            z-index: 1;
        }
        .landing-content p {
            font-size: 18px;
            color: #666;
            margin-bottom: 24px;
        }
        .btn {
            background: linear-gradient(90deg, #d81b60 0%, #ffb6c1 100%);
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 17px;
            box-shadow: 0 2px 8px rgba(216,27,96,0.10);
            margin: 0 10px;
            transition: 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background: linear-gradient(90deg, #ad1457 0%, #f8bbd0 100%);
            color: #fff;
            transform: scale(1.04);
            box-shadow: 0 4px 16px rgba(216,27,96,0.18);
        }
    </style>
</head>
<body style="background: url('images/bg.jpg') no-repeat center center fixed; background-size: cover;">
    <?php // include 'navbar.php'; ?>
    <div class="hero">
        <h1>VELOURA</h1>
    </div>
    <div class="landing-content">
        <h2>Welcome to Veloura Perfumes</h2>
        <p>Discover our exclusive range of perfumes for men and women. Sign up or log in to explore our collection and enjoy a personalized shopping experience!</p>
        <a href="login.php" class="btn">Login</a>
        <a href="register.php" class="btn">Register</a>
    </div>
</body>
</html> 