<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin E-Menu</title>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: #f1f5f9;
        }

        .admin {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */

        .sidebar {
            width: 260px;
            background: #0f172a;
            color: white;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
        }

        .logo {
            margin-bottom: 40px;
        }

        .logo h2 {
            font-size: 28px;
        }

        .menu {
            list-style: none;
        }

        .menu li {
            margin-bottom: 10px;
        }

        .menu li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px;
            border-radius: 10px;
            text-decoration: none;
            color: white;
            transition: 0.3s;
        }

        .menu li a:hover {
            background: #1e293b;
        }

        .main {
            margin-left: 260px;
            width: 100%;
            padding: 30px;
        }

        /* TOP */

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .top h1 {
            color: #0f172a;
            font-size: 28px;
        }

        /* CARD */

        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .card h3 {
            color: #64748b;
            margin-bottom: 10px;
            font-size: 15px;
        }

        .card p {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
        }

        /* TABLE */

        .table-box {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: #f8fafc;
            padding: 15px;
            text-align: left;
        }

        table td {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
        }

        /* BUTTON */

        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
        }

        .btn-primary {
            background: #0f172a;
            color: white;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
        }

        /* FORM */

        .form-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 14px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            outline: none;
        }

        textarea.form-control {
            resize: none;
            height: 120px;
        }

        /* STATUS */

        .badge {
            padding: 6px 10px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
        }

        .dang-phuc-vu {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .hoan-thanh {
            background: #dcfce7;
            color: #15803d;
        }

        .dang-cho {
            background: #fef3c7;
            color: #b45309;
        }

        /* IMAGE */

        .food-image {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 10px;
        }

        /* RESPONSIVE */

        @media(max-width:1200px) {

            .cards {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media(max-width:768px) {

            .sidebar {
                width: 80px;
            }

            .logo h2 {
                display: none;
            }

            .menu li a span {
                display: none;
            }

            .main {
                margin-left: 80px;
            }

            .cards {
                grid-template-columns: 1fr;
            }

        }

        /* MODAL */

        .modal {

            display: none;

            position: fixed;

            z-index: 999;

            left: 0;
            top: 0;

            width: 100%;
            height: 100%;

            background: rgba(0, 0, 0, 0.5);

        }

        .modal-content {

            background: white;

            width: 500px;

            padding: 25px;

            border-radius: 15px;

            margin: 50px auto;

            position: relative;

            animation: showModal 0.3s ease;
        }

        .modal-header {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 20px;
        }

        .close {

            font-size: 28px;

            cursor: pointer;
        }

        @keyframes showModal {

            from {
                transform: translateY(-30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }

        }

        /* ORDER TABLE */

        .order-table {

            width: 100%;

            border-collapse: collapse;
        }

        .order-table th {

            background: #f8fafc;

            padding: 14px;

            text-align: left;

            font-size: 14px;
        }

        .order-table td {

            padding: 14px;

            border-top: 1px solid #e5e7eb;

            vertical-align: middle;
        }

        .action-group {

            display: flex;

            flex-direction: column;

            gap: 10px;
        }

        .action-group form {

            display: flex;

            gap: 8px;

            align-items: center;
        }

        .status-select {

            padding: 8px 10px;

            border: 1px solid #d1d5db;

            border-radius: 8px;

            outline: none;
        }

        .btn-sm {

            padding: 8px 14px;

            font-size: 13px;
        }

        .badge {

            padding: 8px 12px;

            border-radius: 30px;

            font-size: 13px;

            font-weight: 600;

            display: inline-block;
        }

        .dang-cho {

            background: #fef3c7;

            color: #92400e;
        }

        .hoan-thanh {

            background: #dcfce7;

            color: #166534;
        }

        .da-huy {

            background: #fee2e2;

            color: #991b1b;
        }
    </style>

</head>

<body>

    <div class="admin">