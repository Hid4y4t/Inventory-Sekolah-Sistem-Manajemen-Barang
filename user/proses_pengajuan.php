<?php
session_start();
include('../koneksi/koneksi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pengguna =$_SESSION["user_id"];
    $id_barang_array = $_POST['id_barang'];
    $jumlah_array = $_POST['jumlah'];
    $tanggal_pengajuan = $_POST['tanggal_pengajuan'];
    $keterangan = $_POST['keterangan'];
    $kode_transaksi = $_POST['kode_transaksi'];

    // Inisialisasi flag untuk menentukan apakah pengajuan berhasil atau tidak
    $pengajuan_berhasil = true;

    // Looping untuk memproses setiap barang yang dipilih
    for ($i = 0; $i < count($id_barang_array); $i++) {
        $id_barang = $id_barang_array[$i];
        $jumlah = $jumlah_array[$i];

        // Proses penyimpanan data ke tabel pengajuan_peminjaman
        // Sesuaikan dengan struktur dan logika aplikasi Anda
        $insert_query = "INSERT INTO pengajuan_peminjaman (id_barang, id_pengguna, jumlah, tanggal_pengajuan, keterangan, kode_transaksi, status) VALUES ('$id_barang', ' $id_pengguna', '$jumlah', '$tanggal_pengajuan', '$keterangan', '$kode_transaksi', 'Menunggu')";
        
        if ($conn->query($insert_query) !== TRUE) {
            // Jika terdapat kesalahan saat menyimpan salah satu barang, set flag menjadi false
            $pengajuan_berhasil = false;
            break; // Keluar dari loop
        }
    }

    // Tutup koneksi database
    $conn->close();

    // Setelah loop selesai, cek apakah pengajuan berhasil atau tidak
    if ($pengajuan_berhasil) {
        // Jika berhasil, alihkan ke halaman peminjaman dan tampilkan notifikasi
        header("Location: peminjaman.php?success=1");
        exit();
    } else {
        // Jika terdapat kesalahan, alihkan ke halaman pengajuan dengan notifikasi kesalahan
        header("Location: form_pengajuan.php?error=1");
        exit();
    }
} else {
    // Jika akses langsung ke halaman ini tanpa melalui formulir, alihkan ke halaman pengajuan
    header("Location: form_pengajuan.php");
    exit();
}
?>
