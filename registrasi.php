<?php

require 'functions.php';
include 'mainheader.php';

if (isset($_POST["register"])) {

    if (registrasi($_POST) > 0) {
        echo "
        <script>
        alert('registrasi berhasil!');
        document.location.href = 'login.php';
        </script>
        ";
    } else {
        echo mysqli_error($db);
    }
}
?>

<body>
    <br>
    <br>
    <form action="" method="post">
        <div class="container-sm">
            <div class="row mb-3">
                <label for="username" class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                </div>
            </div>
            <div class="row mb-3">
                <label for="email" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="email" name="email" placeholder="Email">
                </div>
            </div>
            <div class="row mb-3">
                <label for="password" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                </div>
            </div>
            <div class="row mb-3">
                <label for="password2" class="col-sm-2 col-form-label">Konfirmasi Password</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="password2" name="password2" placeholder="Konfirmasi Password">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" name="register" class="btn btn-primary">Registrasi</button>
                </div>
            </div>
        </div>
    </form>



    <script src="./Style/js/bootstrap.bundle.min.js"></script>
</body>

</html>