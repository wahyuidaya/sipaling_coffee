<?php
// Mengatur header untuk mengirimkan data dalam format JSON
header('Content-Type: application/json');

$servername = trim($_ENV['DB_HOST']     ?? getenv('DB_HOST')     ?: 'localhost');
$username   = trim($_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME') ?: 'root');
$password   = trim($_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?: '');
$dbname     = trim($_ENV['DB_DATABASE'] ?? getenv('DB_DATABASE') ?: 'sipaling_coffee');
$useSSL     = filter_var(trim($_ENV['DB_SSL'] ?? getenv('DB_SSL') ?: 'false'), FILTER_VALIDATE_BOOLEAN);

$conn = new mysqli();

if ($useSSL) {
    $conn->ssl_set(NULL, NULL, NULL, NULL, NULL);
    $conn->real_connect($servername, $username, $password, $dbname, 4000, NULL, MYSQLI_CLIENT_SSL);
} else {
    $conn->real_connect($servername, $username, $password, $dbname);
}

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Koneksi gagal: ' . $conn->connect_error]);
    exit();
}

$conn->set_charset("utf8mb4");

$nama  = $_POST['nama']  ?? '';
$email = $_POST['email'] ?? '';
$pesan = $_POST['pesan'] ?? '';

if (empty($nama) || empty($email) || empty($pesan)) {
    echo json_encode(['status' => 'error', 'message' => 'Semua field wajib diisi.']);
    exit();
}

$stmt = $conn->prepare("INSERT INTO kontak (nama, email, pesan) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nama, $email, $pesan);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Pesan Anda berhasil terkirim.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
