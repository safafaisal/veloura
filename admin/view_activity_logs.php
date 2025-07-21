<?php
session_start();
include '../db/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit();
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 50;
$offset = ($page - 1) * $limit;

// Filtering
$user_filter = isset($_GET['user']) ? $_GET['user'] : '';
$action_filter = isset($_GET['action']) ? $_GET['action'] : '';
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';

// Build query
$where_conditions = [];
$params = [];
$types = '';

if ($user_filter) {
    $where_conditions[] = "username LIKE ?";
    $params[] = "%$user_filter%";
    $types .= 's';
}

if ($action_filter) {
    $where_conditions[] = "action = ?";
    $params[] = $action_filter;
    $types .= 's';
}

if ($date_filter) {
    $where_conditions[] = "DATE(created_at) = ?";
    $params[] = $date_filter;
    $types .= 's';
}

$where_clause = $where_conditions ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Get total count
$count_sql = "SELECT COUNT(*) as total FROM user_activity_log $where_clause";
$count_stmt = $conn->prepare($count_sql);
if ($params) {
    $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$total_result = $count_stmt->get_result();
$total_logs = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_logs / $limit);

// Get logs
$sql = "SELECT * FROM user_activity_log $where_clause ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$params[] = $limit;
$params[] = $offset;
$types .= 'ii';
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Get unique actions for filter
$actions_sql = "SELECT DISTINCT action FROM user_activity_log ORDER BY action";
$actions_result = $conn->query($actions_sql);
$actions = [];
while ($row = $actions_result->fetch_assoc()) {
    $actions[] = $row['action'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Activity Logs - Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .log-table {
            font-size: 14px;
        }
        .log-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .action-badge {
            font-size: 12px;
            padding: 4px 8px;
        }
        .filters {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <h2><i class="fas fa-chart-line"></i> User Activity Logs</h2>
                
                <!-- Filters -->
                <div class="filters">
                    <form method="GET" class="row">
                        <div class="col-md-3">
                            <label>Username:</label>
                            <input type="text" name="user" class="form-control" value="<?= htmlspecialchars($user_filter) ?>" placeholder="Filter by username">
                        </div>
                        <div class="col-md-3">
                            <label>Action:</label>
                            <select name="action" class="form-control">
                                <option value="">All Actions</option>
                                <?php foreach ($actions as $action): ?>
                                    <option value="<?= htmlspecialchars($action) ?>" <?= $action_filter === $action ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($action) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Date:</label>
                            <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($date_filter) ?>">
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <a href="view_activity_logs.php" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Stats -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Logs</h5>
                                <h3><?= number_format($total_logs) ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Today's Logs</h5>
                                <h3><?= number_format($result->num_rows) ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logs Table -->
                <div class="table-responsive">
                    <table class="table table-striped log-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Details</th>
                                <th>IP Address</th>
                                <th>Date/Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($log = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $log['id'] ?></td>
                                    <td>
                                        <?php if ($log['username']): ?>
                                            <strong><?= htmlspecialchars($log['username']) ?></strong>
                                        <?php else: ?>
                                            <em>Guest</em>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= getActionColor($log['action']) ?> action-badge">
                                            <?= htmlspecialchars($log['action']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small><?= htmlspecialchars($log['details']) ?></small>
                                    </td>
                                    <td>
                                        <code><?= htmlspecialchars($log['ip_address']) ?></code>
                                    </td>
                                    <td>
                                        <small><?= date('M j, Y g:i A', strtotime($log['created_at'])) ?></small>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <nav>
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>&user=<?= urlencode($user_filter) ?>&action=<?= urlencode($action_filter) ?>&date=<?= urlencode($date_filter) ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>

                <div class="mt-3">
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Admin Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
function getActionColor($action) {
    switch ($action) {
        case 'LOGIN':
            return 'success';
        case 'LOGOUT':
            return 'warning';
        case 'REGISTRATION':
            return 'info';
        case 'CHECKOUT':
            return 'primary';
        case 'ADD_TO_CART':
            return 'success';
        case 'REMOVE_FROM_CART':
            return 'danger';
        case 'LOGIN_FAILED':
            return 'danger';
        case 'ERROR':
            return 'danger';
        case 'ADMIN_ACTION':
            return 'dark';
        default:
            return 'secondary';
    }
}
?> 