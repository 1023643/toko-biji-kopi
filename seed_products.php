<?php
include 'koneksi.php';

// Find Seller ID
$query = mysqli_query($koneksi, "SELECT id FROM users WHERE role='seller' LIMIT 1");
if (mysqli_num_rows($query) == 0) {
    die("Error: Tidak ada user Seller ditemukan. Silahkan daftar atau jalankan setup_db.php dulu.");
}
$seller = mysqli_fetch_assoc($query);
$seller_id = $seller['id'];

// DELETE OLD DATA
mysqli_query($koneksi, "DELETE FROM products WHERE seller_id='$seller_id'");
mysqli_query($koneksi, "ALTER TABLE products AUTO_INCREMENT = 1");

echo "🗑️ Data lama dihapus...<br>";

// REAL IMAGES (Sourced from Unsplash matching specific vibes)
$products = [
    [
        "nama" => "Arabica Gayo",
        "asal" => "Aceh (Dataran Tinggi Gayo)",
        "roast" => "Medium",
        "acid" => "Low",
        "harga" => 85000,
        "img" => "https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&q=80" // Authentic burlap sack & beans
    ],
    [
        "nama" => "Toraja Sapan",
        "asal" => "Tana Toraja, Sulawesi",
        "roast" => "Dark",
        "acid" => "Low",
        "harga" => 95000,
        "img" => "https://images.unsplash.com/photo-1524350876685-274059332603?auto=format&fit=crop&q=80" // Hands holding fresh red cherries (Petani vibe)
    ],
    [
        "nama" => "Bali Kintamani",
        "asal" => "Kintamani, Bali",
        "roast" => "Light",
        "acid" => "High",
        "harga" => 88000,
        "img" => "https://images.unsplash.com/photo-1511537632536-34a7029537eb?auto=format&fit=crop&q=80" // Coffee farm greenery in mist
    ],
    [
        "nama" => "Flores Bajawa",
        "asal" => "Bajawa, Flores",
        "roast" => "Medium",
        "acid" => "Medium",
        "harga" => 92000,
        "img" => "https://images.unsplash.com/photo-1587049352846-4a222e784d38?auto=format&fit=crop&q=80" // Raw Green Beans drying
    ],
    [
        "nama" => "Java Preanger",
        "asal" => "Pangalengan, Jawa Barat",
        "roast" => "Medium",
        "acid" => "Medium",
        "harga" => 75000,
        "img" => "https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&q=80" // Classic cup and roasted beans
    ],
    [
        "nama" => "Papua Wamena",
        "asal" => "Lembah Baliem, Papua",
        "roast" => "Dark",
        "acid" => "Low",
        "harga" => 120000,
        "img" => "https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&q=80" // Dark Roasted Beans Pile
    ],
    [
        "nama" => "Lintong Sumatra",
        "asal" => "Danau Toba",
        "roast" => "Medium",
        "acid" => "High",
        "harga" => 90000,
        "img" => "https://images.unsplash.com/photo-1610632380989-680fe40816c6?auto=format&fit=crop&q=80" // Close up textured roasted beans
    ],
    [
        "nama" => "Sidikalang",
        "asal" => "Dairi, Sumatera Utara",
        "roast" => "Dark",
        "acid" => "Low",
        "harga" => 80000,
        "img" => "https://images.unsplash.com/photo-1559525839-8f897d5a083f?auto=format&fit=crop&q=80" // Scoop of beans
    ],
    [
        "nama" => "Robusta Lampung",
        "asal" => "Lampung",
        "roast" => "Dark",
        "acid" => "Low",
        "harga" => 55000,
        "img" => "https://images.unsplash.com/photo-1611854779393-1b2ae9d4cb57?auto=format&fit=crop&q=80" // Pouring beans
    ],
    [
        "nama" => "Liberika Riau",
        "asal" => "Riau",
        "roast" => "Medium",
        "acid" => "Low",
        "harga" => 100000,
        "img" => "https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&q=80" // Texture shot
    ]
];

foreach ($products as $p) {
    $nama = $p['nama'];
    $asal = $p['asal'];
    $roast = $p['roast'];
    $acid = $p['acid'];
    $harga = $p['harga'];
    $img = $p['img'];
    
    $sql = "INSERT INTO products (seller_id, nama_kopi, daerah_asal, roast_level, acidity_level, harga, stok, gambar) 
            VALUES ('$seller_id', '$nama', '$asal', '$roast', '$acid', '$harga', 50, '$img')";
    
    if(mysqli_query($koneksi, $sql)) {
        echo "✅ Berhasil tambah: $nama <br>";
    } else {
        echo "❌ Gagal: " . mysqli_error($koneksi) . "<br>";
    }
}

echo "<hr>Selesai! Gambar kopi sudah diperbarui dengan yang lebih autentik (Petani, Green Beans, Roast).";
?>

