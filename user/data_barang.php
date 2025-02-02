<?php include 'root/head.php' ?>


<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="col-12">
                <div class="card top-selling overflow-auto">


                    <div class="card-body pb-0">
                        <h5 class="card-title">DATA BARANG</h5>
                        


                     



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