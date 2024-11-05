<?php
// Total array yang disiapkan untuk disimpan
$todos = []; 
// Melakukan pengecekan apakah file todo.txt ditemukan
if (file_exists('todo.txt')) {
    // Membaca file todo.txt
    $file = file_get_contents('todo.txt');
    // Mengubah format serialize menjadi array
    $todos = unserialize($file);
}

function simpanData($daftar_belanja) {
    file_put_contents('todo.txt', $daftar_belanja);
    header('location:index.php');
    exit; // Tambahkan exit untuk menghentikan eksekusi lebih lanjut
}

// Jika ditemukan todo yang dikirim melalui metode POST
if (isset($_POST['todo'])) {
    $data = $_POST['todo']; // Data yang dipilih pada form
    $key = isset($_POST['key']) ? $_POST['key'] : null; // Menangkap key jika ada

    if ($key !== null && isset($todos[$key])) {
        // Jika ada key, edit item yang sudah ada
        $todos[$key] = [
            'todo' => $data,
            'status' => $todos[$key]['status'] // Pertahankan status
        ];
    } else {
        // Jika tidak ada key, tambahkan item baru
        $todos[] = [
            'todo' => $data,
            'status' => 0
        ];
    }
    $daftar_belanja = serialize($todos);
    simpanData($daftar_belanja);
}

// Jika ditemukan $_GET['status']
if (isset($_GET['status']) && isset($_GET['key'])) {
    // Ubah status
    $todos[$_GET['key']]['status'] = $_GET['status'];
    $daftar_belanja = serialize($todos);
    simpanData($daftar_belanja);
}

// Jika ditemukan $_GET['hapus']
if (isset($_GET['hapus']) && isset($_GET['key'])) {
    unset($todos[$_GET['key']]);
    $daftar_belanja = serialize($todos);
    simpanData($daftar_belanja);
}

// Jika ditemukan $_GET['edit']
$edit_todo = '';
$edit_key = null;
if (isset($_GET['edit']) && isset($_GET['key'])) {
    $edit_key = $_GET['key'];
    $edit_todo = $todos[$edit_key]['todo'];
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h1 class="text-center">Todo App</h1>
<form action="" method="POST">
<div class="form-group">
    <label>Daftar Belanja Hari ini</label></div><br>
    <input type="text" name="todo" value="<?php echo isset($edit_todo) ? htmlspecialchars($edit_todo) : ''; ?>">
    <input type="hidden" name="key" value="<?php echo isset($edit_key) ? htmlspecialchars($edit_key) : ''; ?>">
    <button type="submit">Simpan</button>
</form>
<div class="list-todo">
<ul>
    <?php foreach ($todos as $key => $value): ?>
    <li>
        <input type="checkbox" name="todo" onclick="window.location.href='index.php?status=<?php echo ($value['status'] == 1) ? '0' : '1'; ?>&key=<?php echo $key; ?>';"
        <?php if ($value['status'] == 1) echo 'checked'; ?>>
        <label>
            <?php
            if ($value['status'] == 1) {
                echo '<del>' . htmlspecialchars($value['todo']) . '</del>'; 
            } else {
                echo htmlspecialchars($value['todo']);
            }
            ?>
        </label>
        <a href="index.php?edit=1&key=<?php echo $key; ?>" onclick="return confirm('Anda yakin ingin mengedit?')" class="edit">edit</a>
        <a href="index.php?hapus=1&key=<?php echo $key; ?>" onclick="return confirm('Apakah Anda Yakin akan menghapus data ini?')" class="hapus">hapus</a>
    </li> 
    <?php endforeach; ?>
</ul>
</div>
</div>
</body>
</html>
