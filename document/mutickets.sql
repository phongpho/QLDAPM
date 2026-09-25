CREATE DATABASE IF NOT EXISTS mutickets CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mutickets;

-- nguoidung
CREATE TABLE nguoidung (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tendangnhap VARCHAR(100) NOT NULL,
    matkhau VARCHAR(255) NULL,
    email VARCHAR(100) NOT NULL,
    googleid VARCHAR(255) NULL,
    vaitro ENUM('admin', 'tickets', 'schedule', 'user') DEFAULT 'user',
    ngaytao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uk_nguoidung_email UNIQUE (email),
    CONSTRAINT uk_nguoidung_googleid UNIQUE (googleid)
);

-- thong tin nguoi dung
CREATE TABLE thongtinnguoidung(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nguoidungid INT NOT NULL,
    avatar VARCHAR(255) NULL,
    ngaysinh DATE NULL,
    quequan VARCHAR(255) NULL,
    sdt VARCHAR(255) NULL,
    lahoivien BOOLEAN DEFAULT FALSE,
    CONSTRAINT uk_thongtin_nguoidung UNIQUE (nguoidungid),
    FOREIGN KEY (nguoidungid) REFERENCES nguoidung(id) ON DELETE CASCADE
);

-- giaidau
CREATE TABLE giaidau (
    id INT AUTO_INCREMENT PRIMARY KEY,
    apiid VARCHAR(100) NOT NULL,
    ten VARCHAR(100) NOT NULL,
    CONSTRAINT uk_giaidau_apiid UNIQUE (apiid)
);

-- doibong
CREATE TABLE doibong (
    id INT AUTO_INCREMENT PRIMARY KEY,
    apiid VARCHAR(100) NOT NULL,
    ten VARCHAR(100) NOT NULL,
    logo VARCHAR(255) NULL,
    CONSTRAINT uk_doibong_apiid UNIQUE (apiid)
);

-- trandau
CREATE TABLE trandau (
    id INT AUTO_INCREMENT PRIMARY KEY,
    apiid VARCHAR(100) NOT NULL,
    giaidauid INT NOT NULL,
    doinhaid INT NOT NULL,
    doikhachid INT NOT NULL,
    thoigian DATETIME NOT NULL,
    diadiem ENUM('sannha', 'sankhach') DEFAULT 'sannha',
    trangthaitrandau VARCHAR(50) DEFAULT 'sapda',
    trangthaibanve ENUM('chuamo', 'dangban', 'hethang') DEFAULT 'chuamo',
    gioihanvesankhach INT NULL,
    ngaytao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uk_trandau_apiid UNIQUE (apiid),
    CONSTRAINT uk_trandau_lichthidau UNIQUE (doinhaid, doikhachid, thoigian),
    FOREIGN KEY (giaidauid) REFERENCES giaidau(id) ON DELETE CASCADE,
    FOREIGN KEY (doinhaid) REFERENCES doibong(id) ON DELETE CASCADE,
    FOREIGN KEY (doikhachid) REFERENCES doibong(id) ON DELETE CASCADE
);

-- khandai
CREATE TABLE khandai (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten VARCHAR(100) NOT NULL
);

-- khuvuc
CREATE TABLE khuvuc (
    id INT AUTO_INCREMENT PRIMARY KEY,
    khandaiid INT NOT NULL,
    mablock VARCHAR(20) NOT NULL,
    ten VARCHAR(50) NOT NULL,
    giave DECIMAL(10,2) NOT NULL,
    CONSTRAINT uk_khuvuc_khandai_block UNIQUE (khandaiid, mablock),
    FOREIGN KEY (khandaiid) REFERENCES khandai(id) ON DELETE CASCADE
);

-- ghe
CREATE TABLE ghe (
    id INT AUTO_INCREMENT PRIMARY KEY,
    khuvucid INT NOT NULL,
    hangghe VARCHAR(10) NOT NULL,
    soghe INT NOT NULL,
    seasontickets BOOLEAN DEFAULT FALSE,
    CONSTRAINT uk_ghe_vitri UNIQUE (khuvucid, hangghe, soghe),
    FOREIGN KEY (khuvucid) REFERENCES khuvuc(id) ON DELETE CASCADE
);

