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
        $qru = rawurlencode($q);

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
        $url = preg_replace('/%s/i', $qru, $url);

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
        if ('_module' != $uriInfo['module']) {
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

        # print_r(get_defined_vars());exit;
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

    public function sleep()
    {
        $Tel = new \Model\Tel;
        $sql = "SELECT sleep FROM `life` WHERE sleep IS NOT NULL LIMIT 50";
        $all = $Tel->select($sql);
        $count = count($all);
        $arr = [0, 0, 0];
        foreach ($all as $row) {
            $exp = explode(':', $row->sleep);
            $arr[0] += $exp[0];
            $arr[1] += $exp[1];
            $arr[2] += $exp[2];
        }
        $min = $arr[0] * 60 + $arr[1];
        echo $hours = round($min / 60 / $count, 2);
        print_r($arr);exit;
    }

    public function contacts()
    {
        $uriInfo =& $this->uriInfo;
        $params = explode('/', $this->uri);
        $num = array_pop($params);
        # print_r(get_defined_vars());

        // 动作匹配
        if (preg_match('/\d+/i', $num, $matches)) {
            $uriInfo['action'] = 'contact';
            return $this->contact($num);
        } elseif (!in_array(strtolower($num), array('index', 'contacts')) && preg_match('/[a-z]+/i', $num, $matches)) {
            $method = strtolower($num);
            return $this->$method();
        }

        $q = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $where = '';
        if ($q) {
            $where = "`NickName` LIKE '%$q%'";
        }
        $w = $where ? ' WHERE ' . $where : '';
        $offset = $page * 50 - 50;

        $Tel = new \Model\Tel;
        $sql = "SELECT id, NickName FROM beings.`contact_item` $w LIMIT $offset,50";
        $all = $Tel->select($sql);
        return get_defined_vars();
    }

    public function contact($id)
    {
        $Tel = new \Model\Tel;
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $t = $_POST['type'];
            $n = $_POST['text'];
            $app_id = $_POST['app_id'] ?? -1;
            $no = addslashes($n);
            switch ($t) {
                case 'addr':
                    $sql = "INSERT INTO beings.contact_address SET contact_id = $id, location = '$no'";
                    break;

                case 'tel':
                    $sql = "INSERT INTO beings.contact_phone SET contact_id = $id, phone_number = '$no'";
                    break;

                default:
                    $sql = "INSERT INTO beings.contact_app SET contact_id = $id, app_account = '$no', app_id = '$app_id'";
                    break;
            }
            $ins = $Tel->query($sql);
        }

        $sql = "SELECT * FROM beings.`contact_item` WHERE id = $id";
        $row = $Tel->get($sql);

        $sql = "SELECT * FROM `contact_phone` WHERE `contact_id` = '$id' LIMIT 50";
        $all = $Tel->select($sql);

        $sql = "SELECT A.*, B.name 
FROM beings.`contact_app` A 
LEFT JOIN application.app_list B ON B.id = A.app_id 
WHERE `contact_id` = '$id' 
ORDER BY `app_id` 
LIMIT 50";
        $app = $Tel->select($sql);

        $sql = "SELECT id, name FROM application.`app_list` LIMIT 50";
        $apps = $Tel->select($sql);

        $sql = "SELECT id, location FROM beings.`contact_address` WHERE `contact_id` = '$id' LIMIT 50";
        $addr = $Tel->select($sql);
        return get_defined_vars();
    }

    public function add()
    {
        $uriInfo =& $this->uriInfo;
        $uriInfo['action'] = 'contact-add';

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $Tel = new \Model\Tel;
            $n = $_POST['name'];
            $name = addslashes($n);
            $nu = rawurlencode($n);
            $sql = "INSERT INTO beings.contact_item SET NickName = '$name'";
            $ins = $Tel->query($sql);
            $url = "/contacts?q=$nu";
            header("Location: $url");
            exit;
        }

        return get_defined_vars();
    }
}
