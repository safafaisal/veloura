<?php // Fixed version of navbar.php ?>
<nav class="navbar navbar-expand-lg navbar-light bg-light" style="background: linear-gradient(90deg, #d81b60 0%, #ffb6c1 100%); border-radius: 0 0 18px 18px; box-shadow: 0 4px 16px rgba(216,27,96,0.10); padding: 0.7rem 2rem;">
  <a class="navbar-brand" href="/veloura/index.php" style="font-weight: bold; font-size: 28px; color: #fff !important; letter-spacing: 2px; text-shadow: 0 2px 8px #c2185b44;">Veloura</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="#navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="border: none; background: #fff3;">
    <span class="navbar-toggler-icon" style="background-image: url('data:image/svg+xml;charset=utf8,%3Csvg viewBox=\'0 0 30 30\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath stroke=\'rgba(216,27,96,0.7)\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-miterlimit=\'10\' d=\'M4 7h22M4 15h22M4 23h22\'/%3E%3C/svg%3E');"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item"><a class="nav-link" href="/veloura/index.php" style="color: #fff !important; font-weight: 500; font-size: 17px; margin: 0 10px; border-radius: 8px; transition: background 0.2s, color 0.2s; padding: 8px 18px;">Home</a></li>
      <li class="nav-item"><a class="nav-link" href="/veloura/cart.php" style="color: #fff !important; font-weight: 500; font-size: 17px; margin: 0 10px; border-radius: 8px; transition: background 0.2s, color 0.2s; padding: 8px 18px;">Cart</a></li>
      <li class="nav-item"><a class="nav-link" href="/veloura/help_center.php" style="color: #fff !important; font-weight: 500; font-size: 17px; margin: 0 10px; border-radius: 8px; transition: background 0.2s, color 0.2s; padding: 8px 18px;">Help Center</a></li>
      <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']): ?>
        <li class="nav-item"><a class="nav-link" href="/veloura/dashboard.php" style="color: #fff !important; font-weight: 500; font-size: 17px; margin: 0 10px; border-radius: 8px; transition: background 0.2s, color 0.2s; padding: 8px 18px;">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="/veloura/logout.php" style="color: #fff !important; font-weight: 500; font-size: 17px; margin: 0 10px; border-radius: 8px; transition: background 0.2s, color 0.2s; padding: 8px 18px;">Logout</a></li>
      <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="/veloura/login.php" style="color: #fff !important; font-weight: 500; font-size: 17px; margin: 0 10px; border-radius: 8px; transition: background 0.2s, color 0.2s; padding: 8px 18px;">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="/veloura/register.php" style="color: #fff !important; font-weight: 500; font-size: 17px; margin: 0 10px; border-radius: 8px; transition: background 0.2s, color 0.2s; padding: 8px 18px;">Register</a></li>
      <?php endif; ?>
      <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']): ?>
        <li class="nav-item"><a class="nav-link" href="/veloura/admin/index.php" style="color: #fff !important; font-weight: 500; font-size: 17px; margin: 0 10px; border-radius: 8px; transition: background 0.2s, color 0.2s; padding: 8px 18px;">Admin</a></li>
        <li class="nav-item"><a class="nav-link" href="/veloura/admin/logout.php" style="color: #fff !important; font-weight: 500; font-size: 17px; margin: 0 10px; border-radius: 8px; transition: background 0.2s, color 0.2s; padding: 8px 18px;">Admin Logout</a></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
