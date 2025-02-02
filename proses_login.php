<?php
session_start(); // Mulai sesi

include('koneksi/koneksi.php'); // Mengimpor file koneksi

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM pengguna WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            $_SESSION["user_id"] = $row["id_pengguna"];
            
            $_SESSION["nama_pengguna"] = $row["nama_pengguna"];

            $_SESSION["username"] = $row["username"];
            $_SESSION["jabatan"] = $row["jabatan"];

            if ($row["jabatan"] == "user") {
                header("Location: user/index.php");
            } elseif ($row["jabatan"] == "admin") {
                header("Location: admin/index.php");
            }
            exit();
        } else {
            $_SESSION["login_error"] = "Password salah"; // Menyimpan pesan error dalam session
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION["login_error"] = "Username tidak ditemukan"; // Menyimpan pesan error dalam session
        header("Location: login.php");
        exit();
    }
}
?>
