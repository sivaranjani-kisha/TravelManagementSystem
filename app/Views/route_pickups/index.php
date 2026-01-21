<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h3 class="mb-3">Route & Pickup Mapping</h3>

<!-- Add Mapping -->
<div class="card mb-3">
    <div class="card-body">
        <form id="mapForm" class="row g-3">

            <div class="col-md-4">
                <select name="route_id" class="form-select" required>
                    <option value="">Select Route</option>
                    <?php foreach($routes as $r): ?>
                        <option value="<?= $r['id'] ?>">
                            <?= $r['route_name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
              <div class="col-md-6">
    <div class="border rounded p-2" style="max-height:180px; overflow-y:auto;">
        <?php foreach($pickups as $p): ?>
            <div class="form-check">
                <input class="form-check-input"
                       type="checkbox"
                       name="pickup_ids[]"
                       value="<?= $p['id'] ?>"
                       id="pickup<?= $p['id'] ?>">
                <label class="form-check-label" for="pickup<?= $p['id'] ?>">
                    <?= $p['pickup_name'] ?>
                </label>
            </div>
        <?php endforeach; ?>
    </div>
</div>

            </div>

            <div class="col-md-2 d-grid">
                <button type="button" class="btn btn-primary" onclick="openSaveModal()">
    Save
</button>

            </div>

        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th width="80">S.No</th>
                    <th>Route</th>
                    <th>Pickup Points</th>
                    <th width="120">Action</th>
                </tr>
            </thead>
            <tbody id="mapTable"></tbody>
        </table>

        <!-- Pagination -->
        <nav>
            <ul class="pagination" id="pagination"></ul>
        </nav>
    </div>
</div>
<!-- SAVE CONFIRMATION MODAL -->
<div class="modal fade" id="saveModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-body text-center">
        <h5>Confirm Save</h5>
        <p>Do you want to save this route & pickup mapping?</p>

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
        <p>Do you want to delete this mapping?</p>
        <input type="hidden" id="delete_id">
        <button class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <button class="btn btn-danger" onclick="confirmDelete()">Yes</button>
      </div>
    </div>
  </div>
</div>

<script>
let currentPage = 1;

function loadMap(page = 1){
    currentPage = page;

    $.get("<?= base_url('route-pickups/list') ?>", { page: page }, function(res){
        let rows = '';
        res.data.forEach((r,i)=>{
            rows += `<tr>
                <td>${((page-1)*res.limit) + (i+1)}</td>
                <td>${r.route_name}</td>
                <td>${r.pickups}</td>
                <td>
                    <button class="btn btn-sm btn-danger"
                        onclick="openDelete(${r.id})">Delete</button>
                </td>
            </tr>`;
        });
        $('#mapTable').html(rows);

        renderPagination(res.total, res.limit, page);
    });
}

function renderPagination(total, limit, page){
    let pages = Math.ceil(total / limit);
    let html = '';

    html += `<li class="page-item ${page === 1 ? 'disabled' : ''}">
        <a class="page-link" href="javascript:void(0)"
           onclick="loadMap(${page-1})">Previous</a>
    </li>`;

    for(let i=1; i<=pages; i++){
        html += `<li class="page-item ${i === page ? 'active' : ''}">
            <a class="page-link" href="javascript:void(0)"
               onclick="loadMap(${i})">${i}</a>
        </li>`;
    }

    html += `<li class="page-item ${page === pages ? 'disabled' : ''}">
        <a class="page-link" href="javascript:void(0)"
           onclick="loadMap(${page+1})">Next</a>
    </li>`;

    $('#pagination').html(html);
}
/* OPEN SAVE MODAL */
function openSaveModal(){
    new bootstrap.Modal(document.getElementById('saveModal')).show();
}

/* CONFIRM SAVE */
function confirmSave(){
    bootstrap.Modal.getInstance(document.getElementById('saveModal')).hide();

    $.post("<?= base_url('route-pickups/save') ?>",
        $('#mapForm').serialize(),
        function(){
            loadMap(currentPage);
          
            $('#mapForm')[0].reset();
        }
    );
}

/* SAVE */

/* DELETE */
function openDelete(id){
    $('#delete_id').val(id);
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function confirmDelete(){
    let id = $('#delete_id').val();
    $.get("<?= base_url('route-pickups/delete') ?>/"+id, function(){
        bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
        loadMap(currentPage);
    });
}

loadMap();
</script>

<?= $this->endSection() ?>
