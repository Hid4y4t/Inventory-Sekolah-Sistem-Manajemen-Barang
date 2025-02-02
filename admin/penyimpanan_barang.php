<?php
include('../koneksi/koneksi.php'); 


// Ambil data dari form
$nama_barang = $_POST['nama_barang'];
$kategori = $_POST['kategori'];
$jumlah_stok = $_POST['jumlah_stok'];
$stok_minimum = $_POST['stok_minimum'];
$kondisi = $_POST['kondisi'];
$tanggal_pembelian = $_POST['tanggal_pembelian'];

// Handle file foto
if ($_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $foto_tmp = $_FILES['foto']['tmp_name'];
    $foto_name = $_FILES['foto']['name'];

    // Pindahkan foto baru ke direktori yang diinginkan
    $target_dir = "../assets/foto_produk/";
    $target_path = $target_dir . $foto_name;

    move_uploaded_file($foto_tmp, $target_path);
} else {
    echo "Error saat mengunggah foto.";
    exit;
}

// Query SQL untuk menyimpan data barang
$sql = "INSERT INTO barang (nama_barang, kategori, jumlah_stok, stok_minimum, kondisi, tanggal_pembelian, foto) 
        VALUES ('$nama_barang', '$kategori', '$jumlah_stok', '$stok_minimum', '$kondisi', '$tanggal_pembelian', '$foto_name')";

if ($conn->query($sql) === TRUE) {
    echo "Data barang berhasil disimpan.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Tutup koneksi database
$conn->close();
?>

