<?php
require '../functions.php';
include 'userheader.php';
?>

<body>
    <div class="container mt-4">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php
            // Ambil user_id dari cookie
            $user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : null;

            // Periksa apakah user_id tersedia sebelum melanjutkan
            if ($user_id) {
                // Query untuk mengambil data pesanan berdasarkan user_id
                $query = "SELECT * FROM `order` 
                          JOIN flight ON `order`.flight_id = flight.flight_id 
                          WHERE `order`.user_id = $user_id";
                $result = mysqli_query($db, $query);

                // Loop untuk menampilkan data pesanan dan penerbangan dalam bentuk card
                foreach ($result as $row) :
                    $total_harga = $row["jumlah_penumpang"] * $row['harga_tiket'];

            ?>
                    <div class="card" style="width: 18rem; margin-bottom: 20px;">
                        <img class="card-img-top" src="../img/<?= $row["foto_flight"]; ?>" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title">Ms/Mr <?= $row["nama"]; ?></h5>
                        </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Kode Booking: <?= $row["order_id"]; ?></li>
                                <li class="list-group-item">Tanggal Keberangkatan: <?= $row["tanggal_penerbangan"]; ?></li>
                                <li class="list-group-item">Bandara Asal: <?= $row["bandara_asal"]; ?></li>
                                <li class="list-group-item">Bandara Tujuan: <?= $row["bandara_tujuan"]; ?></li>
                                <li class="list-group-item">Jam Berangkat: <?= $row["jam_berangkat"]; ?></li>
                                <li class="list-group-item">Jam Tiba: <?= $row["jam_tiba"]; ?></li>
                                <li class="list-group-item">Total Harga: <?= $total_harga; ?></li>
                            </ul>
                            <div class="card-body">
                                <p class="card-text text-danger">Status: <?= $row['status']; ?></p>
                            </div>
                        </div>
                <?php endforeach;
            } else {
                // Jika user_id tidak tersedia dalam cookie
                echo "User ID tidak ditemukan dalam cookie.";
            }
                ?>
                    </div>
        </div>
        <script src="../Style/js/bootstrap.bundle.min.js"></script>
</body>

</html>