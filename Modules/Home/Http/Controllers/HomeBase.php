<?php

namespace Modules\Home\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Modules\Blog\Entities\ArticleClassifies;
use Modules\Core\Http\Controllers\Web\WebBaseController;
use Modules\Core\Services\WebTopNavService;

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

    public function showTipsBtnPage($tips = '出错啦！', $url = 'javascript:;')
    {
        return response()->view(get_module_name() . '::other/info', ['info' => $tips, 'type' => 'warning', 'url' => $url], 200)->header('Content-Type', 'text/html');
    }

    /**
     * 中转 提示页面
     *
     * @param string $buttonUrl     点击按钮跳转地址
     * @param string $buttonText    按钮提示信息
     * @param array  $form_elements 提示页面表单input数据 格式 ['name'=>'姓名','键值'=>'中文提示']
     * @param string $message       表单上方的提示文字
     * @param string $buttonStyle   primary|info|default|success|warning|danger
     */
    public function transferPage(string $buttonUrl = '/', $buttonText = '回首页', $form_elements = [], $message = '', $buttonStyle = 'primary')
    {
        return die(view('system::other/transfer', [
            'button_text'   => $buttonText,
            'type'          => $buttonStyle,
            'message'       => $message,
            'button_url'    => $buttonUrl,
            'form_elements' => $form_elements,
        ]));//->header('Content-Type', 'text/html'));
    }


    /**
     * 通知结果页面
     *
     * @param string $message 提示文字
     * @param string $type    类型 success|error|info|warning
     */
    public function tipsPage(string $message = '操作成功', string $type = 'success')
    {
        return die(view('system::other/tips', [
            'type'    => $type ?? 'success',
            'message' => $message,
        ]));
    }

}
