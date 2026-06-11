<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' – ' : ''; ?>Atlas Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #FAFAFA;
            color: #171717;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: #FFFFFF;
            border-right: 1px solid #E5E5E5;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
        }

        .sidebar-logo {
            padding: 24px 20px;
            border-bottom: 1px solid #E5E5E5;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-logo .logo-icon {
            width: 32px; height: 32px;
            background: #FAFAFA;
            border: 1px solid #E5E5E5;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
        }

        .sidebar-logo-text h2 {
            color: #171717;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: -0.3px;
        }

        .sidebar-logo-text p {
            color: #737373;
            font-size: 11px;
            margin-top: 1px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 500;
        }

        .sidebar-nav {
            flex: 1;
            padding: 20px 0;
        }

        .nav-label {
            padding: 10px 20px 6px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #737373;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            color: #737373;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.15s ease;
            position: relative;
        }

        .nav-link:hover {
            background: #FAFAFA;
            color: #171717;
        }

        .nav-link.active {
            background: #F4F4F5;
            color: #2563EB;
            font-weight: 600;
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #2563EB;
            border-radius: 0 4px 4px 0;
        }

        .nav-link .icon {
            font-size: 16px;
            width: 20px;
            text-align: center;
            opacity: 0.8;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid #E5E5E5;
            background: #FFFFFF;
        }

        .sidebar-footer .admin-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .sidebar-footer .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #F4F4F5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            border: 1px solid #E5E5E5;
            color: #171717;
            font-weight: 600;
        }

        .sidebar-footer .admin-details {
            flex: 1;
            min-width: 0;
        }

        .sidebar-footer .admin-name {
            font-size: 13px;
            font-weight: 600;
            color: #171717;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-footer .admin-role {
            font-size: 11px;
            color: #737373;
            margin-top: 1px;
        }

        .sidebar-footer .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px;
            background: #FFFFFF;
            border: 1px solid #E5E5E5;
            color: #ef4444;
            border-radius: 8px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.15s;
        }

        .sidebar-footer .logout-btn:hover {
            background: #FEF2F2;
            border-color: #FCA5A5;
        }

        /* ── Main ── */
        .main {
            margin-left: 240px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .topbar {
            background: #FFFFFF;
            border-bottom: 1px solid #E5E5E5;
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .topbar h1 {
            font-size: 18px;
            font-weight: 600;
            color: #171717;
            letter-spacing: -0.3px;
        }

        .topbar .topbar-search {
            position: relative;
            width: 280px;
        }

        .topbar .topbar-search input {
            width: 100%;
            padding: 8px 12px 8px 32px;
            background: #FAFAFA;
            border: 1px solid #E5E5E5;
            border-radius: 8px;
            font-size: 13px;
            color: #171717;
            outline: none;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.15s ease;
        }

        .topbar .topbar-search input:focus {
            border-color: #2563EB;
            background: #FFFFFF;
        }

        .topbar .topbar-search .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 13px;
            color: #737373;
            pointer-events: none;
        }

        .topbar .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            color: #737373;
        }

        .topbar .date-badge {
            background: #FAFAFA;
            border: 1px solid #E5E5E5;
            padding: 4px 10px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
            font-weight: 500;
        }

        .content {
            padding: 32px;
            flex: 1;
        }

        /* ── Stat Cards ── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: #FFFFFF;
            border: 1px solid #E5E5E5;
            border-radius: 12px;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            transition: border-color 0.15s ease;
        }

        .stat-card:hover {
            border-color: #A3A3A3;
        }

        .stat-details {
            display: flex;
            flex-direction: column;
        }

        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 600;
            color: #171717;
            line-height: 1.1;
        }

        .stat-card .stat-label {
            font-size: 13px;
            color: #737373;
            margin-top: 4px;
            font-weight: 500;
        }

        .stat-card .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            background: #FAFAFA;
            border: 1px solid #E5E5E5;
        }

        .stat-card.red { border-top: 3px solid #EF4444; }
        .stat-card.blue { border-top: 3px solid #3B82F6; }
        .stat-card.green { border-top: 3px solid #10B981; }

        /* ── Cards & Panels ── */
        .card {
            background: #FFFFFF;
            border: 1px solid #E5E5E5;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid #E5E5E5;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #FFFFFF;
        }

        .card-header h2 {
            font-size: 15px;
            font-weight: 600;
            color: #171717;
            letter-spacing: -0.2px;
        }

        .badge {
            background: #F4F4F5;
            color: #171717;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 500;
            border: 1px solid #E5E5E5;
        }

        /* ── Modern Table Styling ── */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            padding: 12px 20px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #737373;
            background: #FAFAFA;
            border-bottom: 1px solid #E5E5E5;
        }

        td {
            padding: 14px 20px;
            font-size: 13px;
            color: #171717;
            border-bottom: 1px solid #E5E5E5;
            font-family: 'Inter', sans-serif;
        }

        tr:last-child td { border-bottom: none; }

        tr { transition: background-color 0.15s; }
        tr:hover td {
            background: #FAFAFA;
        }

        /* ── Modern Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid #E5E5E5;
            font-family: 'Inter', sans-serif;
            background: #FFFFFF;
            color: #171717;
            transition: all 0.15s ease;
        }

        .btn:hover {
            background: #FAFAFA;
            border-color: #A3A3A3;
        }

        .btn-primary {
            background: #2563EB;
            color: #FFFFFF;
            border-color: #2563EB;
        }
        .btn-primary:hover {
            background: #1D4ED8;
            border-color: #1D4ED8;
        }

        .btn-danger {
            background: #FFFFFF;
            color: #EF4444;
            border-color: #FCA5A5;
        }
        .btn-danger:hover {
            background: #FEF2F2;
            border-color: #EF4444;
        }

        .btn-success {
            background: #10B981;
            color: #FFFFFF;
            border-color: #10B981;
        }
        .btn-success:hover {
            background: #059669;
            border-color: #059669;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 6px;
        }

        /* ── Minimalist Forms ── */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group.full { grid-column: 1 / -1; }

        .form-group label {
            font-size: 13px;
            font-weight: 500;
            color: #171717;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            padding: 10px 12px;
            background: #FFFFFF;
            border: 1px solid #E5E5E5;
            border-radius: 8px;
            color: #171717;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: all 0.15s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #2563EB;
        }

        .form-group textarea { resize: vertical; min-height: 90px; }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #A3A3A3;
        }

        /* ── Minimalist Alerts ── */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }

        .alert-success {
            background: #ECFDF5;
            border: 1px solid #A7F3D0;
            color: #065F46;
        }

        .alert-danger {
            background: #FEF2F2;
            border: 1px solid #FCA5A5;
            color: #991B1B;
        }

        .alert a {
            color: inherit;
            font-weight: 600;
            text-decoration: underline;
        }
    </style>
    <script>
        function filterTable() {
            var input = document.getElementById("header-search");
            var filter = input.value.toLowerCase();
            var tables = document.getElementsByTagName("table");
            for (var i = 0; i < tables.length; i++) {
                var tr = tables[i].getElementsByTagName("tr");
                for (var j = 1; j < tr.length; j++) {
                    var row = tr[j];
                    var text = row.textContent || row.innerText;
                    if (text.toLowerCase().indexOf(filter) > -1) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                }
            }
        }
    </script>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon">✈️</div>
        <div class="sidebar-logo-text">
            <h2>Atlas Tour</h2>
            <p>Admin Dashboard</p>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Main</div>
        <a href="dashboard.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
            <span class="icon">📊</span> Dashboard
        </a>

        <div class="nav-label">Manage</div>
        <a href="bookings.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'bookings.php') ? 'active' : ''; ?>">
            <span class="icon">📋</span> Bookings
        </a>
        <a href="packages.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'packages.php') ? 'active' : ''; ?>">
            <span class="icon">🌍</span> Packages
        </a>
        <a href="users.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'users.php') ? 'active' : ''; ?>">
            <span class="icon">👥</span> Users
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="admin-info">
            <div class="avatar">
                <?php 
                $admin_username = isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : 'A';
                echo strtoupper(substr($admin_username, 0, 1)); 
                ?>
            </div>
            <div class="admin-details">
                <div class="admin-name"><?php echo htmlspecialchars($admin_username); ?></div>
                <div class="admin-role">Administrator</div>
            </div>
        </div>
        <a href="logout.php" class="logout-btn">
            <span>🚪</span> Logout
        </a>
    </div>
</div>

<div class="main">
    <div class="topbar">
        <h1><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></h1>
        
        <div class="topbar-search">
            <span class="search-icon">🔍</span>
            <input type="text" placeholder="Search..." id="header-search" onkeyup="filterTable()">
        </div>

        <div class="topbar-right">
            <div class="date-badge">
                <span>📅</span>
                <span><?php echo date('d M Y'); ?></span>
            </div>
            <span>|</span>
            <span>Welcome, <strong><?php echo htmlspecialchars($admin_username); ?></strong>!</span>
        </div>
    </div>
    <div class="content">
