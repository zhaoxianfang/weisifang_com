<?php

namespace Modules\Docs\Http\Controllers\Web;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Docs\Entities\DocsApp;
use Modules\Docs\Http\Controllers\DocsBaseController;

class DocsController extends DocsBaseController
{
    /**
     * 文档中心主页
     *
     * @return Renderable
     */
    public function index()
    {
        return view('docs::index');
    }
}
