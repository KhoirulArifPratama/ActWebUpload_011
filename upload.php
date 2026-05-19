<?php
$target_dir = "uploads/";

// 1. FITUR LOGIKA DELETE (Ditaruh paling atas)
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['file'])) {
    $namaFileHapus = basename($_GET['file']);
    $pathHapus = $target_dir . $namaFileHapus;

    echo "<h2>Hasil Delete</h2>";
    if (file_exists($pathHapus)) {
        if (unlink($pathHapus)) {
            echo "Berkas <b>" . htmlspecialchars($namaFileHapus) . "</b> berhasil didelete.<br><br>";
        } else {
            echo "Gagal mendelete berkas.<br><br>";
        }
    } else {
        echo "Berkas tidak ditemukan atau sudah dihapus.<br><br>";
    }
    echo "<a href='index.html'>Kembali ke Menu Utama</a>";
    exit; // Stop proses biar gak lanjut ke upload
}


// 2. LOGIKA UPLOAD ASLI KAMU
if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); } // Buat folder jika belum ada

$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Periksa apakah berkas sudah ada
if (file_exists($target_file)) {
    echo "Maaf, berkas sudah ada.<br>";
    $uploadOk = 0;
}

// Periksa ukuran berkas
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Maaf, berkas Anda terlalu besar.<br>";
    $uploadOk = 0;
}

// Hanya izinkan format berkas tertentu
if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "gif" ) {
    echo "Maaf, hanya berkas JPG, JPEG, PNG & GIF yang diperbolehkan.<br>";
    $uploadOk = 0;
}

// Periksa apakah $uploadOk bernilai 0 karena kesalahan
if ($uploadOk == 0) {
    echo "Maaf, berkas Anda tidak dapat diunggah.<br><br>";
    echo "<a href='index.html'>Kembali</a>";
} else {
    // Jika semua oke, coba unggah berkas
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $namaFile = htmlspecialchars(basename($_FILES["fileToUpload"]["name"]));
        
        // Menampilkan Hasil Upload & Gambar
        echo "<h2>Hasil Upload</h2>";
        echo "Berkas <b>". $namaFile . "</b> telah diunggah.<br><br>";
        echo "<img src='" . $target_file . "' style='max-width: 200px; border: 1px solid #ccc;'><br><br>";
        
        // TOMBOL HASIL UNDUH (Download)
        echo "<a href='" . $target_file . "' download><button>Unduh Gambar</button></a> ";
        
        // TOMBOL HASIL DELETE (Memicu fungsi delete di atas)
        echo "<a href='upload.php?action=delete&file=" . urlencode($namaFile) . "'><button>Delete Gambar</button></a><br><br>";
        
        echo "<a href='index.html'>Kembali ke Form</a>";
    } else {
        echo "Maaf, terjadi kesalahan saat mengunggah berkas Anda.<br><br>";
        echo "<a href='index.html'>Kembali</a>";
    }
}
?>