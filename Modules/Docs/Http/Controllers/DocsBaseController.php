<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Web\WebBaseController;
use Modules\Core\Services\WebTopNavService;

class DocsBaseController extends WebBaseController
{
    public function __construct(Request $request)
    {
        parent:: __construct($request);

        if (request()->isMethod('get')) {
            $menu = [
                [
                    'id'   => '/',
                    'pid'  => 0,
                    'icon' => 'fa fa-cube',
                    'name' => '广场',
                ],
            ];

            if (auth('web')->check()) {
                $menu[] = [
                    'id'   => 'mydoc',
                    'pid'  => 0,
                    'icon' => 'fa fa-book',
                    'name' => '我的',
                ];
                $menu[] = [
                    'id'   => 'create',
                    'pid'  => 0,
                    'icon' => 'fa fa-plus',
                    'name' => '新建',
                ];
            }
            WebTopNavService::instance()->renderMenu($menu, 'name', '/docs');
        }
    }
}
