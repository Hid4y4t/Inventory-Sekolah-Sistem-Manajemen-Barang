<?php include 'root/head.php' ?>

<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Trakhir Pengajuan</h5>

                        <!-- Table with stripped rows -->
                        <?php
include('../koneksi/koneksi.php'); // Pastikan Anda telah memasukkan kode koneksi ke database

// Mendapatkan informasi pengguna yang sudah login
$pengguna_id = $_SESSION['user_id']; // Ganti dengan nama variabel sesi Anda

// Query untuk mengambil data pengajuan peminjaman berdasarkan kode_transaksi
$query = "SELECT B.nama_barang, PP.jumlah, PP.keterangan, PP.tanggal_pengajuan
          FROM Pengajuan_Peminjaman PP
          JOIN Barang B ON PP.id_barang = B.id_barang
          WHERE PP.id_pengguna = '$pengguna_id'
          ORDER BY PP.kode_transaksi DESC";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<table class='table'>
            <thead>
              <tr>
                <th scope='col'>Nama Barang</th>
                <th scope='col'>Jumlah</th>
                <th scope='col'>Keterangan</th>
                <th scope='col'>Tanggal</th>
              </tr>
            </thead>
            <tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row['nama_barang'] . "</td>
                <td>" . $row['jumlah'] . "</td>
                <td>" . $row['keterangan'] . "</td>
                <td>" . $row['tanggal_pengajuan'] . "</td>
              </tr>";
    }

    echo "</tbody></table>";
} else {
    echo "Tidak ada data pengajuan peminjaman.";
}

$conn->close();
?>



                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>

            <?php
function generateKodeTransaksi() {
    include('../koneksi/koneksi.php');

    // Ambil kode transaksi terakhir dari database
    $query = "SELECT MAX(kode_transaksi) AS max_kode FROM pengajuan_peminjaman";
    $result = $conn->query($query);

    $max_kode = $result->fetch_assoc()['max_kode'];

    // Ambil angka dari kode transaksi terakhir
    $last_number = (int) substr($max_kode, 3);

    // Increment angka
    $new_number = $last_number + 1;

    // Format ulang angka ke dalam format tiga digit dengan leading zeros
    $new_number_formatted = str_pad($new_number, 3, '0', STR_PAD_LEFT);

    // Gabungkan format dan angka
    $new_kode_transaksi = "KDT" . $new_number_formatted;

    // Tutup koneksi database
    $conn->close();

    return $new_kode_transaksi;
}
?>

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Buat Pinjaman</h5>

                        <!-- General Form Elements -->
                        <form action="proses_pengajuan.php" method="post">
                            <div class="row mb-3">
                                <label for="inputKodeTransaksi" class="col-sm-2 col-form-label">Kode Transaksi</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="kode_transaksi"
                                        value="<?php echo generateKodeTransaksi(); ?>" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
        <label class="col-sm-2 col-form-label">Barang</label>
        <div class="col-sm-10">
        <select class="form-select" name="id_barang[]" id="id_barang" aria-label="Barang" multiple>
                    <option selected>Pilih barang</option>
                    <?php
                    include('../koneksi/koneksi.php');

                    // Query untuk mengambil data barang
                    $barang_query = "SELECT id_barang, nama_barang FROM Barang";
                    $result = $conn->query($barang_query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id_barang'] . "'>" . $row['nama_barang'] . "</option>";
                        }
                    }

                    $conn->close();
                    ?>
                </select>
        </div>
    </div>
    <div class="row mb-3" id="jumlahContainer" style="display: none;">
            <label for="inputJumlah" class="col-sm-2 col-form-label">Jumlah Barang</label>
            <div class="col-sm-10" id="jumlahInputs">
                <!-- Script JavaScript akan menambahkan input jumlah di sini -->
            </div>
        </div>
                            <div class="row mb-3">
                                <label for="inputTanggal" class="col-sm-2 col-form-label">Tanggal</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" name="tanggal_pengajuan">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputKeterangan" class="col-sm-2 col-form-label">Keterangan</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" style="height: 100px" name="keterangan"></textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Kirim</button>
                                </div>
                            </div>
                        </form>
                        <!-- End General Form Elements -->

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<script>
        // Skrip JavaScript Anda yang menggunakan jQuery
        $(document).ready(function() {
            // Tangkap elemen dropdown barang
            var idBarangSelect = $("#id_barang");

            // Tangkap elemen container jumlah
            var jumlahContainer = $("#jumlahContainer");
            var jumlahInputsContainer = $("#jumlahInputs");

            // Tambahkan event listener ketika dropdown barang berubah
            idBarangSelect.change(function() {
                // Cek apakah dropdown barang memiliki nilai yang dipilih
                if (idBarangSelect.val().length > 0) {
                    // Kosongkan container jumlah
                    jumlahInputsContainer.html("");

                    // Loop untuk setiap barang yang dipilih
                    $.each(idBarangSelect.val(), function(index, selectedBarangId) {
                        // Dapatkan nama barang berdasarkan ID
                        var selectedBarang = idBarangSelect.find("option[value='" + selectedBarangId + "']").text();

                        // Tambahkan input jumlah untuk setiap barang
                        var inputJumlah = $("<input>", {
                            type: "text",
                            class: "form-control",
                            name: "jumlah[]",
                            placeholder: "Masukkan jumlah untuk " + selectedBarang
                        });

                        // Tambahkan input jumlah ke dalam container jumlah
                        jumlahInputsContainer.append(inputJumlah);
                    });

                    // Tampilkan container jumlah
                    jumlahContainer.show();
                } else {
                    // Sembunyikan container jumlah jika tidak ada barang yang dipilih
                    jumlahContainer.hide();
                }
            });
        });
    </script>
<?php include 'root/footer.php' ?>