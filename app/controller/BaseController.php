<?php

namespace app\controller;

use app\traits\BaseRequest;
use app\utils\json;
use DI\Attribute\Inject;
use support\Log;
use support\Request;

class BaseController
{
    use BaseRequest;

    /**
     * @var Request|\Webman\Http\Request|null
     */
    public  $request;

    public function __construct()
    {
        $this->request = \request();
    }


}
