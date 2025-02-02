<?php include 'root/head.php' ?>

<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-12">
                <?php
include('../koneksi/koneksi.php'); // Pastikan Anda telah memasukkan kode koneksi ke database

if (isset($_GET['id'])) {
    $id_barang = $_GET['id'];

    // Buat query SELECT untuk mengambil detail barang berdasarkan ID
    $query = "SELECT * FROM Barang WHERE id_barang = $id_barang";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        echo "<div class='row g-0'>
                <div class='col-md-4'>
                    <img src='../assets/foto_produk/" . $row['foto'] . "' class='img-fluid rounded-start' alt=''>
                </div>
                <div class='col-md-8'>
                    <div class='card-body'>
                        <ul class='list-group list-group-flush'>
                            <li class='list-group-item'>Nama Barang : " . $row['nama_barang'] . "</li>
                            <li class='list-group-item'>Kategori : " . $row['kategori'] . "</li>
                            <li class='list-group-item'>Jumlah Stok : " . $row['jumlah_stok'] . "</li>
                            <li class='list-group-item'>Stok Minimum : " . $row['stok_minimum'] . "</li>
                            <li class='list-group-item'>Kondisi : " . $row['kondisi'] . "</li>
                            <li class='list-group-item'>Tanggal Pembelian : " . $row['tanggal_pembelian'] . "</li>
                        </ul>
                    </div>
                </div>
            </div>";
    } else {
        echo "Detail barang tidak ditemukan.";
    }

    // Tutup koneksi ke database
    $conn->close();
} else {
    echo "Parameter 'id' tidak ditemukan.";
}
?>

                </div>
            </div>

        </div>
    </section>
</main>

<?php include 'root/footer.php' ?>