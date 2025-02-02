<?php
include('../koneksi/koneksi.php'); // Pastikan Anda telah memasukkan kode koneksi ke database

// Periksa apakah parameter "id" telah diset dan memiliki nilai
if (isset($_GET['id'])) {
    $id_barang = $_GET['id'];

    // Buat query DELETE untuk menghapus data barang berdasarkan ID
    $query = "DELETE FROM Barang WHERE id_barang = $id_barang";

    // Eksekusi query DELETE
    if ($conn->query($query) === TRUE) {
        header("Location: data_barang.php");
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }

    // Tutup koneksi ke database
    $conn->close();
} else {
    echo "Parameter 'id' tidak ditemukan.";
}
?>
