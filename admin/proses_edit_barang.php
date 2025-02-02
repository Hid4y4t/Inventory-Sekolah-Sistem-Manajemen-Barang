<?php
include('../koneksi/koneksi.php');

// Ambil data dari form
$id_barang = $_POST['edit_id'];
$nama_barang = $_POST['nama_barang'];
$jumlah_stok = $_POST['jumlah_stok'];
$kategori = $_POST['kategori'];
$stok_minimum = $_POST['stok_minimum'];
$kondisi = $_POST['kondisi'];
$tanggal_pembelian = $_POST['tanggal_pembelian'];
$foto_lama = $_POST['foto_lama'];

// Handle file foto
if ($_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $foto_tmp = $_FILES['foto']['tmp_name'];
    $foto_name = $_FILES['foto']['name'];
    
    // Pindahkan foto baru ke direktori yang diinginkan
    $target_dir = "direktori_target/";
    $target_path = $target_dir . $foto_name;

    move_uploaded_file($foto_tmp, $target_path);

    // Hapus foto lama jika foto baru diunggah
    if ($foto_lama !== "nama_default_foto.jpg") {
        unlink("../assets/foto_produk/" . $foto_lama);
    }
} else {
    // Jika foto tidak diubah, gunakan foto lama
    $foto_name = $foto_lama;
}

// Query SQL untuk melakukan update data barang
$sql = "UPDATE barang SET
        nama_barang = '$nama_barang',
        jumlah_stok = '$jumlah_stok',
        kategori = '$kategori',
        stok_minimum = '$stok_minimum',
        kondisi = '$kondisi',
        tanggal_pembelian = '$tanggal_pembelian',
        foto = '$foto_name'
        WHERE id_barang = '$id_barang'";

if ($conn->query($sql) === TRUE) {
    header("Location: data_barang.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Tutup koneksi database
$conn->close();
?>