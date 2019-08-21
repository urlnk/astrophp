<?php

namespace App\_Module\Controller;

class _Controller extends \MagicCube\Controller
{
    public function s()
    {
        global $_CONFIG;
        $this->enableView = false;
        $q = isset($_GET['q']) ? $_GET['q'] : '';
        $id = isset($_GET['id']) ? $_GET['id'] : '';

        $url = "/search/$id?q=%s";
        if ($id) {
            $sqlite = new \Ext\PhpPdoSqlite($_CONFIG['database']);
            $row =  $sqlite->find("SELECT * FROM search_url WHERE id='$id'");
            if ($row) {
                $url = $row->url;
            }
        } else {
            $url = "%s";
        }
        $url = preg_replace('/%s/i', urlencode($q), $url);

        header("Location: $url");
        return ['method' => __METHOD__];
    }

    public function __destruct()
    {
        global $template;
        $uriInfo =& $this->uriInfo;

        $params = explode('/', $uriInfo['param']);
        $action = $uriInfo['action'];
        if ('module' != $uriInfo['module']) {
            array_unshift($params, $action);
            $action = $uriInfo['controller'];
            $uriInfo['controller'] = $uriInfo['module'];
            $uriInfo['module'] = '_module';
        }
        
        if ('_controller' != $uriInfo['controller']) {
            array_unshift($params, $action);
            $actionCnt = lcfirst($uriInfo['controller']);
            $uriInfo['controller'] = '_controller';            
            $action = in_array($actionCnt, $this->methods) ? $actionCnt : $action;
        }        
        $uriInfo['action'] = $action;
        $uriInfo['param'] = trim(implode('/', $params), '/');

        # print_r(get_defined_vars());
        parent::__destruct();
    }
}
