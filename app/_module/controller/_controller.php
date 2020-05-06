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
        $Tel = new \Model\Tel();
        $qs = addslashes($q);
        $strlen = strlen($q);
        $leng = mb_strlen($q);

        $url = "/search/$id?q=%s";
        $params = array();
        if ($id) {
            $sqlite = new \Ext\PhpPdoSqlite($_CONFIG['database2']);
            $row =  $sqlite->find("SELECT * FROM search_url WHERE id='$id'");
            if ($row) {
                $url = $row->url;
                if ($row->param) {
                    $this->enableView = true;
                    $param = preg_replace('/%s/i', $q, $row->param);
                    parse_str($param, $params);
                }
            }
        } else {
            $url = "/?q=%s";
        }
        $url = preg_replace('/%s/i', urlencode($q), $url);

        /* 记录 */
        $sql = "SELECT id FROM text.url_entry WHERE text = '$qs'";
        $row = $Tel->get($sql);
        $sql = "INSERT INTO text.url_entry SET text = '$qs', strlen = $strlen, length = $leng, created = NOW()";
        if (!$row) {
            $ins = $Tel->query($sql);
        } else {
            $sql = "UPDATE text.url_entry SET total = total + 1, updated = NOW() WHERE id = $row->id";
            $up = $Tel->query($sql);
        }

        if (!$this->enableView) {
            header("Location: $url");
            exit;
        }
        return get_defined_vars();
    }

    public function search()
    {
        print_r(array(__FILE__, __LINE__));
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

    public function tel()
    {
        $uriInfo =& $this->uriInfo;
        $Tel = new \Model\Tel();
        $params = explode('/', $uriInfo['param']);
        $num = array_shift($params);

        // 动作匹配
        if (preg_match('/[a-z]+/i', $num, $matches)) {
            $method = strtolower($num);
            return $this->$method();
        }

        // 参数分析
        $fenxi = explode('-', $num);
        $count = count($fenxi);
        $city = 0;
        $number = $num;
        if (1 < $count) {
            $city = $fenxi[0];
            $number = $fenxi[1];
        }

        $sql = "SELECT * FROM tel_list WHERE city = '$city' AND `number` = '$number' LIMIT 1";
        $obj = $Tel->get($sql);
        if (!$obj) {
            $sql = "INSERT INTO tel_list SET city = '$city', `number` = '$number'";
            $ins = $Tel->query($sql);
        }
        print_r(get_defined_vars());
    }

    public function call()
    {
        $num = isset($_GET['num']) ? $_GET['num'] : '';
        $uriInfo =& $this->uriInfo;
        $uriInfo['action'] = 'call';
        $Tel = new \Model\Tel();

        $where = '';
        if ($num) {
            // 参数分析
            $fenxi = explode('-', $num);
            $count = count($fenxi);
            $city = 0;
            $number = $num;
            if (1 < $count) {
                $city = $fenxi[0];
                $number = $fenxi[1];
            }

            // 获取 ID
            $sql = "SELECT * FROM tel_list WHERE `number` = '$number' AND city = '$city'";
            $obj = $Tel->get($sql);
            if ($obj) {
                $id = $obj->id;
                $where = "`from` = '$id' OR `to` = '$id'";
            }
        }

        $w = $where ? ' WHERE ' . $where : '';
        $sql = "SELECT A.*, B.city AS fc, B.number AS f, C.city AS tc, C.number AS t 
FROM tel_call A 
LEFT JOIN tel_list B ON B.id = A.from 
LEFT JOIN tel_list C ON C.id = A.to 
$w 
ORDER BY A.`time` DESC 
LIMIT 50";
        $all = $Tel->select($sql);
        return get_defined_vars();
    }

    public function bank()
    {
        $card = isset($_GET['card']) ? $_GET['card'] : '';
        $uriInfo =& $this->uriInfo;
        $uriInfo['action'] = 'bank';
        $Tel = new \Model\Tel;
        $Tel->db_name = 'bank';
        $Tel->inst();

        /* 列出支出 */
        $where = '';
        if ($card) {
            $where = "`card_id` = '$card'";
        }

        $w = $where ? ' WHERE ' . $where : '';
        $sql = "
SELECT A.*, B.title AS item, C.number, D.title AS app, E.account_name, F.title as order_app, G.account_name AS order_account 
FROM `bank_expense` A 
LEFT JOIN pay_item B ON B.id = A.item_id 
LEFT JOIN bank_card C ON C.id = A.card_id 
LEFT JOIN bank_app D ON D.id = A.app_id 
LEFT JOIN app_account E ON E.id = A.account_id 
LEFT JOIN bank_app F ON F.id = A.order_app 
LEFT JOIN app_account G ON G.id = A.order_account 
$w 
ORDER BY paid DESC 
LIMIT 50 ";

        $all = $Tel->select($sql);
        # print_r($all);exit;
        return get_defined_vars();
    }

    public function article()
    {
        $Tel = new \Model\Tel;
        $sql = "
SELECT A.*, CONCAT(B.FamilyName, B.GivenName) AS name 
FROM text.`article_list` A 
LEFT JOIN beings.people_names B ON B.id = A.people_id 
LIMIT 50
";
        $all = $Tel->select($sql);
        return get_defined_vars();
    }
}
