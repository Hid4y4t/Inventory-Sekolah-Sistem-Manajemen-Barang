<?php
include('../koneksi/koneksi.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_peminjaman = $_POST["id_peminjaman"];
    $id_pengajuan = $_POST["id_pengajuan"];
    $id_pengguna = $_POST["id_pengguna"];
    $id_barang = $_POST["id_barang"];
    $jumlah_dipinjam = $_POST["jumlah_dipinjam"];
    $jumlah_dikembalikan = $_POST["jumlah_dikembalikan"];
    $kondisi_barang = $_POST["kondisi_barang"];
    $tanggal_pengembalian = $_POST["tanggal"];

    // Masukkan data pengembalian ke dalam tabel pengembalian_barang
    $insert_pengembalian_sql = "INSERT INTO barang_pengembalian (id_pengguna, id_barang, jumlah_dipinjam, jumlah_dikembalikan, kondisi_barang, tanggal)
                               VALUES (?, ?, ?, ?, ?, ?)";
    $insert_pengembalian_stmt = $conn->prepare($insert_pengembalian_sql);

    if ($insert_pengembalian_stmt) {
        // Bind parameter ke statement
        $insert_pengembalian_stmt->bind_param("iiisss", $id_pengguna, $id_barang, $jumlah_dipinjam, $jumlah_dikembalikan, $kondisi_barang, $tanggal_pengembalian);

        // Eksekusi pernyataan untuk menyimpan data pengembalian
        $insert_pengembalian_stmt->execute();

        // Update jumlah stok di tabel barang
        $update_barang_sql = "UPDATE barang SET jumlah_stok = jumlah_stok + ? WHERE id_barang = ?";
        $update_barang_stmt = $conn->prepare($update_barang_sql);

        if ($update_barang_stmt) {
            // Bind parameter ke statement
            $update_barang_stmt->bind_param("ii", $jumlah_dikembalikan, $id_barang);

            // Eksekusi pernyataan untuk mengupdate jumlah stok barang
            $update_barang_stmt->execute();

            // Update tanggal_pengembalian di tabel peminjaman
            $update_peminjaman_sql = "UPDATE peminjaman SET tanggal_pengembalian = ? WHERE id_peminjaman = ?";
            $update_peminjaman_stmt = $conn->prepare($update_peminjaman_sql);

            if ($update_peminjaman_stmt) {
                // Bind parameter ke statement
                $update_peminjaman_stmt->bind_param("si", $tanggal_pengembalian, $id_peminjaman);

                // Eksekusi pernyataan untuk mengupdate tanggal_pengembalian di tabel peminjaman
                $update_peminjaman_stmt->execute();

                echo "Data pengembalian berhasil disimpan dan tabel barang diperbarui.";
            } else {
                echo "Gagal menyiapkan statement SQL untuk mengupdate tanggal_pengembalian di tabel peminjaman: " . $conn->error;
            }
        } else {
            echo "Gagal menyiapkan statement SQL untuk mengupdate jumlah stok barang: " . $conn->error;
        }
    } else {
        echo "Gagal menyiapkan statement SQL untuk menyimpan data pengembalian: " . $conn->error;
    }
}

// Tutup koneksi
$conn->close();
?>
