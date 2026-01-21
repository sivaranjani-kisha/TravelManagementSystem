<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<h3>Dashboard</h3>

<div class="row">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5>Routes</h5>
                <h2><?= $routeCount ?></h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5>Vehicles</h5>
                <h2><?= $vehicleCount ?></h2>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
