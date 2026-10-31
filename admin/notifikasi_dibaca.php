<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    http_response_code(403);
    exit;
}

/*
|--------------------------------------------------------------------------
| Simpan jumlah notifikasi yang sudah dilihat
|--------------------------------------------------------------------------
*/

if (isset($_POST['total'])) {

    $_SESSION['notifikasi_dibaca'] = (int)$_POST['total'];

    echo json_encode([
        "status" => "success"
    ]);

} else {

    echo json_encode([
        "status" => "error"
    ]);
}

exit;
?>