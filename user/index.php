<?php include 'root/head.php' ?>


<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <?php
 include('../koneksi/koneksi.php'); // Pastikan Anda telah memasukkan kode koneksi ke database

 // Mendapatkan informasi pengguna yang sudah login
 $pengguna_id = $_SESSION['user_id']; // Ganti dengan nama variabel sesi Anda
 
 // Query untuk menghitung jumlah pengajuan dengan status "Menunggu" berdasarkan ID pengguna
 $queryMenunggu = "SELECT COUNT(*) AS jumlah_pengajuan
                   FROM Pengajuan_Peminjaman
                   WHERE id_pengguna = '$pengguna_id' AND status = 'Menunggu'";
 $resultMenunggu = $conn->query($queryMenunggu);
 $jumlahMenunggu = 0;
 if ($resultMenunggu->num_rows > 0) {
     $row = $resultMenunggu->fetch_assoc();
     $jumlahMenunggu = $row['jumlah_pengajuan'];
 }
 
 // Query untuk menghitung jumlah pengajuan dengan status "Di Terima" berdasarkan ID pengguna
 $queryTerima = "SELECT COUNT(*) AS jumlah_pengajuan
                 FROM Pengajuan_Peminjaman
                 WHERE id_pengguna = '$pengguna_id' AND status = 'Di Terima'";
 $resultTerima = $conn->query($queryTerima);
 $jumlahTerima = 0;
 if ($resultTerima->num_rows > 0) {
     $row = $resultTerima->fetch_assoc();
     $jumlahTerima = $row['jumlah_pengajuan'];
 }
 
 // Query untuk menghitung jumlah pengajuan dengan status "Di Tolak" berdasarkan ID pengguna
 $queryTolak = "SELECT COUNT(*) AS jumlah_pengajuan
                FROM Pengajuan_Peminjaman
                WHERE id_pengguna = '$pengguna_id' AND status = 'Di Tolak'";
 $resultTolak = $conn->query($queryTolak);
 $jumlahTolak = 0;
 if ($resultTolak->num_rows > 0) {
     $row = $resultTolak->fetch_assoc();
     $jumlahTolak = $row['jumlah_pengajuan'];
 }
 
 $conn->close();
 ?>

            <div class="col-xxl-4 col-md-4">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Pengajuan Menunggu</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-cart"></i>
                            </div>
                            <div class="ps-3">
                                <h6><?php echo $jumlahMenunggu; ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card untuk Jumlah Di Terima -->
            <div class="col-xxl-4 col-md-4">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Pengajuan Di Terima</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="ps-3">
                                <h6><?php echo $jumlahTerima; ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card untuk Jumlah Di Tolak -->
            <div class="col-xxl-4 col-md-4">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Pengajuan Di Tolak</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-x-circle"></i>
                            </div>
                            <div class="ps-3">
                                <h6><?php echo $jumlahTolak; ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card recent-sales overflow-auto">


                    <div class="card-body">
                        <h5 class="card-title"> Riwayat Pengajuan</h5>
                        <?php
 include('../koneksi/koneksi.php'); // Pastikan Anda telah memasukkan kode koneksi ke database

// Mendapatkan informasi pengguna yang sudah login
$pengguna_id = $_SESSION['user_id']; // Ganti dengan nama variabel sesi Anda

// Query untuk mengambil data pengajuan peminjaman berdasarkan ID pengguna
$query = "SELECT B.nama_barang, PP.keterangan, PP.tanggal_pengajuan, PP.jumlah, PP.status
          FROM Pengajuan_Peminjaman PP
          JOIN Barang B ON PP.id_barang = B.id_barang
          WHERE PP.id_pengguna = '$pengguna_id' ORDER BY PP.id_pengajuan DESC";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<table class='table table-borderless datatable'>
            <thead>
                <tr>
                    <th scope='col'>Nama Barang</th>
                    <th scope='col'>Keterangan</th>
                    <th scope='col'>Tanggal</th>
                    <th scope='col'>Jumlah</th>
                    <th scope='col'>Status</th>
                </tr>
            </thead>
            <tbody>";

    while ($row = $result->fetch_assoc()) {
        $status_class = '';
        if ($row['status'] == 'menunggu') {
            $status_class = 'bg-warning';
        } elseif ($row['status'] == 'Di Terima') {
            $status_class = 'bg-success';
        } elseif ($row['status'] == 'Di Tolak') {
            $status_class = 'bg-danger';
        }

        echo "<tr>
                <td>" . $row['nama_barang'] . "</td>
                <td>" . $row['keterangan'] . "</td>
                <td>" . $row['tanggal_pengajuan'] . "</td>
                <td>" . $row['jumlah'] . "</td>
                <td><span class='badge $status_class'>" . $row['status'] . "</span></td>
              </tr>";
    }

    echo "</tbody></table>";
} else {
    echo "Tidak ada data pengajuan peminjaman.";
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