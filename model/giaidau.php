<?php
class GIAIDAU
{
    private $id;
    private $apiid;
    private $ten;

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
    public function getten()
    {
        return $this->ten;
    }
    public function setten($value)
    {
        $this->ten = $value;
    }

    // Lấy tất cả giải đấu
    public function laydanhsachgiaidau()
    {
        $dbcon = DATABASE::connect();
        try {
            $sql = "SELECT * FROM giaidau";
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
}
