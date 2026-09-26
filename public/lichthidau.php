<?php
require_once("../model/database.php");
require_once("../model/trandau.php");
$td = new TRANDAU();
$danhsach_trandau =$td->laydanhsachtrandau();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch Thi Đấu - Manchester United Tickets</title>
    <!-- Nhúng Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .header-mu { background-color: #da020e; color: white; padding: 20px 0; }
        .match-card { border-radius: 12px; transition: transform 0.2s; }
        .match-card:hover { transform: translateY(-3px); }
        .team-logo { width: 50px; height: 50px; object-fit: contain; }
        .badge-sannha { background-color: #da020e; }
        .badge-sankhach { background-color: #6c757d; }
    </style>
</head>
<body>
    <?php include 'include/navbar.php' ?>

    <!-- Header -->
    <header class="header-mu text-center mb-4 shadow mt-20">
        <div class="container">
            <h1 class="fw-bold">LỊCH THI ĐẤU MANCHESTER UNITED</h1>
            <p class="mb-0">Cập nhật lịch thi đấu mới nhất và đặt vé ngay</p>
        </div>
    </header>

    <!-- Nội dung chính -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                
                <?php if(empty($danhsach_trandau)): ?>
                    <div class="alert alert-info text-center">Hiện chưa có dữ liệu lịch thi đấu. Vui lòng quay lại sau!</div>
                <?php else: ?>
                    
                    <?php foreach($danhsach_trandau as$t): ?>
                        <div class="card match-card mb-3 shadow-sm border-0">
                            <div class="card-body">
                                <div class="row align-items-center text-center">
                                    
                                    <!-- Giải đấu & Ngày giờ -->
                                    <div class="col-md-3 mb-2 mb-md-0 border-end">
                                        <span class="badge bg-secondary mb-1"><?php echo htmlspecialchars($t['tengiaidau']); ?></span>
                                        <div class="fw-bold text-danger">
                                            <?php echo date('H:i - d/m/Y', strtotime($t['thoigian'])); ?>
                                        </div>
                                        <small class="text-muted">
                                            <?php if($t['diadiem'] == 'sannha'): ?>
                                                <span class="badge badge-sannha">Sân nhà (Old Trafford)</span>
                                            <?php else: ?>
                                                <span class="badge badge-sankhach">Sân khách</span>
                                            <?php endif; ?>
                                        </small>
                                    </div>

                                    <!-- Đội nhà vs Đội khách -->
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <div class="d-flex align-items-center justify-content-center gap-3">
                                            <!-- Đội nhà -->
                                            <div class="text-center" style="width: 40%;">
                                                <img src="<?php echo !empty($t['logodoinha']) ?$t['logodoinha'] : 'assets/images/default.png'; ?>" 
                                                     alt="Logo" class="team-logo mb-1"><br>
                                                <span class="fw-bold fs-6"><?php echo htmlspecialchars($t['tendoinha']); ?></span>
                                            </div>

                                            <div class="fw-bold fs-4 text-muted">VS</div>

                                            <!-- Đội khách -->
                                            <div class="text-center" style="width: 40%;">
                                                <img src="<?php echo !empty($t['logodoikhach']) ?$t['logodoikhach'] : 'assets/images/default.png'; ?>" 
                                                     alt="Logo" class="team-logo mb-1"><br>
                                                <span class="fw-bold fs-6"><?php echo htmlspecialchars($t['tendoikhach']); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Trạng thái bán vé & Nút mua -->
                                    <div class="col-md-3">
                                        <?php if($t['trangthaibanve'] == 'dangban'): ?>
                                            <a href="muave.php?id=<?php echo $t['id']; ?>" class="btn btn-danger w-100 fw-bold">MUA VÉ NGAY</a>
                                        <?php elseif($t['trangthaibanve'] == 'hethang'): ?>
                                            <button class="btn btn-secondary w-100" disabled>HẾT VÉ</button>
                                        <?php else: ?>
                                            <button class="btn btn-outline-secondary w-100" disabled>CHƯA MỞ BÁN</button>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php endif; ?>

            </div>
        </div>
    </div>

</body>
</html>