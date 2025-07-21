<?php
session_start();
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart - Veloura</title>
    <style>
        body {
            font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
            padding: 40px;
            background-color: #fff0f5;
            color: #333;
        }

        h1 {
            color: #d81b60;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 0 2px 8px #d81b6044;
        }

        .cart {
            max-width: 900px;
            margin: auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(216,27,96,0.10);
            padding: 32px 24px 24px 24px;
        }

        .item {
            display: flex;
            justify-content: space-between;
            padding: 18px 0;
            border-bottom: 1px solid #f8bbd0;
            align-items: center;
        }

        .item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 8px #d81b6044;
        }

        .item-name {
            flex: 2;
            margin-left: 20px;
            color: #d81b60;
            font-weight: 600;
            font-size: 18px;
        }

        .item-price,
        .item-qty,
        .item-remove {
            flex: 1;
            text-align: center;
        }

        .total {
            text-align: right;
            margin-top: 30px;
            font-weight: bold;
            font-size: 22px;
            color: #ad1457;
            text-shadow: 0 2px 8px #d81b6044;
        }

        .btn {
            margin-top: 20px;
            padding: 12px 28px;
            background: linear-gradient(90deg, #d81b60 0%, #ffb6c1 100%);
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 2px 8px rgba(216,27,96,0.10);
            transition: 0.2s;
        }

        .btn:hover {
            background: linear-gradient(90deg, #ad1457 0%, #f8bbd0 100%);
            color: #fff;
            transform: scale(1.04);
            box-shadow: 0 4px 16px rgba(216,27,96,0.18);
        }

        .item-qty form {
            display: inline;
        }

        .item-qty button {
            padding: 4px 12px;
            margin: 0 4px;
            background: #f8bbd0;
            color: #d81b60;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            box-shadow: 0 1px 4px #d81b6044;
            transition: 0.2s;
        }

        .item-qty button:hover {
            background: #d81b60;
            color: #fff;
        }

        .item-remove a {
            color: #e91e63;
            font-size: 22px;
            text-decoration: none;
            transition: 0.2s;
        }

        .item-remove a:hover {
            color: #ad1457;
        }

        .empty-message {
            text-align: center;
            margin-top: 100px;
            font-size: 22px;
            color: #ad1457;
            font-weight: 600;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="cart">
    <h1>Your Cart</h1>

    <?php if (empty($cart)) : ?>
        <div class="empty-message">
            <p>Your cart is empty.</p>
            <a class="btn" href="index.php">Continue Shopping</a>
        </div>
    <?php else : ?>
        <?php
        $total = 0;
        foreach ($cart as $id => $item) :
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
        ?>
        <div class="item">
            <img src="images/<?php echo $item['image']; ?>" alt="">
            <div class="item-name"><?php echo $item['name']; ?></div>

            <div class="item-qty">
                <form method="post" action="update_cart.php" style="display:inline;">
                    <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                    <input type="hidden" name="action" value="decrease">
                    <button type="submit">➖</button>
                </form>

                <strong><?php echo $item['quantity']; ?></strong>

                <form method="post" action="update_cart.php" style="display:inline;">
                    <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                    <input type="hidden" name="action" value="increase">
                    <button type="submit">➕</button>
                </form>
            </div>

            <div class="item-price">Rs. <?php echo $subtotal; ?></div>

            <div class="item-remove">
                <a href="remove_from_cart.php?product_id=<?php echo $id; ?>" onclick="return confirm('Remove this item?');">🗑</a>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="total">Total: Rs. <?php echo $total; ?></div>
        <a class="btn" href="index.php">Continue Shopping</a>
        <a class="btn" href="checkout.php">Proceed to Checkout</a>
    <?php endif; ?>
</div>

</body>
</html>
