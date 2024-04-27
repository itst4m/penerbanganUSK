<?php
require '../functions.php';
include 'userheader.php';
?>

<?php
// Inisialisasi variabel
$row = [];

// Periksa apakah parameter URL flight_id ada
$flight_id = $_GET['id'] ?? null;

if ($flight_id) {
    // Mendapatkan informasi penerbangan berdasarkan flight_id
    $flight_info = mysqli_query($db, "SELECT * FROM flight WHERE flight_id = $flight_id");

    // Memastikan data penerbangan ditemukan
    $row = mysqli_fetch_assoc($flight_info);

    // Redirect atau tampilkan pesan kesalahan jika tidak ditemukan
    if (!$row) {
        // Handle error, misalnya redirect ke halaman error atau tampilkan pesan
        echo "Oops! Terjadi kesalahan.";
        exit;
    }
} else {
    echo "Oops! Masih error.";
    exit;
}

if (isset($_POST["submit"])) {
    // Mendapatkan user_id dari cookie
    $user_id = getUserIdFromCookie();

    if ($user_id) {
        // Mendapatkan data dari formulir
        $nama = mysqli_real_escape_string($db, $_POST['nama']);
        $email = mysqli_real_escape_string($db, $_POST['email']);
        $no_telpon = mysqli_real_escape_string($db, $_POST['no_telpon']);
        $alamat = mysqli_real_escape_string($db, $_POST['alamat']);
        $kode_pos = mysqli_real_escape_string($db, $_POST['kode_pos']);
        $jumlah_penumpang = mysqli_real_escape_string($db, $_POST['jumlah_penumpang']);

        // Query untuk menyimpan pesanan ke dalam database
        $query = "INSERT INTO `order` (flight_id, user_id, nama, email, no_telpon, alamat, kode_pos, jumlah_penumpang) 
                  VALUES ('$flight_id', '$user_id', '$nama', '$email', '$no_telpon', '$alamat', '$kode_pos', $jumlah_penumpang)";

        // Eksekusi query
        if (mysqli_query($db, $query)) {
            // Ambil kuota penerbangan dari database
            $flight_info = mysqli_query($db, "SELECT kuota_penerbangan FROM flight WHERE flight_id = $flight_id");
            $row = mysqli_fetch_assoc($flight_info);
            $kuota_penerbangan = $row['kuota_penerbangan'];

            // Kurangi kuota penerbangan dengan jumlah penumpang yang dipesan
            $update_kuota = $kuota_penerbangan - intval($jumlah_penumpang);

            if ($update_kuota > 0) {
                // Eksekusi query untuk memperbarui kuota penerbangan
                $query_kuota = "UPDATE flight SET kuota_penerbangan = $update_kuota WHERE flight_id = $flight_id";
                if (mysqli_query($db, $query_kuota)) {
                    // Kuota penerbangan berhasil diperbarui
                    echo "
                    <script>
                        alert('Pesanan berhasil dibuat!');
                        document.location.href = 'pesanan.php';
                    </script>
                    ";
                } else {
                    // Handle kesalahan jika gagal memperbarui kuota penerbangan
                    echo "Gagal memperbarui kuota penerbangan: " . mysqli_error($db);
                }
            } else {
                // Kuota penerbangan habis
                echo "
                <script>
                    alert('Maaf, kuota penerbangan untuk penerbangan ini telah habis. Pesanan dibatalkan.');
                    document.location.href = 'index.php';
                </script>
                ";
            }
        } else {
            // Handle kesalahan jika query pesanan tidak berhasil dieksekusi
            echo "Gagal membuat pesanan: " . mysqli_error($db);
        }
    } else {
        // Handle kesalahan jika user_id tidak tersedia dalam cookie
        echo "User ID tidak ditemukan dalam cookie.";
    }
}

?>


<body>
    <br>
    <div class="container mt-4">
        <!-- Formulir untuk input ke database -->
        <form method="post" action="">
            <input type="hidden" name="flight_id" value="<?= $flight_id; ?>">
            <!-- Menampilkan informasi penerbangan yang diambil otomatis -->
            <div class="row g-3">
                <div class="col-6">
                    <label for="nama_maskapai" class="form-label">Nama Maskapai</label>
                    <input type="text" class="form-control" value="<?= $row['nama_maskapai'] ?? 'N/A'; ?>">
                </div>
                <div class="col-6">
                    <label for="bandara_asal" class="form-label">Bandara Asal</label>
                    <input type="text" class="form-control" value="<?= $row['bandara_asal'] ?? 'N/A'; ?>">
                </div>
                <div class="col-6">
                    <label for="bandara_tujuan" class="form-label">Bandara Tujuan</label>
                    <input type="text" class="form-control" value="<?= $row['bandara_tujuan'] ?? 'N/A'; ?>">
                </div>
                <div class="col-6">
                    <label for="harga" class="form-label">Harga</label>
                    <input type="text" class="form-control" value="<?= $row['harga_tiket'] ?? 'N/A'; ?>">
                </div>
                <div class="col-6">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" name="nama" required>
                </div>
                <div class="col-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="col-6">
                    <label for="no_telpon" class="form-label">No Telepon</label>
                    <input type="text" class="form-control" name="no_telpon" required>
                </div>
                <div class="col-6">
                    <label for="alamat" class="form-label">Alamat</label>
                    <input type="text" class="form-control" name="alamat" required>
                </div>
                <div class="col-6">
                    <label for="kode_pos" class="form-label">Kode Pos</label>
                    <input type="text" class="form-control" name="kode_pos" required>
                </div>
                <div class="col-6">
                    <label for="jumlah_penumpang" class="form-label">Jumlah Penumpang</label>
                    <input type="number" name="jumlah_penumpang" id="jumlah_penumpang" class="form-control" min="0" required>
                </div>
            </div>
            <br>
            <button type="submit" name="submit" class="btn btn-danger">Buat order</button>
        </form>
    </div>
    <script src="../Style/js/bootstrap.bundle.min.js"></script>
</body>