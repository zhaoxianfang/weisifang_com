<?php

namespace Modules\Home\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Modules\Blog\Entities\ArticleClassifies;
use Modules\System\Http\Controllers\Web\WebBaseController;
use Modules\System\Services\WebTopNavService;

class HomeBase extends WebBaseController
{
//    public function __construct(Request $request)
//    {
//        parent::__construct($request);
//
//        View::share('list', []);
//        View::share('bread_crumb', '');
//
//        $artClassify = ArticleClassifies::open()->get()->toArray();
//        // 设置top菜单目录
//        WebTopNavService::instance()->renderMenu($artClassify, 'name', '/blog/c');
//
//    }

}
