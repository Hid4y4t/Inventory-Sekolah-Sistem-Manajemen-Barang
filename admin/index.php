<?php include 'root/head.php' ?>

<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <?php
                include('../koneksi/koneksi.php');

                // Query untuk mengambil data dari tabel barang
                $sql = "SELECT COUNT(*) AS jumlah_barang, SUM(jumlah_stok) AS jumlah_stok FROM barang";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();

                // Mengambil nilai hasil query
                $jumlah_barang = $row["jumlah_barang"];
                $jumlah_stok = $row["jumlah_stok"];

                // Tutup koneksi
                $conn->close();
                ?>

            <div class="col-xxl-4 col-md-4">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Barang</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-cart"></i>
                            </div>
                            <div class="ps-3">
                                <h6><?php echo $jumlah_barang; ?></h6>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-4 col-md-4">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Item</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-cart"></i>
                            </div>
                            <div class="ps-3">
                                <h6><?php echo $jumlah_stok; ?></h6>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Table Pengajuan</h5>

                        <?php
include('../koneksi/koneksi.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"];
    $id_pengajuan = $_POST["id_pengajuan"];
    $id_pengguna = $_POST["id_pengguna"];
    $id_barang = $_POST["id_barang"];
    $tanggal_peminjaman = $_POST["tanggal_peminjaman"];
    $jumlah_diambil = $_POST["jumlah_diambil"];

    if ($action === "di_terima") {
        // Pastikan $jumlah_diambil adalah integer yang valid
        $jumlah_diambil = intval($_POST["jumlah_diambil"]);
    
        // Menggunakan prepared statement untuk menghindari SQL Injection
        $update_sql = "UPDATE pengajuan_peminjaman SET status = ?, jumlah = ? WHERE id_pengajuan = ?";
        $update_barang_sql = "UPDATE barang SET jumlah_stok = jumlah_stok - ? WHERE id_barang = ?";
    
        // Persiapkan statement SQL
        $update_stmt = $conn->prepare($update_sql);
        $update_barang_stmt = $conn->prepare($update_barang_sql);
    
        if ($update_stmt && $update_barang_stmt) {
            // Bind parameter ke statement
            $status_terima = 'Di Terima';
            $update_stmt->bind_param("sii", $status_terima, $jumlah_diambil, $id_pengajuan);
            $update_barang_stmt->bind_param("ii", $jumlah_diambil, $id_barang);
    
            // Update status dan jumlah di tabel pengajuan_peminjaman
            $update_stmt->execute();
    
            // Update jumlah di tabel barang
            $update_barang_stmt->execute();

            // Jika update berhasil, tambahkan data ke tabel peminjaman
            $insert_peminjaman_sql = "INSERT INTO peminjaman (id_pengajuan, id_pengguna, id_barang, tanggal_peminjaman, jumlah) VALUES (?, ?, ?, ?, ?)";
            $insert_peminjaman_stmt = $conn->prepare($insert_peminjaman_sql);

            if ($insert_peminjaman_stmt) {
                // Bind parameter ke statement
                $insert_peminjaman_stmt->bind_param("iiisi", $id_pengajuan, $id_pengguna, $id_barang, $tanggal_peminjaman, $jumlah_diambil);

                // Eksekusi pernyataan untuk menyimpan data peminjaman
                $insert_peminjaman_stmt->execute();

                echo "Permintaan peminjaman diterima dan data peminjaman berhasil disimpan.";
            } else {
                echo "Gagal menyiapkan statement SQL untuk menyimpan data peminjaman: " . $conn->error;
            }
        } else {
            echo "Gagal menyiapkan statement SQL: " . $conn->error;
        }
    } elseif ($action === "di_tolak") {
        // Menggunakan prepared statement untuk menghindari SQL Injection
        $update_sql = "UPDATE pengajuan_peminjaman SET status = 'Di Tolak' WHERE id_pengajuan = ?";
    
        // Persiapkan statement SQL
        $update_stmt = $conn->prepare($update_sql);
    
        if ($update_stmt) {
            // Bind parameter ke statement
            $update_stmt->bind_param("i", $id_pengajuan);
    
            // Update status di tabel pengajuan_peminjaman
            $update_stmt->execute();
    
            echo "Permintaan peminjaman telah ditolak.";
        } else {
            echo "Gagal menyiapkan statement SQL: " . $conn->error;
        }
    }
}

// Query untuk mengambil data pengajuan peminjaman yang statusnya "menunggu"
$sql = "SELECT pp.*, u.nama_pengguna, b.nama_barang, b.jumlah_stok FROM pengajuan_peminjaman pp
        INNER JOIN pengguna u ON pp.id_pengguna = u.id_pengguna
        INNER JOIN barang b ON pp.id_barang = b.id_barang
        WHERE pp.status = 'menunggu'";
$result = $conn->query($sql);

// Tutup koneksi
$conn->close();
?>



                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Barang</th>
                                    <th scope="col">Jumlah Pengajuan</th>
                                    <th scope="col">Jumlah Saat Ini</th>
                                    <th scope="col">Tanggal Pengajuan</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <th scope="row"><?php echo $row["nama_pengguna"]; ?></th>
                                    <td><?php echo $row["nama_barang"]; ?></td>
                                    <td><?php echo $row["jumlah"]; ?></td>
                                    <td><?php echo $row["jumlah_stok"]; ?></td>
                                    <td><?php echo $row["tanggal_pengajuan"]; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#modal_<?php echo $row["id_pengajuan"]; ?>">
                                            Lihat
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal untuk setiap pengajuan -->
                                <div class="modal fade" id="modal_<?php echo $row["id_pengajuan"]; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail Pengajuan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Nama Peminjam: <?php echo $row["nama_pengguna"]; ?></p>
                                                <p>Nama Barang: <?php echo $row["nama_barang"]; ?></p>
                                                <p>Tanggal Peminjaman: <?php echo $row["tanggal_pengajuan"]; ?></p>
                                                <p>Jumlah Pengajuan: <?php echo $row["jumlah"]; ?></p>
                                                <p>Jumlah Saat Ini: <?php echo $row["jumlah_stok"]; ?></p>

                                                <form method="post" action="">
                                                    <input type="hidden" name="id_pengajuan"
                                                        value="<?php echo $row["id_pengajuan"]; ?>">
                                                    <input type="hidden" name="id_pengguna"
                                                        value="<?php echo $row["id_pengguna"]; ?>">
                                                    <input type="hidden" name="id_barang"
                                                        value="<?php echo $row["id_barang"]; ?>">
                                                        <input type="hidden" name="jumlah"
                                                        value="<?php echo $row["jumlah"]; ?>">
                                                    <input type="hidden" name="tanggal_peminjaman"
                                                        value="<?php echo $row["tanggal_pengajuan"]; ?>" required>
                                                        Jumlah Yang Peminjaman
                                                      
                                                        <input type="text" class="form-control" name="jumlah_diambil" value="<?php echo $row["jumlah"]; ?>"required>

                                                    <br>
                                                    <button type="submit" class="btn btn-success" name="action"
                                                        value="di_terima">
                                                        <i class="bi bi-check-circle"></i> Di Terima
                                                    </button>
                                                    <button type="submit" class="btn btn-danger" name="action"
                                                        value="di_tolak">
                                                        <i class="bi bi-exclamation-octagon"></i> Di Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            </tbody>
                        </table>



                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>

        </div>
    </section>
</main>

<?php include 'root/footer.php' ?>