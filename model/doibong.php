<?php
class DOIBONG {
    private $id;
    private $apiid;
    private $ten;
    private $logo;

    public function getid() { return $this->id; }
    public function setid($value) { $this->id = $value; }
    public function getapiid() { return $this->apiid; }
    public function setapiid($value) { $this->apiid = $value; }
    public function getten() { return $this->ten; }
    public function setten($value) { $this->ten = $value; }
    public function getlogo() { return $this->logo; }
    public function setlogo($value) { $this->logo = $value; }

    // Lấy toàn bộ đội bóng
    public function laydanhsachdoibong() {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM doibong ORDER BY ten ASC";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            return $cmd->fetchAll();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    // Lấy danh sách đội bóng thuộc một giải đấu cụ thể qua bảng doibong_giaidau
    public function laydoibongtheogiaidau($giaidauid) {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT d.* 
                    FROM doibong d
                    JOIN doibong_giaidau dg ON d.id = dg.doibongid
                    WHERE dg.giaidauid = :giaidauid
                    ORDER BY d.ten ASC";

            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":giaidauid", $giaidauid);
            $cmd->execute();
            return $cmd->fetchAll();
        } catch (PDOException $e) {
            echo "<p>Lỗi truy vấn: " . $e->getMessage() . "</p>";
            exit();
        }
    }
}
?>