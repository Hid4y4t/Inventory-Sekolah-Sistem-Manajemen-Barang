<?php include 'root/head.php' ?>


<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="col-12">
                <div class="card recent-sales overflow-auto">



                    <div class="card-body">
                        <h5 class="card-title">Riwayat Pengembalian</h5>

                        <table class="table table-borderless datatable">
                            <thead>
                                <tr>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Barang</th>
                                    <th scope="col">Jumlah Di Pinjam</th>
                                    <th scope="col">Jumlah Di Terima</th>
                                    <th scope="col">Kondisi</th>
                                    <th scope="col">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
        include('../koneksi/koneksi.php'); // Pastikan Anda telah memasukkan kode koneksi ke database

        $query = "SELECT BP.id, P.nama_pengguna, B.nama_barang, BP.jumlah_dipinjam, BP.jumlah_dikembalikan, BP.kondisi_barang, BP.tanggal
          FROM barang_pengembalian BP
          JOIN pengguna P ON BP.id_pengguna = P.id_pengguna
          JOIN barang B ON BP.id_barang = B.id_barang";

                  
        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
            $jumlah_pinjam = $row['jumlah_dipinjam'];
            $jumlah_terima = $row['jumlah_dikembalikan'];
            $jumlah_class = ($jumlah_pinjam != $jumlah_terima) ? 'bg-danger ' : ''; // Menambah class 'text-white' jika ada perbedaan

            echo "<tr >
                <td>" . $row['nama_pengguna'] . "</td>
                <td>" . $row['nama_barang'] . "</td>
                <td>" . $row['jumlah_dipinjam'] . "</td>
                <td><span class= 'text-black badge $jumlah_class'>" . $row['jumlah_dikembalikan'] . "</span></td>
                <td>" . $row['kondisi_barang'] . "</td>
                <td>" . $row['tanggal'] . "</td>
            </tr>";
        }

        $conn->close();
        ?>
                            </tbody>
                        </table>


                    </div>

                </div>
            </div>
        </div>
    </section>
</main>
<?php include 'root/footer.php' ?>