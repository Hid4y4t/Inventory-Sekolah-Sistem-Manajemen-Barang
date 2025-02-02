<?php include 'root/head.php' ?>


<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="col-12">
                <div class="card recent-sales overflow-auto">


                    <div class="card-body">
                        <h5 class="card-title"> Riwayat Pengajuan Peminjaman</h5>
                        <?php
include('../koneksi/koneksi.php'); // Pastikan Anda telah memasukkan kode koneksi ke database

// Query untuk mengambil semua data pengajuan peminjaman beserta informasi barang dan pengguna
$query = "SELECT U.nama_pengguna, B.nama_barang, PP.keterangan, PP.tanggal_pengajuan, PP.jumlah, PP.status
          FROM Pengajuan_Peminjaman PP
          JOIN Pengguna U ON PP.id_pengguna = U.id_pengguna
          JOIN Barang B ON PP.id_barang = B.id_barang
          ORDER BY PP.id_pengajuan DESC";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<table class='table table-borderless datatable'>
            <thead>
                <tr>
                    <th scope='col'>Nama Pengguna</th>
                    <th scope='col'>Nama Barang</th>
                    <th scope='col'>Keterangan</th>
                    <th scope='col'>Tanggal Pengajuan</th>
                    <th scope='col'>Jumlah</th>
                    <th scope='col'>Status</th>
                </tr>
            </thead>
            <tbody>";

    while ($row = $result->fetch_assoc()) {
        $status_class = '';
        if ($row['status'] == 'Menunggu') {
            $status_class = 'bg-warning';
        } elseif ($row['status'] == 'Di Terima') {
            $status_class = 'bg-success';
        } elseif ($row['status'] == 'Di Tolak') {
            $status_class = 'bg-danger';
        }

        echo "<tr>
                <td>" . $row['nama_pengguna'] . "</td>
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