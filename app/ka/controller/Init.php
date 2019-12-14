<?php

namespace App\Ka\Controller;

class Init extends _controller
{
    public function index()
    {
        global $_CONFIG;
        // 客户端设备访问统计
        $no = isset($_GET['no']) ? trim($_GET['no']) : null;
        $dt = date('Y-m-d H:i:s');
        if ($no) {
            setcookie('no', $no, 4729600202);
        } elseif (isset($_COOKIE['no'])) {
            $no = $_COOKIE['no'] ? : null;
        }
        if (!$no) {
            $no = $dt;
            setcookie('no', $no, 4729600202);
        }
        $no = addslashes($no);
        $sqlite = new \Ext\PhpPdoSqlite($_CONFIG['database2']);
        $sql = "SELECT * FROM device WHERE no = '$no' LIMIT 1";
        $row =  $sqlite->find($sql, [], \PDO::FETCH_OBJ);
        if ($row) {
            $sql = "UPDATE device SET updated = '$dt', updates = updates + 1 WHERE `no` = '$no'";
        } else {
            $sql = "INSERT INTO device (\"no\", created, updated, updates) VALUES ('$no', '$dt', '$dt', 1)";
        }
        $row =  $sqlite->query($sql);
        unset($_GET['no']);

        $_GET['_'] = time();
        $queryString = http_build_query($_GET);
        header("Location: /ka?$queryString");
        exit;
    }
}
