<?php
// 1. Kết nối cơ sở dữ liệu mutickets bằng PDO
$host = '127.0.0.1';
$db   = 'mutickets';
$user = 'root'; // Thay bằng user CSDL của bạn (mặc định XAMPP là root)
$pass = '';     // Thay bằng mật khẩu CSDL của bạn (mặc định XAMPP để trống)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}

// 2. Tự động khởi tạo bảng trung gian doibong_giaidau nếu chưa tồn tại
$sqlCreateBridgeTable = "CREATE TABLE IF NOT EXISTS doibong_giaidau (
    doibongid INT NOT NULL,
    giaidauid INT NOT NULL,
    PRIMARY KEY (doibongid, giaidauid),
    FOREIGN KEY (doibongid) REFERENCES doibong(id) ON DELETE CASCADE,
    FOREIGN KEY (giaidauid) REFERENCES giaidau(id) ON DELETE CASCADE
)";
$pdo->exec($sqlCreateBridgeTable);

// 3. Cấu hình gọi API từ football-data.org
// - ID Manchester United là 66
// - Bỏ tham số ?competitions=PL để lấy TOÀN BỘ các giải đấu (Premier League, Cúp FA, C1,...)
$apiUrl = "https://api.football-data.org/v4/teams/66/matches";
$apiToken = "b6e732afc0094c399ccc3aaf4bbe49e5"; // Token API

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $apiUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "X-Auth-Token: " . $apiToken
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    die("Lỗi gọi API: " . $err);
}

$data = json_decode($response, true);

if (!isset($data['matches'])) {
    die("Không có dữ liệu trả về. Vui lòng kiểm tra lại API Token hoặc đường dẫn.");
}

$soTranDaDongBo = 0;

// 4. Chuẩn bị các câu lệnh SQL (Prepared Statements)
$sqlGiaiDau = "INSERT INTO giaidau (apiid, ten) VALUES (:apiid, :ten) 
               ON DUPLICATE KEY UPDATE ten = VALUES(ten)";
$stmtGiaiDau = $pdo->prepare($sqlGiaiDau);

$sqlDoiBong = "INSERT INTO doibong (apiid, ten, logo) VALUES (:apiid, :ten, :logo) 
               ON DUPLICATE KEY UPDATE ten = VALUES(ten), logo = VALUES(logo)";
$stmtDoiBong = $pdo->prepare($sqlDoiBong);

// Thêm câu lệnh lưu liên kết Đội bóng <-> Giải đấu
$sqlDoiBongGiaiDau = "INSERT IGNORE INTO doibong_giaidau (doibongid, giaidauid) VALUES (:doibongid, :giaidauid)";
$stmtDoiBongGiaiDau = $pdo->prepare($sqlDoiBongGiaiDau);

$sqlTranDau = "INSERT INTO trandau (apiid, giaidauid, doinhaid, doikhachid, thoigian, diadiem, trangthaitrandau) 
               VALUES (:apiid, :giaidauid, :doinhaid, :doikhachid, :thoigian, :diadiem, :trangthaitrandau)
               ON DUPLICATE KEY UPDATE 
               thoigian = VALUES(thoigian), trangthaitrandau = VALUES(trangthaitrandau)";
$stmtTranDau = $pdo->prepare($sqlTranDau);

// 5. Xử lý và lưu dữ liệu
foreach ($data['matches'] as $match) {
    try {
        $pdo->beginTransaction();

        // --- A. Xử lý Giải đấu ---
        $stmtGiaiDau->execute([
            ':apiid' => $match['competition']['id'],
            ':ten' => $match['competition']['name']
        ]);
        $giaidauIdQuery = $pdo->prepare("SELECT id FROM giaidau WHERE apiid = ?");
        $giaidauIdQuery->execute([$match['competition']['id']]);
        $giaidauId = $giaidauIdQuery->fetchColumn();

        // --- B. Xử lý Đội nhà ---
        $stmtDoiBong->execute([
            ':apiid' => $match['homeTeam']['id'],
            ':ten' => $match['homeTeam']['name'],
            ':logo' => $match['homeTeam']['crest']
        ]);
        $doiNhaIdQuery = $pdo->prepare("SELECT id FROM doibong WHERE apiid = ?");
        $doiNhaIdQuery->execute([$match['homeTeam']['id']]);
        $doiNhaId = $doiNhaIdQuery->fetchColumn();

        // --- C. Xử lý Đội khách ---
        $stmtDoiBong->execute([
            ':apiid' => $match['awayTeam']['id'],
            ':ten' => $match['awayTeam']['name'],
            ':logo' => $match['awayTeam']['crest']
        ]);
        $doiKhachIdQuery = $pdo->prepare("SELECT id FROM doibong WHERE apiid = ?");
        $doiKhachIdQuery->execute([$match['awayTeam']['id']]);
        $doiKhachId = $doiKhachIdQuery->fetchColumn();

        // --- D. Đồng bộ bảng trung gian DOIBONG_GIAIDAU ---
        // Gán Đội nhà vào Giải đấu
        $stmtDoiBongGiaiDau->execute([
            ':doibongid' => $doiNhaId,
            ':giaidauid' => $giaidauId
        ]);
        // Gán Đội khách vào Giải đấu
        $stmtDoiBongGiaiDau->execute([
            ':doibongid' => $doiKhachId,
            ':giaidauid' => $giaidauId
        ]);

        // --- E. Xử lý Trận đấu ---
        $diadiem = ($match['homeTeam']['id'] == 66) ? 'sannha' : 'sankhach';
        $thoigian = date('Y-m-d H:i:s', strtotime($match['utcDate']));

        $stmtTranDau->execute([
            ':apiid' => $match['id'],
            ':giaidauid' => $giaidauId,
            ':doinhaid' => $doiNhaId,
            ':doikhachid' => $doiKhachId,
            ':thoigian' => $thoigian,
            ':diadiem' => $diadiem,
            ':trangthaitrandau' => $match['status']
        ]);

        $pdo->commit();
        $soTranDaDongBo++;

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Lỗi ở trận đấu ID API " . $match['id'] . ": " . $e->getMessage() . "<br>";
    }
}

echo "<div style='font-family: Arial; padding: 20px; background-color: #e8f5e9; border: 1px solid #4caf50; color: #2e7d32; border-radius: 5px;'>";
echo "<h3 style='margin-0;'>✅ Đồng bộ thành công!</h3>";
echo "<p>Đã lưu / cập nhật <strong>{$soTranDaDongBo}</strong> trận đấu và tự động cập nhật danh sách đối thủ theo từng giải đấu.</p>";
echo "</div>";
?>