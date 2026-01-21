<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h3 class="mb-3">Routes</h3>

<!-- Add Route -->
<div class="card mb-3">
    <div class="card-body">
        <form id="routeForm" class="row g-2">
            <input type="hidden" name="id" id="id">

            <div class="col-md-6">
                <input type="text"
                       name="route_name"
                       id="route_name"
                       class="form-control"
                       placeholder="Enter Route Name"
                       required>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
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
                    <th width="180">Action</th>
                </tr>
            </thead>
            <tbody id="routeTable"></tbody>
        </table>
        <div class="mt-3">
    <nav>
        <ul class="pagination" id="pagination"></ul>
    </nav>
</div>

    </div>
</div>

<!-- ================= EDIT MODAL ================= -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Edit Route</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="edit_id">
        <input type="text" id="edit_route_name" class="form-control">
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" onclick="updateRoute()">OK</button>
      </div>

    </div>
  </div>
</div>

<!-- ================= DELETE MODAL ================= -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-body text-center">
        <h5>Are you sure?</h5>
        <p>Do you want to delete this route?</p>

        <input type="hidden" id="delete_id">

        <button class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <button class="btn btn-danger" onclick="confirmDelete()">Yes</button>
      </div>

    </div>
  </div>
</div>

<script>
let currentPage = 1;

function loadRoutes(page = 1){
    currentPage = page;

    $.get("<?= base_url('routes/list') ?>", { page: page }, function(res){
        let rows = '';
        res.data.forEach((r,i)=>{
            rows += `<tr>
                <td>${((page-1)*res.limit) + (i+1)}</td>
                <td>${r.route_name}</td>
                <td>
                    <button class="btn btn-sm btn-warning"
                        onclick="openEdit(${r.id})">Edit</button>
                    <button class="btn btn-sm btn-danger"
                        onclick="openDelete(${r.id})">Delete</button>
                </td>
            </tr>`;
        });
        $('#routeTable').html(rows);

        renderPagination(res.total, res.limit, page);
    });
}

function renderPagination(total, limit, page){
    let pages = Math.ceil(total / limit);
    let html = '';
    

    // Previous
    html += `<li class="page-item ${page === 1 ? 'disabled' : ''}">
        <a class="page-link" href="javascript:void(0)"
           onclick="loadRoutes(${page-1})">Previous</a>
    </li>`;

    // Numbers
    for(let i = 1; i <= pages; i++){
        html += `<li class="page-item ${i === page ? 'active' : ''}">
            <a class="page-link" href="javascript:void(0)"
               onclick="loadRoutes(${i})">${i}</a>
        </li>`;
    }

    // Next
    html += `<li class="page-item ${page === pages ? 'disabled' : ''}">
        <a class="page-link" href="javascript:void(0)"
           onclick="loadRoutes(${page+1})">Next</a>
    </li>`;

    $('#pagination').html(html);
}

/* ADD */
$('#routeForm').submit(function(e){
    e.preventDefault();
    $.post("<?= base_url('routes/save') ?>",
        $(this).serialize(),
        function(){
            $('#routeForm')[0].reset();
            loadRoutes(currentPage);
        }
    );
});

/* EDIT */
function openEdit(id){
    $.get("<?= base_url('routes/edit') ?>/"+id, function(res){
        $('#edit_id').val(res.id);
        $('#edit_route_name').val(res.route_name);
        new bootstrap.Modal(document.getElementById('editModal')).show();
    });
}

/* UPDATE */
function updateRoute(){
    $.post("<?= base_url('routes/save') ?>", {
        id: $('#edit_id').val(),
        route_name: $('#edit_route_name').val()
    }, function(){
        bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
        loadRoutes(currentPage);
    });
}

/* DELETE */
function openDelete(id){
    $('#delete_id').val(id);
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function confirmDelete(){
    let id = $('#delete_id').val();
    $.get("<?= base_url('routes/delete') ?>/"+id, function(){
        bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
        loadRoutes(currentPage);
    });
}

loadRoutes();
</script>
<?= $this->endSection() ?>
