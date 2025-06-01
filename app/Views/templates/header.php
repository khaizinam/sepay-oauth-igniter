<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" />
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
            width: 220px;
            background-color: #343a40;
            padding-top: 1rem;
        }
        .sidebar a {
            color: #fff;
            display: block;
            padding: 10px 20px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            margin-left: 220px;
            padding: 2rem;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h5 class="text-white text-center">Menu</h5>
        <a href="/">Dashboard</a>
        <a href="<?= base_url('home') ?>">Access token</a>
        <a href="<?= base_url('bank-account') ?>">Bank accounts</a>
        <a href="<?= base_url('transactions') ?>">Transactions</a>
        <a href="<?= base_url('webhooks') ?>">Webhooks</a>
    </div>