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

    /**
     * 创建文档
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function create()
    {
        return view('docs::apps.create');
    }

    /**
     * 某文档首页
     */
    public function firstPage(DocsApp $docsApp)
    {

    }
}
