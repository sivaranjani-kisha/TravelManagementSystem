<!DOCTYPE html>
<html>
<head>
    <title>Admin Register</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
        body {
            background: #f4f6f9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-box {
            width: 380px;
        }
    </style>
</head>
<body>

<div class="register-box">
    <div class="card shadow">
        <div class="card-body">

            <div class="text-center mb-3">
                <div style="font-size:40px;">🚍</div>
                <h5 class="fw-bold">Transport Agency</h5>
                <small class="text-muted">Admin Registration</small>
            </div>

            <form id="registerForm">
                <div class="mb-3">
                    <input type="text"
                           name="name"
                           class="form-control"
                           placeholder="Full Name"
                           required>
                </div>

                <div class="mb-3">
                    <input type="text"
                           name="mobile"
                           class="form-control"
                           placeholder="Mobile Number"
                           maxlength="10"
       oninput="this.value = this.value.replace(/[^0-9]/g,'')"
                           required>
                </div>

                <div class="mb-3">
                    <input type="password"
                           name="password"
                           class="form-control"
                           placeholder="Password"
                           
                           required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success">
                        Register
                    </button>
                </div>
            </form>

            <p id="msg" class="text-danger text-center mt-3"></p>

            <div class="text-center mt-3">
                <small>
                    Already have an account?
                    <a href="<?= base_url('login') ?>">Login</a>
                </small>
            </div>

        </div>
    </div>
</div>

<script>
$('#registerForm').submit(function(e){
    e.preventDefault();

    $.post("<?= base_url('register/save') ?>",
        $(this).serialize(),
        function(res){
            if(res.status === 'error'){
                $('#msg').text(res.message);
            } else {
                window.location.href = "<?= base_url('login') ?>";
            }
        }
    );
});
</script>

</body>
</html>
