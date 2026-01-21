<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h3 class="mb-3">Transport Reports</h3>

<!-- FILTERS -->
<div class="card mb-3">
    <div class="card-body">
        <form id="filterForm" class="row g-3">

            <div class="col-md-4">
                <div class="border rounded p-2" style="max-height:150px; overflow-y:auto;">
                    <strong>Vehicles</strong>
                    <?php foreach($vehicles as $v): ?>
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="vehicle_ids[]"
                                   value="<?= $v['id'] ?>">
                            <label class="form-check-label">
                                <?= $v['vehicle_name'] ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="border rounded p-2" style="max-height:150px; overflow-y:auto;">
                    <strong>Routes</strong>
                    <?php foreach($routes as $r): ?>
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="route_ids[]"
                                   value="<?= $r['id'] ?>">
                            <label class="form-check-label">
                                <?= $r['route_name'] ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Driver Name</label>
                <input type="text" name="driver" class="form-control">
            </div>

            <div class="col-md-2 d-grid">
                <button type="button"
                        class="btn btn-primary"
                        onclick="loadReport(1)">
                    Filter
                </button>
            </div>

        </form>
    </div>
</div>

<!-- TABLE -->
<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>S.No</th>
                    <th>Vehicle</th>
                    <th>Routes</th>
                    <th>Pickup Points</th>
                    <th>Driver</th>
                </tr>
            </thead>
            <tbody id="reportTable"></tbody>
        </table>

        <nav>
            <ul class="pagination" id="pagination"></ul>
        </nav>
    </div>
</div>

<!-- FILTER CONFIRM MODAL -->



<script>
let currentPage = 1;

function loadReport(page = 1){
    currentPage = page;

    let data = $('#filterForm').serializeArray();
    data.push({name:'page', value:page});

    $.post("<?= base_url('reports/fetch') ?>", data, function(res){
        let rows = '';
        res.data.forEach((r,i)=>{
            rows += `<tr>
                <td>${((page-1)*res.limit)+(i+1)}</td>
                <td>${r.vehicle_name}</td>
                <td>${r.routes ?? ''}</td>
                <td>${r.pickups ?? ''}</td>
                <td>${r.driver_name}</td>
            </tr>`;
        });
        $('#reportTable').html(rows);
        renderPagination(res.total, res.limit, page);
    });
}

function renderPagination(total, limit, page){
    let pages = Math.ceil(total / limit);
    let html = '';

    html += `<li class="page-item ${page===1?'disabled':''}">
        <a class="page-link" onclick="loadReport(${page-1})">Previous</a>
    </li>`;

    for(let i=1;i<=pages;i++){
        html += `<li class="page-item ${i===page?'active':''}">
            <a class="page-link" onclick="loadReport(${i})">${i}</a>
        </li>`;
    }

    html += `<li class="page-item ${page===pages?'disabled':''}">
        <a class="page-link" onclick="loadReport(${page+1})">Next</a>
    </li>`;

    $('#pagination').html(html);
}

/* FILTER MODAL */
function openFilterModal(){
    new bootstrap.Modal(document.getElementById('filterModal')).show();
}

function applyFilter(){
    bootstrap.Modal.getInstance(document.getElementById('filterModal')).hide();
    loadReport(1);
}

loadReport();
</script>

<?= $this->endSection() ?>
