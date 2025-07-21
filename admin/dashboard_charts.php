<?php
session_start();
include '../db/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit();
}

// Get statistics for charts
function getActivityStats($conn) {
    // Get today's date
    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    $week_ago = date('Y-m-d', strtotime('-7 days'));
    $month_ago = date('Y-m-d', strtotime('-30 days'));
    
    // Total logs
    $total_logs = $conn->query("SELECT COUNT(*) as count FROM user_activity_log")->fetch_assoc()['count'];
    
    // Today's logs
    $today_logs = $conn->query("SELECT COUNT(*) as count FROM user_activity_log WHERE DATE(created_at) = '$today'")->fetch_assoc()['count'];
    
    // Yesterday's logs
    $yesterday_logs = $conn->query("SELECT COUNT(*) as count FROM user_activity_log WHERE DATE(created_at) = '$yesterday'")->fetch_assoc()['count'];
    
    // This week's logs
    $week_logs = $conn->query("SELECT COUNT(*) as count FROM user_activity_log WHERE DATE(created_at) >= '$week_ago'")->fetch_assoc()['count'];
    
    // This month's logs
    $month_logs = $conn->query("SELECT COUNT(*) as count FROM user_activity_log WHERE DATE(created_at) >= '$month_ago'")->fetch_assoc()['count'];
    
    // Action breakdown
    $action_stats = $conn->query("SELECT action, COUNT(*) as count FROM user_activity_log GROUP BY action ORDER BY count DESC LIMIT 10");
    $actions = [];
    $action_counts = [];
    while ($row = $action_stats->fetch_assoc()) {
        $actions[] = $row['action'];
        $action_counts[] = $row['count'];
    }
    
    // Daily activity for last 7 days
    $daily_activity = $conn->query("
        SELECT DATE(created_at) as date, COUNT(*) as count 
        FROM user_activity_log 
        WHERE DATE(created_at) >= '$week_ago'
        GROUP BY DATE(created_at) 
        ORDER BY date
    ");
    $dates = [];
    $daily_counts = [];
    while ($row = $daily_activity->fetch_assoc()) {
        $dates[] = date('M j', strtotime($row['date']));
        $daily_counts[] = $row['count'];
    }
    
    // Top users by activity
    $top_users = $conn->query("
        SELECT username, COUNT(*) as count 
        FROM user_activity_log 
        WHERE username IS NOT NULL 
        GROUP BY username 
        ORDER BY count DESC 
        LIMIT 5
    ");
    $user_names = [];
    $user_counts = [];
    while ($row = $top_users->fetch_assoc()) {
        $user_names[] = $row['username'];
        $user_counts[] = $row['count'];
    }
    
    // Login vs Logout comparison
    $login_count = $conn->query("SELECT COUNT(*) as count FROM user_activity_log WHERE action = 'LOGIN'")->fetch_assoc()['count'];
    $logout_count = $conn->query("SELECT COUNT(*) as count FROM user_activity_log WHERE action = 'LOGOUT'")->fetch_assoc()['count'];
    $failed_login_count = $conn->query("SELECT COUNT(*) as count FROM user_activity_log WHERE action = 'LOGIN_FAILED'")->fetch_assoc()['count'];
    
    return [
        'total_logs' => $total_logs,
        'today_logs' => $today_logs,
        'yesterday_logs' => $yesterday_logs,
        'week_logs' => $week_logs,
        'month_logs' => $month_logs,
        'actions' => $actions,
        'action_counts' => $action_counts,
        'dates' => $dates,
        'daily_counts' => $daily_counts,
        'user_names' => $user_names,
        'user_counts' => $user_counts,
        'login_count' => $login_count,
        'logout_count' => $logout_count,
        'failed_login_count' => $failed_login_count
    ];
}

$stats = getActivityStats($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Analytics</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .chart-title {
            color: #333;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }
        .metric-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .metric-value {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
        }
        .metric-label {
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>
<body style="background-color: #f8f9fa;">
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">
                    <i class="fas fa-chart-bar"></i> Admin Dashboard Analytics
                </h2>
                
                <!-- Quick Stats -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value"><?= number_format($stats['total_logs']) ?></div>
                            <div class="metric-label">Total Activities</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value"><?= number_format($stats['today_logs']) ?></div>
                            <div class="metric-label">Today's Activities</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value"><?= number_format($stats['week_logs']) ?></div>
                            <div class="metric-label">This Week</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value"><?= number_format($stats['month_logs']) ?></div>
                            <div class="metric-label">This Month</div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row 1 -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="chart-container">
                            <h5 class="chart-title">
                                <i class="fas fa-chart-line"></i> Daily Activity (Last 7 Days)
                            </h5>
                            <canvas id="dailyActivityChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="chart-container">
                            <h5 class="chart-title">
                                <i class="fas fa-chart-pie"></i> Action Breakdown
                            </h5>
                            <canvas id="actionBreakdownChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Charts Row 2 -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="chart-container">
                            <h5 class="chart-title">
                                <i class="fas fa-users"></i> Top Active Users
                            </h5>
                            <canvas id="topUsersChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="chart-container">
                            <h5 class="chart-title">
                                <i class="fas fa-sign-in-alt"></i> Login Statistics
                            </h5>
                            <canvas id="loginStatsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Admin Dashboard
                        </a>
                        <a href="view_activity_logs.php" class="btn btn-primary">
                            <i class="fas fa-list"></i> View Detailed Logs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Daily Activity Chart
        const dailyCtx = document.getElementById('dailyActivityChart').getContext('2d');
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode($stats['dates']) ?>,
                datasets: [{
                    label: 'Activities',
                    data: <?= json_encode($stats['daily_counts']) ?>,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Action Breakdown Chart
        const actionCtx = document.getElementById('actionBreakdownChart').getContext('2d');
        new Chart(actionCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($stats['actions']) ?>,
                datasets: [{
                    data: <?= json_encode($stats['action_counts']) ?>,
                    backgroundColor: [
                        '#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe',
                        '#00f2fe', '#43e97b', '#38f9d7', '#fa709a', '#fee140'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Top Users Chart
        const usersCtx = document.getElementById('topUsersChart').getContext('2d');
        new Chart(usersCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($stats['user_names']) ?>,
                datasets: [{
                    label: 'Activities',
                    data: <?= json_encode($stats['user_counts']) ?>,
                    backgroundColor: '#43e97b',
                    borderColor: '#38f9d7',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Login Statistics Chart
        const loginCtx = document.getElementById('loginStatsChart').getContext('2d');
        new Chart(loginCtx, {
            type: 'pie',
            data: {
                labels: ['Successful Logins', 'Logouts', 'Failed Logins'],
                datasets: [{
                    data: [
                        <?= $stats['login_count'] ?>,
                        <?= $stats['logout_count'] ?>,
                        <?= $stats['failed_login_count'] ?>
                    ],
                    backgroundColor: ['#43e97b', '#4facfe', '#f5576c'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html> 