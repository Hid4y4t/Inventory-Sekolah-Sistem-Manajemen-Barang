<?php include 'root/head.php' ?>

<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <?php
include('../koneksi/koneksi.php');

// Pastikan Anda memiliki $id_barang yang diambil dari parameter URL
$id_barang = $_GET['id'];

// Query untuk mengambil data barang berdasarkan ID
$query = "SELECT * FROM Barang WHERE id_barang = $id_barang";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc(); // Mengambil data dari hasil query
?>
            <form action="proses_edit_barang.php" method="POST" enctype="multipart/form-data">

                <!-- Input tersembunyi untuk menyimpan ID barang yang akan diedit -->
                <input type="hidden" name="edit_id" value="<?php echo $row['id_barang']; ?>">

                <div class="row mb-3">
                    <label for="nama_barang" class="col-sm-2 col-form-label">Nama Barang</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nama_barang" name="nama_barang"
                            value="<?php echo $row['nama_barang']; ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="jumlah_stok" class="col-sm-2 col-form-label">Jumlah Stok</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="jumlah_stok" name="jumlah_stok"
                            value="<?php echo $row['jumlah_stok']; ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="kategori" class="col-sm-2 col-form-label">Kategori</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="kategori" name="kategori"
                            value="<?php echo $row['kategori']; ?>">
                    </div>
                </div>

                
                <div class="row mb-3">
                    <label for="stok_minimum" class="col-sm-2 col-form-label">Stok Minimum</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="stok_minimum" name="stok_minimum"
                            value="<?php echo $row['stok_minimum']; ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="kondisi" class="col-sm-2 col-form-label">Kondisi</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="kondisi" name="kondisi"
                            value="<?php echo $row['kondisi']; ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="tanggal_pembelian" class="col-sm-2 col-form-label">Tanggal Pembelian</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="tanggal_pembelian" name="tanggal_pembelian"
                            value="<?php echo $row['tanggal_pembelian']; ?>">
                    </div>
                </div>

                <!-- Input tersembunyi untuk menyimpan nama foto lama -->
                <input type="hidden" name="foto_lama" value="<?php echo $row['foto']; ?>">

                <div class="row mb-3">
                    <label for="foto" class="col-sm-2 col-form-label">Foto</label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control" id="foto" name="foto">
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </form>

            <?php
} else {
    echo "Data barang tidak ditemukan.";
}

$conn->close();
?>


        </div>
    </section>
</main>

<?php include 'root/footer.php' ?>