<?php include 'root/head.php' ?>


<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="col-12">
                <div class="card top-selling overflow-auto">


                    <div class="card-body pb-0">
                        <h5 class="card-title">DATA BARANG</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#verticalycentered">
                            Tambah Barang
                        </button>


                        <div class="modal fade" id="verticalycentered" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tambah Barang</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" action="penyimpanan_barang.php"
                                            enctype="multipart/form-data">
                                            
                                            <div class="row mb-3">
                                                <label for="nama_barang" class="col-sm-2 col-form-label">Nama
                                                    Barang</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="nama_barang"
                                                        name="nama_barang" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="kategori" class="col-sm-2 col-form-label">Kategori</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="kategori"
                                                        name="kategori" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="jumlah_stok" class="col-sm-2 col-form-label">Jumlah
                                                    Stok</label>
                                                <div class="col-sm-10">
                                                    <input type="number" class="form-control" id="jumlah_stok"
                                                        name="jumlah_stok" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="stok_minimum" class="col-sm-2 col-form-label">Stok
                                                    Minimum</label>
                                                <div class="col-sm-10">
                                                    <input type="number" class="form-control" id="stok_minimum"
                                                        name="stok_minimum" required>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <label for="kondisi" class="col-sm-2 col-form-label">Kondisi</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="kondisi" name="kondisi"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="tanggal_pembelian" class="col-sm-2 col-form-label">Tanggal
                                                    Pembelian</label>
                                                <div class="col-sm-10">
                                                    <input type="date" class="form-control" id="tanggal_pembelian"
                                                        name="tanggal_pembelian" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="foto" class="col-sm-2 col-form-label">Foto</label>
                                                <div class="col-sm-10">
                                                    <input type="file" class="form-control" id="foto" name="foto"
                                                        accept="image/*" required>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                                <button type="reset" class="btn btn-secondary">Reset</button>
                                            </div>
                                        </form>

                                    </div>

                                </div>
                            </div>
                        </div>




                        <?php
include('../koneksi/koneksi.php');  // Pastikan Anda telah memasukkan kode koneksi ke database

// Query untuk mengambil data barang
$query = "SELECT * FROM Barang";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<table class='table table-borderless'>
            <thead>
                <tr>
                    <th scope='col'>Foto</th>
                    <th scope='col'>Nama</th>
                    <th scope='col'>Jumlah</th>
                    <th scope='col'>Kategori</th>
                    <th scope='col'></th>
                </tr>
            </thead>
            <tbody>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <th scope='row'><a href='#'><img src='../assets/foto_produk/" . $row['foto'] . "' alt=''></a></th>
                        <td><a href='#' class='text-primary fw-bold'>" . $row['nama_barang'] . "</a></td>
                        <td>" . $row['jumlah_stok'] . "</td>
                        <td class='fw-bold'>" . $row['kategori'] . "</td>
                        <td>
                        <a href='view_barang.php?id=" . $row['id_barang'] . "' class='btn btn-info btn-sm'>Detail</a>
                            <a href='edit_barang.php?id=" . $row['id_barang'] . "' class='btn btn-primary btn-sm'>Edit</a>
                            <a href='hapus_barang.php?id=" . $row['id_barang'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus barang ini?\")'>Hapus</a>
                        </td>
                      </tr>";
            }
            

    echo "</tbody></table>";
} else {
    echo "Tidak ada data barang.";
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