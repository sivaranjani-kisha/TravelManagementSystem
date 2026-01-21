<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand">Transport Management System</span>

    <span class="text-white">
        <?= session()->get('name') ?>
        | <a href="<?= base_url('logout') ?>" class="text-warning">Logout</a>
    </span>
</nav>
