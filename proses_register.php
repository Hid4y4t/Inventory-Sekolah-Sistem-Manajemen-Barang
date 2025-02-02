<?php
require_once 'koneksi/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $jabatan = $_POST["jabatan"];
    $email = $_POST["email"];
    $kontak = $_POST["kontak"];

    // Enkripsi password menggunakan bcrypt
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO pengguna (nama_pengguna, username, password, jabatan, email, kontak) VALUES ('$name', '$username', '$hashedPassword', '$jabatan', '$email', '$kontak')";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php"); // Mengarahkan ke halaman index.php
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
