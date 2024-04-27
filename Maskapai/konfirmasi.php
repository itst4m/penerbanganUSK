<?php
require '../functions.php';
include 'maskapaiheader.php';

// Jika tombol konfirmasi atau cancel diklik
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $order_id = $_GET['order_id']; // Ambil order_id dari parameter URL

    // Update status sesuai dengan aksi yang dipilih
    if ($action == 'confirm') {
        $status = 'Confirmed';
    } elseif ($action == 'cancel') {
        $status = 'Cancelled';
    }

    // Query untuk update status di database
    $update_query = "UPDATE `order` SET status='$status' WHERE order_id='$order_id'";
    mysqli_query($db, $update_query);
}

?>


<body>
    <div class="container mt-4">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php
            // Mengambil data pesanan dari tabel order dan data penerbangan dari tabel flight
            $query = "SELECT * FROM `order` 
                      JOIN flight ON `order`.flight_id = flight.flight_id";
            $result = mysqli_query($db, $query);

            // Loop untuk menampilkan data pesanan dan penerbangan dalam bentuk card
            foreach ($result as $row) :
            ?>
                <div class="card" style="width: 18rem; margin-bottom: 20px;">
                    <img class="card-img-top" src="../img/<?= $row["foto_flight"]; ?>" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">Ms/Mr <?= $row["nama"]; ?></h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Tanggal Keberangkatan: <?= $row["tanggal_penerbangan"]; ?></li>
                        <li class="list-group-item">Bandara Asal: <?= $row["bandara_asal"]; ?></li>
                        <li class="list-group-item">Bandara Tujuan: <?= $row["bandara_tujuan"]; ?></li>
                        <li class="list-group-item">Jam Berangkat: <?= $row["jam_berangkat"]; ?></li>
                        <li class="list-group-item">Jam Tiba: <?= $row["jam_tiba"]; ?></li>
                        <li class="list-group-item text-danger">Status: <?= $row['status']; ?></li>
                    </ul>
                    <div class="card-body">
                        <!-- Tambahkan parameter action dan order_id di URL -->
                        <a href="?action=confirm&order_id=<?= $row['order_id']; ?>" class="btn btn-warning btn-sm">Konfirmasi</a>
                        <a href="?action=cancel&order_id=<?= $row['order_id']; ?>" class="btn btn-warning btn-sm">Cancel</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="../Style/js/bootstrap.bundle.min.js"></script>
</body>

</html>