<?php include 'root/head.php' ?>
<?php include 'root/head.php' ?>

<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <?php
include('../koneksi/koneksi.php');

// Query untuk mengambil data dari tabel barang
$sql_barang = "SELECT COUNT(*) AS jumlah_barang, SUM(jumlah_stok) AS jumlah_stok FROM barang";
$result_barang = $conn->query($sql_barang);
$row_barang = $result_barang->fetch_assoc();

// Mengambil nilai hasil query
$jumlah_barang = $row_barang["jumlah_barang"];
$jumlah_stok = $row_barang["jumlah_stok"];

// Query untuk mengambil jumlah peminjaman dengan status "Di Terima"
$sql_diterima = "SELECT COUNT(*) AS jumlah_diterima FROM pengajuan_peminjaman WHERE status = 'Di Terima'";
$result_diterima = $conn->query($sql_diterima);
$row_diterima = $result_diterima->fetch_assoc();
$jumlah_diterima = $row_diterima["jumlah_diterima"];

// Query untuk mengambil jumlah peminjaman dengan status "Di Tolak"
$sql_ditolak = "SELECT COUNT(*) AS jumlah_ditolak FROM pengajuan_peminjaman WHERE status = 'Di Tolak'";
$result_ditolak = $conn->query($sql_ditolak);
$row_ditolak = $result_ditolak->fetch_assoc();
$jumlah_ditolak = $row_ditolak["jumlah_ditolak"];

// Query untuk mengambil jumlah peminjaman dengan status "Menunggu"
$sql_menunggu = "SELECT COUNT(*) AS jumlah_menunggu FROM pengajuan_peminjaman WHERE status = 'Menunggu'";
$result_menunggu = $conn->query($sql_menunggu);
$row_menunggu = $result_menunggu->fetch_assoc();
$jumlah_menunggu = $row_menunggu["jumlah_menunggu"];

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

            <div class="col-xxl-4 col-md-4">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Peminjaman Di Terima</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="ps-3">
                                <h6><?php echo $jumlah_diterima; ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-4 col-md-4">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Peminjaman Di Tolak</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-x-circle"></i>
                            </div>
                            <div class="ps-3">
                                <h6><?php echo $jumlah_ditolak; ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-4 col-md-4">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Peminjaman Menunggu</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-hourglass"></i>
                            </div>
                            <div class="ps-3">
                                <h6><?php echo $jumlah_menunggu; ?></h6>
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
                                                    <input type="hidden" name="tanggal_peminjaman"
                                                        value="<?php echo $row["tanggal_pengajuan"]; ?>" required>
                                                    Jumlah Yang Peminjaman

                                                    <input type="text" class="form-control" name="jumlah_diambil"
                                                        value="<?php echo $row["jumlah"]; ?>" required>

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
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Daftar Barang Yang Belum Dikembalikan</h5>

                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">Nama Peminjam</th>
                                        <th scope="col">Nama Barang</th>
                                        <th scope="col">Jumlah</th>
                                        <th scope="col">Tanggal Peminjaman</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
        include('../koneksi/koneksi.php');

        // Query untuk mengambil data barang peminjaman yang memiliki kolom tanggal_pengembalian kosong
        $sql = "SELECT p.*, u.nama_pengguna, b.nama_barang, pp.jumlah AS jumlah_pengajuan FROM peminjaman p
                INNER JOIN pengguna u ON p.id_pengguna = u.id_pengguna
                INNER JOIN barang b ON p.id_barang = b.id_barang
                INNER JOIN pengajuan_peminjaman pp ON p.id_pengajuan = pp.id_pengajuan
                WHERE p.tanggal_pengembalian IS NULL";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()):
        ?>
                                    <tr>
                                        <th scope="row"><?php echo $row["nama_pengguna"]; ?></th>
                                        <td><?php echo $row["nama_barang"]; ?></td>
                                        <td><?php echo $row["jumlah_pengajuan"]; ?></td>
                                        <td><?php echo $row["tanggal_peminjaman"]; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#modal_<?php echo $row["id_peminjaman"]; ?>">
                                                Pengembalian
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal untuk setiap pengembalian -->
                                    <div class="modal fade" id="modal_<?php echo $row["id_peminjaman"]; ?>"
                                        tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Pengembalian Barang</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Horizontal Form -->
                                                    <form method="post" action="proses_pengembalian.php">
                                                        <input type="hidden" name="id_peminjaman"
                                                            value="<?php echo $row["id_peminjaman"]; ?>">
                                                        <input type="hidden" name="id_pengajuan"
                                                            value="<?php echo $row["id_pengajuan"]; ?>">
                                                            
                                                        <input type="hidden" name="id_pengguna"
                                                            value="<?php echo $row["id_pengguna"]; ?>">
                                                        <input type="hidden" name="id_barang"
                                                            value="<?php echo $row["id_barang"]; ?>">
                                                            <input type="hidden" name="jumlah_dipinjam"
                                                            value="<?php echo $row["jumlah"]; ?>">

                                                        <div class="mb-3">
                                                            <label for="jumlah_dikembalikan" class="form-label">Jumlah
                                                                Dikembalikan</label>
                                                            <input type="number" class="form-control"
                                                                id="jumlah_dikembalikan" name="jumlah_dikembalikan"
                                                                required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="kondisi_barang" class="form-label">Kondisi
                                                                Barang</label>
                                                            <input type="text" class="form-control" id="kondisi_barang"
                                                                name="kondisi_barang" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="tanggal" class="form-label">Tanggal
                                                                Pengembalian</label>
                                                            <input type="date" class="form-control" id="tanggal"
                                                                name="tanggal" value="<?php echo date('Y-m-d'); ?>"
                                                                required>
                                                        </div>

                                                        <div class="text-center">
                                                            <button type="submit" class="btn btn-primary" name="action"
                                                                value="submit">Submit</button>
                                                            
                                                        </div>
                                                    </form>
                                                    <!-- End Horizontal Form -->
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

        </div>
    </section>
</main>


<?php include 'root/footer.php' ?>