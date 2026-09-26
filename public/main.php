<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manchester United Ticket</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <?php include 'include/navbar.php' ?>

    <!-- Carousel -->
    <div id="demo" class="carousel slide" data-bs-ride="carousel">

        <!-- Indicators/dots -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
        </div>

        <!-- The slideshow/carousel -->
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="../assets/images/banners/banner1.jpg" alt="Los Angeles" class="d-block w-100">
            </div>
            <div class="carousel-item">
                <img src="../assets/images/banners/banner2.jpg" alt="Chicago" class="d-block w-100">
            </div>
            <div class="carousel-item">
                <img src="../assets/images/banners/banner3.jpg" alt="New York" class="d-block w-100">
            </div>
        </div>

        <!-- Left and right controls/icons -->
        <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <div class="container mt-3">
        <div class="row">
            <div class="col-md-3 bg-light p-3 rounded shadow-sm border">
                <h5 class="fw-bold mb-3 text-danger border-bottom pb-2">
                    <i class="bi bi-funnel-fill"></i> TÌM TRẬN ĐẤU
                </h5>

                <form action="index.php" method="GET" id="filterForm">
                    <div class="mb-3">
                        <label for="giaidauSelect" class="form-label fw-bold text-secondary">Theo giải đấu</label>
                        <select class="form-select" id="giaidauSelect" name="giaidauid">
                            <option value="">-- Tất cả giải đấu --</option>
                            <?php foreach ($giaidau as $gd): ?>
                                <option value="<?php echo $gd["id"]; ?>">
                                    <?php echo $gd["ten"]; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted d-block mt-1"><i>(Chọn giải để xem danh sách đội)</i></small>
                    </div>

                    <!-- Chọn Đối thủ (Mặc định hiển thị $dsdoibong) -->
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Chọn đối thủ trong giải</label>
                        <div id="teamsContainer" class="border rounded p-2 bg-white" style="max-height: 200px; overflow-y: auto;">

                            <div class="text-center p-2 d-none" id="loadingNotice">
                                <div class="spinner-border spinner-border-sm text-danger" role="status"></div>
                                <span class="small text-muted ms-2">Đang tải danh sách...</span>
                            </div>

                            <div class="team-list-content" id="teamListContent">
                                <?php if (!empty($dsdoibong)): ?>
                                    <?php foreach ($dsdoibong as $db): ?>
                                        <?php
                                        if (strpos(strtolower($db["ten"]), "manchester united") !== false) continue;
                                        ?>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="teams[]" value="<?php echo $db["id"]; ?>" id="team_<?php echo $db["id"]; ?>">
                                            <label class="form-check-label d-flex align-items-center gap-2" for="team_<?php echo $db["id"]; ?>">
                                                <?php if (!empty($db["logo"])): ?>
                                                    <img src="<?php echo $db["logo"]; ?>" width="20" height="20" alt="logo">
                                                <?php endif; ?>
                                                <span><?php echo $db["ten"]; ?></span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-muted small p-2 text-center">Chưa có dữ liệu đội bóng.</div>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>

                    <hr class="my-3">



                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-danger fw-bold">TÌM KIẾM</button>
                        <a href="index.php?action=lichthidau" class="btn btn-outline-secondary btn-sm text-center">Tải lại</a>
                    </div>
                </form>
            </div>



            <!-- nd chính (phát triển sau) -->
            <div class="col-md-6 bg-white">
                hiển thị các trận đấu trong 1 hoặc 2 tuần tới, khi người dùng bấm tìm kiếm bộ lọc sẽ hiển thị trận đấu của mu với clb đã chọn
            </div>

            <!-- bxh (phát triển sau) -->
            <div class="col-md-3 bg-light">
                bảng xếp hạng
            </div>
        </div>
    </div>


    <script src="../assets/js/javascript.js"></script>
</body>

</html>