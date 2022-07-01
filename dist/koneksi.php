<?php
// konfigurasi database
$host = "localhost"; 
$user = "root"; 
$password = "";
$database = "fp_dwo_64"; 

$koneksi = mysqli_connect($host, $user, $password, $database);
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
