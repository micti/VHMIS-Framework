<?php

namespace Vhmis\Container\Service;

use Vhmis\Container\Param\ParamInterface;

trait ParamsValueTrait
{
    /**
     * @param ParamInterface[] $params
     *
     * @return array
     */
    protected function getRealValueOfParams($params) {
        $data = [];
        foreach($params as $param) {
            $data[] = $param->setContainer($this->container)->getValue();
        }
        return $data;
    }
}
