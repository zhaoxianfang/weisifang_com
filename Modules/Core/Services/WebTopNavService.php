<?php

namespace Modules\Core\Services;

use Illuminate\Support\Facades\View;

class WebTopNavService extends BaseService
{
    /**
     * 设置 TopNav 顶部导航菜单
     *
     * @param array  $menuList   包含 'id'、'pid'、'name' 等的二维数组,可通过 icon 字段设置自定义 的小图标样式
     * @param string $titleField 如果 $menuList 中使用的不是 name ，可以指定字段名，例如：title
     * @param string $urlPrefix  url 访问前缀 默认使用 /classify, 可以自定义前缀，例如 /docs
     *
     * @return void
     */
    public function renderMenu(array $menuList, string $titleField = 'name', string $urlPrefix = '')
    {
        // $urlPrefix = !empty($urlPrefix) ? $urlPrefix : get_module_name();

        // list($modules, $controller, $method) = get_laravel_route();
        // $urlLink                             = '/' . $modules . '/' . $controller . '/' . $method;
        $urlLink = '';
        // 转换为视图

        $topNav = \zxf\extend\Menu::instance()->init($menuList)->setTitle($titleField)->setUrlPrefix($urlPrefix)->setActiveMenu($urlLink)->createMenu(0, 'home');

        View::share('top_nav_string', $topNav ?? '');

    }
}
