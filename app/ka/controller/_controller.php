<?php

namespace App\Ka\Controller;

class _Controller extends \MagicCube\Controller
{
    public $static_version = '?v=350';

    public function __construct($vars = [])
    {
        global $_CONFIG;
        parent::__construct($vars);
        $this->db = $_CONFIG['database']['db_name'];
        # session_start();
    }

    public function _action()
    {
        return array(
            'static_version' => $this->static_version,
        );
    }

    public function status()
    {
        global $_CONFIG;
        $sqlite = new \Ext\PhpPdoSqlite($_CONFIG['database2']);
        $sql = "SELECT * FROM device ORDER BY updated LIMIT 100";
        $all =  $sqlite->select($sql, [], \PDO::FETCH_OBJ);
        return array(
            'static_version' => $this->static_version,
            'all' => $all,
        );
    }

    public function __destruct()
    {
        global $template;
        $uriInfo =& $this->uriInfo;

        $action = $uriInfo['action'] ? : '_action';
        if ('_controller' != $uriInfo['controller']) {
            $actionCnt = lcfirst($uriInfo['controller']);
            $uriInfo['controller'] = '_controller';

            $action = in_array($actionCnt, $this->methods) ? $actionCnt : $action;
        }
        $uriInfo['action'] = $action;

        parent::__destruct();
    }
}
