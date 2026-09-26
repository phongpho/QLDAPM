<?php
class TRANDAU
{
    private $id;
    private $apiid;
    private $giaidauid;
    private $doinhaid;
    private $doikhachid;
    private $thoigian;
    private $diadiem;
    private $trangthaitrandau;
    private $trangthaibanve;
    private $gioihanvesankhach;

    public function getid()
    {
        return $this->id;
    }
    public function setid($value)
    {
        $this->id = $value;
    }
    public function getapiid()
    {
        return $this->apiid;
    }
    public function setapiid($value)
    {
        $this->apiid = $value;
    }
    public function getgiaidauid()
    {
        return $this->giaidauid;
    }
    public function setgiaidauid($value)
    {
        $this->giaidauid = $value;
    }
    public function getdoinhaid()
    {
        return $this->doinhaid;
    }
    public function setdoinhaid($value)
    {
        $this->doinhaid = $value;
    }
    public function getdoikhachid()
    {
        return $this->doikhachid;
    }
    public function setdoikhachid($value)
    {
        $this->doikhachid = $value;
    }
    public function getthoigian()
    {
        return $this->thoigian;
    }
    public function setthoigian($value)
    {
        $this->thoigian = $value;
    }
    public function getdiadiem()
    {
        return $this->diadiem;
    }
    public function setdiadiem($value)
    {
        $this->diadiem = $value;
    }
    public function gettrangthaitrandau()
    {
        return $this->trangthaitrandau;
    }
    public function settrangthaitrandau($value)
    {
        $this->trangthaitrandau = $value;
    }
    public function gettrangthaibanve()
    {
        return $this->trangthaibanve;
    }
    public function settrangthaibanve($value)
    {
        $this->trangthaibanve = $value;
    }
    public function getgioihanvesankhach()
    {
        return $this->gioihanvesankhach;
    }
    public function setgioihanvesankhach($value)
    {
        $this->gioihanvesankhach = $value;
    }

    // lấy toàn bộ lịch thi đấu
    public function laydanhsachtrandau()
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT t.*, 
                           g.ten AS tengiaidau, 
                           dn.ten AS tendoinha, dn.logo AS logodoinha, 
                           dk.ten AS tendoikhach, dk.logo AS logodoikhach 
                    FROM trandau t
                    JOIN giaidau g ON t.giaidauid = g.id
                    JOIN doibong dn ON t.doinhaid = dn.id
                    JOIN doibong dk ON t.doikhachid = dk.id
                    ORDER BY t.thoigian ASC";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            $result = $cmd->fetchAll();
            return $result;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            echo "<p>Lỗi truy vấn: $error_message</p>";
            exit();
        }
    }

    // các trận sắp diễn ra
    public function laytrandausapda()
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT t.*, 
                           g.ten AS tengiaidau, 
                           dn.ten AS tendoinha, dn.logo AS logodoinha, 
                           dk.ten AS tendoikhach, dk.logo AS logodoikhach 
                    FROM trandau t
                    JOIN giaidau g ON t.giaidauid = g.id
                    JOIN doibong dn ON t.doinhaid = dn.id
                    JOIN doibong dk ON t.doikhachid = dk.id
                    WHERE t.thoigian >= NOW()
                    ORDER BY t.thoigian ASC";
            $cmd = $dbcon->prepare($sql);
            $cmd->execute();
            $result = $cmd->fetchAll();
            return $result;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            echo "<p>Lỗi truy vấn: $error_message</p>";
            exit();
        }
    }

    // lấy lịch thi đấu theo id
    public function laytrandautheoid($id)
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT t.*, 
                           g.ten AS tengiaidau, 
                           dn.ten AS tendoinha, dn.logo AS logodoinha, 
                           dk.ten AS tendoikhach, dk.logo AS logodoikhach 
                    FROM trandau t
                    JOIN giaidau g ON t.giaidauid = g.id
                    JOIN doibong dn ON t.doinhaid = dn.id
                    JOIN doibong dk ON t.doikhachid = dk.id
                    WHERE t.id = :id";
            $cmd = $dbcon->prepare($sql);
            $cmd->bindValue(":id", $id);
            $cmd->execute();
            $result = $cmd->fetch();
            return $result;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            echo "<p>Lỗi truy vấn: $error_message</p>";
            exit();
        }
    }
    

    // tìm kiếm lịch thi đấu với đối thủ chỉ định
}
