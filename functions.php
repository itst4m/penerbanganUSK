<?php
// koneksi ke db
$db = mysqli_connect("localhost", "root", "", "tiket_penerbangan");

//untuk menerima query
function query($query)
{
    //untuk menggunakan variabel db yg udah dibuat
    global $db;
    $result = mysqli_query($db, $query);
    $rows = [];
    // mysqli_.._assoc = untuk mengambil satu baris data dalam table
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function tambah($data)
{
    //buat variabel untuk ambil data dari tiap elemen dalam form
    //htmlspecialchars menghindari user iseng yang ingin menyisipkan coding html dalam input.
    //jika mau simple gausah pakai aja jadi tinggal $data["nama"];
    $nama_maskapai = htmlspecialchars($data["nama_maskapai"]);
    $tanggal_penerbangan = htmlspecialchars($data["tanggal_penerbangan"]);
    $kuota_penerbangan = htmlspecialchars($data["kuota_penerbangan"]);
    $bandara_asal =  htmlspecialchars($data["bandara_asal"]);
    $bandara_tujuan =  htmlspecialchars($data["bandara_tujuan"]);
    $jam_berangkat = htmlspecialchars($data["jam_berangkat"]);
    $jam_tiba =  htmlspecialchars($data["jam_tiba"]);
    $harga_tiket =  htmlspecialchars($data["harga_tiket"]);
    $foto_flight = upload();
    if (!$foto_flight) {
        exit; // Jika upload gagal, hentikan eksekusi script
    }

    //query insert data
    $query = "INSERT INTO flight VALUES
    (NULL, '$bandara_asal', '$bandara_tujuan', '$nama_maskapai', '$tanggal_penerbangan', '$jam_berangkat'
    , '$jam_tiba', '$kuota_penerbangan', '$harga_tiket', '$foto_flight')";

    global $db;
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

function upload()
{
    $namaFile = $_FILES['foto_flight']['name'];
    $ukuranFile = $_FILES['foto_flight']['size'];
    $error = $_FILES['foto_flight']['error'];
    $tmpName = $_FILES['foto_flight']['tmp_name'];

    $ekstensGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensGambar = explode('.', $namaFile);
    $ekstensGambar = strtolower(end($ekstensGambar));

    if (!in_array($ekstensGambar, $ekstensGambarValid)) {
        echo "<script>
            alert('Yang Anda upload bukan gambar!');
        </script>";
        return false;
    }
    if ($ukuranFile > 1000000) {
        echo "<script>
            alert('Ukuran gambar terlalu besar!');
        </script>";
        return false;
    }

    // Pindahkan file ke direktori yang diinginkan
    $destination = '../img/' . $namaFile;
    if (move_uploaded_file($tmpName, $destination)) {
        return $namaFile; // Kembalikan nama file jika berhasil diupload
    } else {
        echo "<script>
            alert('Gagal mengupload gambar!');
        </script>";
        return false;
    }
}

function hapus($id)
{
    global $db;
    mysqli_query($db, "DELETE FROM flight WHERE flight_id = $id");
    return mysqli_affected_rows($db);
}

function ubah($data)
{


    $id = $data["id"];
    $foto_flight = htmlspecialchars($data["foto_flight"]);
    $nama_maskapai = htmlspecialchars($data["nama_maskapai"]);
    $tanggal_penerbangan = htmlspecialchars($data["tanggal_penerbangan"]);
    $kuota_penerbangan = htmlspecialchars($data["kuota_penerbangan"]);
    $bandara_asal =  htmlspecialchars($data["bandara_asal"]);
    $bandara_tujuan =  htmlspecialchars($data["bandara_tujuan"]);
    $jam_berangkat = htmlspecialchars($data["jam_berangkat"]);
    $jam_tiba =  htmlspecialchars($data["jam_tiba"]);
    $harga_tiket =  htmlspecialchars($data["harga_tiket"]);



    //query insert data
    $query = "UPDATE flight SET 
    foto_flight = '$foto_flight',
    nama_maskapai = '$nama_maskapai', 
    tanggal_penerbangan = '$tanggal_penerbangan', 
    kuota_penerbangan = '$kuota_penerbangan', 
    bandara_asal = '$bandara_asal',
    bandara_tujuan = '$bandara_tujuan',
    jam_berangkat = '$jam_berangkat', 
    jam_tiba = '$jam_tiba', 
    harga_tiket = '$harga_tiket'
    WHERE flight_id = $id
    ";


    global $db;
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}


function registrasi($data)
{
    global $db;
    //agar user tidak memasukan karakter slash dan memasukan huruf kecil
    $username = strtolower(stripslashes($data['username']));

    //supaya aman dari sql injection seperti tdk sengaja memasukan kutip mysqli_real_escape_string
    $password = mysqli_real_escape_string($db, $data["password"]);
    $password2 = mysqli_real_escape_string($db, $data["password2"]);

    $email = htmlspecialchars($data['email']);

    // Periksa kesesuaian password
    if ($password !== $password2) {
        echo "<script>
        alert('Konfirmasi password tidak sesuai!');
        </script>";
        return false; // Mengembalikan false jika konfirmasi password tidak sesuai
    }
    // Hash password menggunakan password_hash()
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Periksa apakah username sudah ada
    $result = mysqli_query($db, "SELECT username FROM user WHERE username = '$username'");
    if (mysqli_fetch_assoc($result)) {
        echo "<script>
        alert('Username sudah terdaftar!');
        </script>";
        return false; // Mengembalikan false jika username sudah terdaftar
    }

    // Simpan data registrasi ke dalam database
    mysqli_query($db, "INSERT INTO user (username, email, password, role) VALUES ('$username', '$email', '$hashedPassword', 'user')");

    return mysqli_affected_rows($db); // Mengembalikan jumlah baris yang terpengaruh oleh operasi INSERT
}





function getUserIdFromCookie()
{
    if (isset($_COOKIE['user_id'])) {
        return $_COOKIE['user_id'];
    } else {
        return null;
    }
}

// gunakan var_dump untuk memeriksa hasil queri apakah udah sesuai belum
// $mydata = mysqli_fetch_row($result);
// var_dump($mydata);

// untuk cek apakah ada error
// if (!$result){
//     echo mysqli_error($db);
// }