<?php

namespace App\Users\Controller;

class _Controller extends \MagicCube\Controller
{

    public function _action()
    {
        return ['method' => __METHOD__];
    }

    public function name()
    {
        return ['method' => __METHOD__];
    }

    public function documents()
    {
        return ['method' => __METHOD__];
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
        print_r(get_defined_vars());

        parent::__destruct();
    }
}
