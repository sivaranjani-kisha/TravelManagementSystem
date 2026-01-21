<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap JS (THIS IS THE KEY) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="<?= base_url('public/assets/css/admin.css') ?>">


    <style>
        body, html {
            height: 100%;
        }
        .wrapper {
            min-height: 100vh;
            display: flex;
        }
        .main-content {
            display: flex;
            flex-direction: column;
            width: 100%;
        }
        .content-area {
            flex: 1; /* 👈 content grow aagum */
        }
    </style>
</head>
<body>

<div class="wrapper">

    <!-- Sidebar -->
    <?= view('partials/sidebar') ?>

    <!-- Right side -->
    <div class="main-content">

        <!-- Header -->
        <?= view('partials/header') ?>

        <!-- Content -->
        <div class="content-area p-4">
            <?= $this->renderSection('content') ?>
        </div>

        <!-- Footer (ALWAYS BOTTOM) -->
        <?= view('partials/footer') ?>

    </div>

</div>

</body>
</html>
