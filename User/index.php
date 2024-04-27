<?php
require '../functions.php';
include 'userheader.php';
?>


<body>
    <div class="container mt-4">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

            <?php
            // Mengambil data produk dari tabel flight
            $flight = mysqli_query($db, "SELECT * FROM flight");

            // foreach loop untuk menampilkan data dalam bentuk card
            foreach ($flight as $row) :
            ?>
                <div class="card" style="width: 18rem; margin-bottom: 20px;">
                    <img class="card-img-top" src="../img/<?= $row["foto_flight"]; ?>" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title"><?= $row["nama_maskapai"]; ?></h5>
                        <p class="card-text">Tanggal Penerbangan: <?= $row["tanggal_penerbangan"]; ?></p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Kuota Penerbangan: <?= $row["kuota_penerbangan"]; ?></li>
                        <li class="list-group-item">Bandara Asal: <?= $row["bandara_asal"]; ?></li>
                        <li class="list-group-item">Bandara Tujuan: <?= $row["bandara_tujuan"]; ?></li>
                    </ul>
                    <div class="card-body">
                        <p>Jam Berangkat: <?= $row["jam_berangkat"]; ?></p>
                        <p>Jam Tiba: <?= $row["jam_tiba"]; ?></p>
                        <p>Harga Tiket: <?= $row["harga_tiket"]; ?></p>
                    </div>
                    <a href="Order.php?id=<?= $row['flight_id']; ?>" class="btn btn-danger">Pesan</a>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
    <script src="../Style/js/bootstrap.bundle.min.js"></script>
</body>