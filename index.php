<?php
include 'db/db.php';

$categoryFilter = isset($_GET['category']) ? $_GET['category'] : 'all';

if ($categoryFilter === 'men') {
    $sql = "SELECT * FROM products WHERE category = 'men'";
} elseif ($categoryFilter === 'women') {
    $sql = "SELECT * FROM products WHERE category = 'women'";
} else {
    $sql = "SELECT * FROM products";
}

$result = $conn->query($sql);
?>

<?php include 'navbar.php'; ?>

<style>
    body {
        font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
        margin: 0;
        background: url('images/bg.jpg') no-repeat center center fixed;
        background-size: cover;
        position: relative;
        color: #333;
    }
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
    nav.navbar {
        background: linear-gradient(90deg, #d81b60 0%, #ffb6c1 100%);
        border-radius: 0 0 18px 18px;
        box-shadow: 0 4px 16px rgba(216,27,96,0.10);
        padding: 0.7rem 2rem;
    }
    .navbar-brand {
        font-weight: bold;
        font-size: 28px;
        color: #fff !important;
        letter-spacing: 2px;
        text-shadow: 0 2px 8px #c2185b44;
    }
    .navbar-nav .nav-link {
        color: #fff !important;
        font-weight: 500;
        font-size: 17px;
        margin: 0 10px;
        border-radius: 8px;
        transition: background 0.2s, color 0.2s;
        padding: 8px 18px;
    }
    .navbar-nav .nav-link:hover, .navbar-nav .nav-link.active {
        background: rgba(255,255,255,0.18);
        color: #d81b60 !important;
    }
    .navbar-toggler {
        border: none;
        background: #fff3;
    }
    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(216,27,96,0.7)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
    }
    .main-content, .category-buttons, .products, .card {
        position: relative;
        z-index: 1;
    }
    .category-buttons {
        text-align: center;
        margin-top: 30px;
    }
    .category-buttons a {
        display: inline-block;
        margin: 0 10px;
        background: linear-gradient(90deg, #d81b60 0%, #ffb6c1 100%);
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 16px;
        box-shadow: 0 2px 8px rgba(216,27,96,0.10);
        transition: 0.2s;
    }
    .category-buttons a:hover {
        background: linear-gradient(90deg, #ad1457 0%, #f8bbd0 100%);
        color: #fff;
        transform: translateY(-2px) scale(1.04);
        box-shadow: 0 4px 16px rgba(216,27,96,0.18);
    }
    .products {
        display: flex;
        flex-wrap: wrap;
        gap: 32px;
        justify-content: center;
        padding: 40px 0;
    }
    .card {
        background: linear-gradient(135deg, #fff 70%, #fce4ec 100%);
        width: 240px;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(216,27,96,0.10);
        padding: 20px 16px 24px 16px;
        text-align: center;
        transition: 0.2s;
    }
    .card:hover {
        transform: scale(1.05) translateY(-4px);
        box-shadow: 0 8px 32px rgba(216,27,96,0.18);
    }
    .card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 2px 8px #d81b6044;
    }
    .card h3 {
        margin: 16px 0 6px;
        color: #d81b60;
        font-size: 22px;
        font-weight: 700;
        letter-spacing: 1px;
    }
    .card p {
        font-size: 14px;
        color: #666;
        margin-bottom: 8px;
    }
    .price {
        color: #ad1457;
        font-weight: bold;
        margin: 10px 0 16px 0;
        font-size: 18px;
    }
    .btn {
        background: linear-gradient(90deg, #d81b60 0%, #ffb6c1 100%);
        color: white;
        border: none;
        padding: 10px 22px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 15px;
        box-shadow: 0 2px 8px rgba(216,27,96,0.10);
        transition: 0.2s;
    }
    .btn:hover {
        background: linear-gradient(90deg, #ad1457 0%, #f8bbd0 100%);
        color: #fff;
        transform: scale(1.04);
        box-shadow: 0 4px 16px rgba(216,27,96,0.18);
    }
</style>

<!-- Inside Main Content (from navbar.php wrapper) -->
<div class="main-content">
    <div class="hero">
        <h1>VELOURA</h1>
    </div>

    <div class="category-buttons">
        <a href="index.php?category=all">Show All</a>
        <a href="index.php?category=women">Women Perfumes</a>
        <a href="index.php?category=men">Men Perfumes</a>
    </div>

    <div class="products">
        <?php while($row = $result->fetch_assoc()) { ?>
        <div class="card">
            <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
            <h3><?php echo $row['name']; ?></h3>
            <p><?php echo $row['description']; ?></p>
            <div class="price">Rs. <?php echo $row['price']; ?></div>
            <form method="post" action="add_to_cart.php">
                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                <button type="submit" class="btn">Add to Cart</button>
            </form>
        </div>
        <?php } ?>
    </div>
    <script>
    const toggleBtn = document.getElementById('toggleCategory');
    const categorySection = document.querySelector('.category-buttons');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            if (categorySection.style.display === 'none') {
                categorySection.style.display = 'block';
                toggleBtn.textContent = 'Hide Filters';
            } else {
                categorySection.style.display = 'none';
                toggleBtn.textContent = 'Show Filters';
            }
        });
    }
    </script>
</div>
</div> <!-- End of wrapper -->
