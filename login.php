<?php

require 'functions.php';
include 'mainheader.php';

// cek cookie 
if (isset($_COOKIE['id']) && isset($_COOKIE['key'])) {
    $id = $_COOKIE['id'];
    $key = $_COOKIE['key'];
    // ambil username berdasarkan id
    $result = mysqli_query($db, "SELECT username FROM user WHERE user_id = $id");
    $row = mysqli_fetch_assoc($result);
}

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query($db, "SELECT * FROM user WHERE username = '$username'");

    if (mysqli_num_rows($result) === 1) {
        // Cek password
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row["password"])) {

            // Set user_id cookie
            setcookie('user_id', $row['user_id'], time() + 3600, '/');

            //cek pakai remeber 
            if (isset($_POST['remember'])) {
                //buat coookie
                setcookie('id', $row['user_id'], time() + 3600, '/');
                setcookie('key', $row['username'], time() + 3600, '/');
            }

            // Periksa role
            if ($row["role"] == "user") {
                header("Location: /Penerbangan/User/index.php");
                exit;
            } elseif ($row["role"] == "admin") {
                header("Location: /Penerbangan/Admin/index.php");
                exit;
            } elseif ($row["role"] == "maskapai") {
                header("Location: /Penerbangan/Maskapai/dashboard.php");
                exit;
            } else {
                echo "Role tidak valid";
            }
        } else {
            echo "<script>alert('Password salah!');</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!');</script>";
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
                <input type="text" class="form-control" id="username" name="username" placeholder="username">
            </div>
        </div>
        <div class="row mb-3">
            <label for="password" class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="password" name="password" placeholder="password">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-10 offset-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="checkRemember">Remember me</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-10 offset-sm-2">
                <button type="submit" name="login" class="btn btn-primary">Sign in</button>
            </div>
        </div>
        </div>
    </form>

    <script src="./Style/js/bootstrap.bundle.min.js"></script>
</body>