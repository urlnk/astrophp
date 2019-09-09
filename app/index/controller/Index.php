<?php

namespace App\Index\Controller;

class Index extends \MagicCube\Controller
{
    public function index()
    {
        global $_CONFIG;
        $q = isset($_GET['q']) ? $_GET['q'] : '';
        $sqlite = new \Ext\PhpPdoSqlite($_CONFIG['database']);
        $all =  $sqlite->select("SELECT * FROM search_url ORDER BY category", [], \PDO::FETCH_OBJ);

        $data = array();
        foreach ($all as $key => $row) {
            if (!isset($data[$row->category])) {
                $data[$row->category] = array();
            }
            $data[$row->category][] = array($row->id, $row->title);
        }

        $target = $_CONFIG['home']['target'];

        return array(
            'arr' => $data,
            'target' => $target,
            'cdn_host' => $_CONFIG['cdn_host'],
            'query' => $q,
        );
    }
}
