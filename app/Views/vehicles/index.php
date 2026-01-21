<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h3 class="mb-3">Vehicles</h3>

<!-- ADD VEHICLE -->
<div class="card mb-3">
    <div class="card-body">
        <form id="vehicleForm" enctype="multipart/form-data" class="row g-3">

            <input type="hidden" name="id" id="id">

            <div class="col-md-4">
                <input type="text" name="vehicle_name"
                       class="form-control"
                       placeholder="Vehicle Name" required>
            </div>

            <div class="col-md-4">
                <input type="text" name="driver_name"
                       class="form-control"
                       placeholder="Driver Name" required>
            </div>

            <!-- ROUTE CHECKBOX -->
            <div class="col-md-6">
                <div class="border rounded p-2" style="max-height:150px; overflow-y:auto;">
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

            <div class="col-md-3">
                <input type="file" name="vehicle_image" class="form-control">
            </div>

            <div class="col-md-3 d-grid">
                <button type="button"
                        class="btn btn-primary"
                        onclick="openSaveModal()">
                    Save
                </button>
            </div>

        </form>
    </div>
</div>

<!-- VEHICLE TABLE -->
<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>S.No</th>
                    <th>Vehicle</th>
                    <th>Driver</th>
                    <th>Routes</th>
                    <th>Image</th>
                    <th width="120">Action</th>
                </tr>
            </thead>
            <tbody id="vTable"></tbody>
        </table>

        <nav>
            <ul class="pagination" id="pagination"></ul>
        </nav>
    </div>
</div>

<!-- SAVE MODAL -->
<div class="modal fade" id="saveModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center">
        <h5>Confirm Save</h5>
        <p>Do you want to save this vehicle?</p>
        <button class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <button class="btn btn-primary" onclick="confirmSave()">Yes</button>
      </div>
    </div>
  </div>
</div>

<!-- DELETE MODAL -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center">
        <h5>Are you sure?</h5>
        <p>Do you want to delete this vehicle?</p>
        <input type="hidden" id="delete_id">
        <button class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <button class="btn btn-danger" onclick="confirmDelete()">Yes</button>
      </div>
    </div>
  </div>
</div>

<script>
let currentPage = 1;

function loadVehicles(page = 1){
    currentPage = page;

    $.get("<?= base_url('vehicles/list') ?>", { page }, function(res){
        let rows = '';
        res.data.forEach((v,i)=>{
            rows += `<tr>
                <td>${((page-1)*res.limit)+(i+1)}</td>
                <td>${v.vehicle_name}</td>
                <td>${v.driver_name}</td>
                <td>${v.routes ?? ''}</td>
                <td>${v.vehicle_image ? 
                    `<img src="<?= base_url('public/uploads/vehicles') ?>/${v.vehicle_image}" width="60">` : ''}</td>
                <td>
                    <button class="btn btn-sm btn-danger"
                        onclick="openDelete(${v.id})">Delete</button>
                </td>
            </tr>`;
        });
        $('#vTable').html(rows);
        renderPagination(res.total, res.limit, page);
    });
}

function renderPagination(total, limit, page){
    let pages = Math.ceil(total / limit);
    let html = '';

    html += `<li class="page-item ${page===1?'disabled':''}">
        <a class="page-link" onclick="loadVehicles(${page-1})">Previous</a>
    </li>`;

    for(let i=1;i<=pages;i++){
        html += `<li class="page-item ${i===page?'active':''}">
            <a class="page-link" onclick="loadVehicles(${i})">${i}</a>
        </li>`;
    }

    html += `<li class="page-item ${page===pages?'disabled':''}">
        <a class="page-link" onclick="loadVehicles(${page+1})">Next</a>
    </li>`;

    $('#pagination').html(html);
}

/* SAVE */
function openSaveModal(){
    new bootstrap.Modal(document.getElementById('saveModal')).show();
}

function confirmSave(){
    bootstrap.Modal.getInstance(document.getElementById('saveModal')).hide();

    let fd = new FormData($('#vehicleForm')[0]);
    $.ajax({
        url: "<?= base_url('vehicles/save') ?>",
        type: "POST",
        data: fd,
        processData: false,
        contentType: false,
        success: function(){
            $('#vehicleForm')[0].reset();
            loadVehicles(currentPage);
        }
    });
}

/* DELETE */
function openDelete(id){
    $('#delete_id').val(id);
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function confirmDelete(){
    let id = $('#delete_id').val();
    $.get("<?= base_url('vehicles/delete') ?>/"+id, function(){
        bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
        loadVehicles(currentPage);
    });
}

loadVehicles();
</script>

<?= $this->endSection() ?>
