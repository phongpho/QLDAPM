<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-mu sticky-top">
    <div class="container">
        
        <!-- Logo -->
        <a class="navbar-brand" href="index.php">
            <span>MUFC</span> Tickets
        </a>

        <!-- Nút Hamburger Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#muNavbar">
            <i class="fas fa-bars" style="color: #ffffff; font-size: 1.5rem;"></i>
        </button>

        <!-- Khối Menu chính -->
        <div class="collapse navbar-collapse" id="muNavbar">
            <ul class="navbar-nav ms-auto me-4">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'muave.php' || $current_page == 'tickets.php') ? 'active' : ''; ?>" href="muave.php">Mua Vé</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'lichthidau.php') ? 'active' : ''; ?>" href="lichthidau.php">Lịch thi đấu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'tintuc.php') ? 'active' : ''; ?>" href="tintuc.php">Tin tức</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'lienhe.php') ? 'active' : ''; ?>" href="lienhe.php">Liên hệ</a>
                </li>
            </ul>

            <!-- Khối Icon -->
            <div class="d-flex icon-group">
                <a href="giohang.php" class="icon-btn text-decoration-none" title="Giỏ hàng">
                    <i class="fas fa-shopping-cart"></i>
                </a>
                <a href="dangnhap.php" class="icon-btn text-decoration-none" title="Tài khoản">
                    <i class="fas fa-user"></i>
                </a>
            </div>
        </div>

    </div>
</nav>

<!-- JavaScript để xử lý hiệu ứng cuộn trang đổi màu Navbar -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const navbar = document.getElementById("mainNavbar");

        window.addEventListener("scroll", function() {
            if (window.scrollY > 50) {
                navbar.classList.add("navbar-scrolled");
            } else {
                navbar.classList.remove("navbar-scrolled");
            }
        });
    });
</script>