-- ve mua
CREATE TABLE vemua (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nguoidungid INT NOT NULL,
    gheid INT NOT NULL,
    muagiai VARCHAR(20) NOT NULL,
    ngaytao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uk_vemua_ghe_mua UNIQUE (gheid, muagiai),
    FOREIGN KEY (nguoidungid) REFERENCES nguoidung(id) ON DELETE CASCADE,
    FOREIGN KEY (gheid) REFERENCES ghe(id) ON DELETE CASCADE
);

-- giughe
CREATE TABLE giughe (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trandauid INT NOT NULL,
    gheid INT NOT NULL,
    nguoidungid INT NULL,
    sessionid VARCHAR(100) NULL,
    hethangiughe DATETIME NOT NULL,
    CONSTRAINT uk_giughe_trandau_ghe UNIQUE (trandauid, gheid),
    FOREIGN KEY (trandauid) REFERENCES trandau(id) ON DELETE CASCADE,
    FOREIGN KEY (gheid) REFERENCES ghe(id) ON DELETE CASCADE,
    FOREIGN KEY (nguoidungid) REFERENCES nguoidung(id) ON DELETE CASCADE
);

-- donhang
CREATE TABLE donhang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nguoidungid INT NOT NULL,
    tongtien DECIMAL(10,2) NOT NULL,
    phuongthuc VARCHAR(50) DEFAULT 'VNPay',
    trangthai ENUM('chothanhtoan', 'dathanhtoan', 'dahuy') DEFAULT 'chothanhtoan',
    magiaodich VARCHAR(100) NULL,
    hethanthanhtoan DATETIME NOT NULL,
    ngaytao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uk_donhang_magiaodich UNIQUE (magiaodich),
    FOREIGN KEY (nguoidungid) REFERENCES nguoidung(id) ON DELETE CASCADE
);

-- ve
CREATE TABLE ve (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donhangid INT NOT NULL,
    trandauid INT NOT NULL,
    gheid INT NULL,
    mave VARCHAR(20) NOT NULL,
    maqr VARCHAR(255) NOT NULL,
    trangthai ENUM('hople', 'dacheckin', 'dangnhuong', 'dahuy') DEFAULT 'hople',
    CONSTRAINT uk_ve_mave UNIQUE (mave),
    CONSTRAINT uk_ve_maqr UNIQUE (maqr),
    CONSTRAINT uk_ve_trandau_ghe UNIQUE (trandauid, gheid),
    FOREIGN KEY (donhangid) REFERENCES donhang(id) ON DELETE CASCADE,
    FOREIGN KEY (trandauid) REFERENCES trandau(id) ON DELETE CASCADE,
    FOREIGN KEY (gheid) REFERENCES ghe(id) ON DELETE SET NULL
);

-- chuyen nhuong ve
CREATE TABLE nhuongve (
    id INT AUTO_INCREMENT PRIMARY KEY,
    veid INT NOT NULL,
    nguoinhuongid INT NOT NULL,
    nguoimuaid INT NULL,
    gianhuong DECIMAL(10,2) NOT NULL,
    trangthainhuong ENUM('dangraoban', 'daban', 'dahuy') DEFAULT 'dangraoban',
    ngaytao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uk_nhuongve_veid UNIQUE (veid),
    FOREIGN KEY (veid) REFERENCES ve(id) ON DELETE CASCADE,
    FOREIGN KEY (nguoinhuongid) REFERENCES nguoidung(id) ON DELETE CASCADE,
    FOREIGN KEY (nguoimuaid) REFERENCES nguoidung(id) ON DELETE CASCADE
);

-- bangxephang
CREATE TABLE bangxephang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    giaidauid INT NOT NULL,
    doibongid INT NOT NULL,
    thuhang INT NULL,
    sotrandada INT DEFAULT 0,
    thang INT DEFAULT 0,
    hoa INT DEFAULT 0,
    thua INT DEFAULT 0,
    banthang INT DEFAULT 0,
    banthua INT DEFAULT 0,
    hieuso INT DEFAULT 0,
    diemso INT DEFAULT 0,
    ngaycapnhat TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT uk_bxh_giai_doi UNIQUE (giaidauid, doibongid),
    FOREIGN KEY (giaidauid) REFERENCES giaidau(id) ON DELETE CASCADE,
    FOREIGN KEY (doibongid) REFERENCES doibong(id) ON DELETE CASCADE
);

-- tintuc (có thể bỏ nha mấy ông)
CREATE TABLE tintuc (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tieude VARCHAR(255) NOT NULL,
    anhdaidien VARCHAR(255) NULL,
    noidung TEXT NULL,
    ngaytao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);