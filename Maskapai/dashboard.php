<?php
require '../functions.php';
include 'maskapaiheader.php';
?>

<body>
    <div class="container mt-4">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php
            // Mengambil data pesanan dari tabel order dan data penerbangan dari tabel flight
            $query = "SELECT * FROM `order` 
                      JOIN flight ON `order`.flight_id = flight.flight_id";
            $result = mysqli_query($db, $query);

            // Mengambil total tiket terjual dari tabel order
            $total_tiket_terjual_query = "SELECT SUM(jumlah_penumpang) AS total_penumpang FROM `order`";
            $total_tiket_terjual_result = mysqli_query($db, $total_tiket_terjual_query);
            $total_tiket_terjual_row = mysqli_fetch_assoc($total_tiket_terjual_result);
            $total_tiket_terjual = $total_tiket_terjual_row['total_penumpang'];

            // Loop untuk menampilkan data pesanan dan penerbangan dalam bentuk card
            foreach ($result as $row) :
            ?>
                <div class="card" style="width: 18rem; margin-bottom: 20px;">
                    <img class="card-img-top" src="../img/<?= $row["foto_flight"]; ?>" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">Tiket <?= $row["nama_maskapai"]; ?> Terjual : <?= $total_tiket_terjual; ?></h5>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="../Style/js/bootstrap.bundle.min.js"></script>
</body>