<?php include 'root/head.php' ?>


<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="col-12">
                <div class="card recent-sales overflow-auto">


                    <div class="card-body">
                        <h5 class="card-title"> Riwayat Pengajuan</h5>
                        <?php
include('../koneksi/koneksi.php');  // Pastikan Anda telah memasukkan kode koneksi ke database

// Mendapatkan informasi pengguna yang sudah login
$pengguna_id = $_SESSION['user_id']; // Ganti dengan nama variabel sesi Anda

// Query untuk mengambil data peminjaman sesuai dengan ID pengguna dan informasi nama barang dari tabel Pengajuan_Peminjaman dan Barang
$query = "SELECT PM.id_peminjaman, PP.id_pengajuan, B.nama_barang,  PM.tanggal_peminjaman, PM.tanggal_pengembalian
          FROM Peminjaman PM
          JOIN pengajuan_peminjaman PP ON PM.id_pengajuan = PP.id_pengajuan
          JOIN Barang B ON PP.id_barang = B.id_barang
          WHERE PP.id_pengguna = '$pengguna_id'"; // Menampilkan hanya data dengan ID pengguna yang sesuai

$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<table class='table table-borderless datatable'>
            <thead>
                <tr>
                    <th scope='col'>Nama Barang</th>
                   
                    <th scope='col'>Tanggal Peminjaman</th>
                    <th scope='col'>Tanggal Pengembalian</th>
                </tr>
            </thead>
            <tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row['nama_barang'] . "</td>
             
                <td> <span class='badge bg-warning'>" . $row['tanggal_peminjaman'] . "</span></td>
                <td><span class='badge bg-success'>" . $row['tanggal_pengembalian'] . "</span></td>
              </tr>";
    }

    echo "</tbody></table>";
} else {
    echo "Tidak ada data peminjaman.";
}

$conn->close();
?>

                    </div>

                </div>
            </div>
        </div>
    </section>
</main>
<?php include 'root/footer.php' ?>