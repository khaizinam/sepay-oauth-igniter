<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <title>Bảng điều khiển</title>
    <link rel="stylesheet" href="<?= base_url('public/assets/build/css/app.css') ?>">
    <style>
        body {
            overflow-x: hidden;
        }
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .sidebar .nav-link {
            color: #fff !important;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.3s ease;
            border: none;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.2);
            font-weight: 600;
        }
        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }
        .content {
            margin-left: 250px;
            padding: 2rem;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="text-center mb-4">
            <h5 class="text-white mb-1">
                <i class="fas fa-network-wired"></i> Tunnel Gateway
            </h5>
            <small class="text-muted">Local Tunnel System</small>
        </div>
        
        <nav class="nav flex-column">
            <a href="/" class="nav-link text-white">
                <i class="fas fa-tachometer-alt me-2"></i>
                Dashboard
            </a>
            <a href="/tunel" class="nav-link text-white">
                <i class="fas fa-network-wired me-2"></i>
                Tunnels
            </a>
            <a href="/setting" class="nav-link text-white">
                <i class="fas fa-key me-2"></i>
                Access Token
            </a>
            <a href="/bank-account" class="nav-link text-white">
                <i class="fas fa-university me-2"></i>
                Bank Accounts
            </a>
            <a href="/webhooks" class="nav-link text-white">
                <i class="fas fa-bolt me-2"></i>
                Webhooks
            </a>
        </nav>
        
        <div class="mt-auto p-3">
            <div class="text-center">
                <small class="text-muted">
                    <i class="fas fa-code me-1"></i>
                    Powered by CodeIgniter 4
                </small>
            </div>
        </div>
    </div>