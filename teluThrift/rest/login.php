<?php
header('Content-Type: application/json');
session_start();
require_once "../config/koneksi.php";

$method = $_SERVER['REQUEST_METHOD'];

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);
if (!is_array($input)) {
    $input = $_POST;
}

$response = ['status' => 'error', 'message' => 'Aksi tidak valid'];

if ($method === 'POST') {
    $username = mysqli_real_escape_string($conn, trim($input['username'] ?? ''));
    $password = trim($input['password'] ?? '');

    if (empty($username) || empty($password)) {
        $response['message'] = 'Username dan password wajib diisi';
    } else {
        $sql = "SELECT id, username, password, nama, role FROM users WHERE username='$username'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama'] = $user['nama'];
                
                $response = [
                    'status' => 'success',
                    'message' => 'Login berhasil',
                    'data' => [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'nama' => $user['nama'],
                        'role' => $user['role']
                    ]
                ];
            } else {
                $response['message'] = 'Password salah';
            }
        } else {
            $response['message'] = 'Username tidak ditemukan';
        }
    }
}

echo json_encode($response);
?>
