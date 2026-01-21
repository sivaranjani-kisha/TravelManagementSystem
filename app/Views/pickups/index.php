<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h3 class="mb-3">Pickup Points</h3>

<!-- Add Pickup -->
<div class="card mb-3">
    <div class="card-body">
        <form id="pickupForm" class="row g-2">
            <input type="hidden" name="id" id="id">

            <div class="col-md-6">
                <input type="text"
                       name="pickup_name"
                       id="pickup_name"
                       class="form-control"
                       placeholder="Enter Pickup Point"
                       required>
            </div>
             <div class="col-md-6">
                <input type="text"
                       name="pickupdesc"
                       id="description"
                       class="form-control"
                       placeholder="Enter desc  "
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
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th width="80">S.No</th>
                    <th>Pickup Point</th>
                    <th>Description</th>
                    <th width="180">Action</th>
                </tr>
            </thead>
            <tbody id="pickupTable"></tbody>
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
        <h5 class="modal-title">Edit Pickup Point</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="edit_id">
        <input type="text" id="edit_pickup_name" class="form-control">
        <input type="text" id="edit_description" class="form-control">
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" onclick="updatePickup()">OK</button>
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
        <p>Do you want to delete this pickup point?</p>

        <input type="hidden" id="delete_id">

        <button class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <button class="btn btn-danger" onclick="confirmDelete()">Yes</button>
      </div>

    </div>
  </div>
</div>

<script>
let currentPage = 1;

function loadPickups(page = 1){
    currentPage = page;

    $.get("<?= base_url('pickups/list') ?>", { page: page }, function(res){
        let rows = '';
        res.data.forEach((p,i)=>{
            rows += `<tr>
                <td>${((page-1)*res.limit) + (i+1)}</td>
                <td>${p.pickup_name}</td>
                <td>${p.description}</td>
                <td>
                    <button class="btn btn-sm btn-warning"
                        onclick="openEdit(${p.id})">Edit</button>
                    <button class="btn btn-sm btn-danger"
                        onclick="openDelete(${p.id})">Delete</button>
                </td>
            </tr>`;
        });
        $('#pickupTable').html(rows);

        renderPagination(res.total, res.limit, page);
    });
}

function renderPagination(total, limit, page){
    let pages = Math.ceil(total / limit);
    let html = '';

    // Previous button
    html += `<li class="page-item ${page === 1 ? 'disabled' : ''}">
        <a class="page-link" href="javascript:void(0)"
           onclick="loadPickups(${page-1})">Previous</a>
    </li>`;

    // Page numbers
    for(let i = 1; i <= pages; i++){
        html += `<li class="page-item ${i === page ? 'active' : ''}">
            <a class="page-link" href="javascript:void(0)"
               onclick="loadPickups(${i})">${i}</a>
        </li>`;
    }

    // Next button
    html += `<li class="page-item ${page === pages ? 'disabled' : ''}">
        <a class="page-link" href="javascript:void(0)"
           onclick="loadPickups(${page+1})">Next</a>
    </li>`;

    $('#pagination').html(html);
}


/* ADD */
$('#pickupForm').submit(function(e){
    e.preventDefault();
    $.post("<?= base_url('pickups/save') ?>",
        $(this).serialize(),
        function(){
            $('#pickupForm')[0].reset();
            loadPickups(currentPage);
        }
    );
});

/* EDIT OPEN */
function openEdit(id){
    $.get("<?= base_url('pickups/edit') ?>/"+id, function(res){
        $('#edit_id').val(res.id);
        $('#edit_pickup_name').val(res.pickup_name);
        $('#edit_description').val(res.description);
        new bootstrap.Modal(document.getElementById('editModal')).show();
    });
}

/* UPDATE */
function updatePickup(){
    $.post("<?= base_url('pickups/save') ?>", {
        id: $('#edit_id').val(),
        pickup_name: $('#edit_pickup_name').val(),
        pickupdesc: $('#edit_description').val()
    }, function(){
        bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
        loadPickups(currentPage);
    });
}

/* DELETE OPEN */
function openDelete(id){
    $('#delete_id').val(id);
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

/* DELETE CONFIRM */
function confirmDelete(){
    let id = $('#delete_id').val();
    $.get("<?= base_url('pickups/delete') ?>/"+id, function(){
        bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
        loadPickups(currentPage);
    });
}

loadPickups();
</script>


<?= $this->endSection() ?>
