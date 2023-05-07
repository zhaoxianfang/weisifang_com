<?php

namespace Modules\Docs\Http\Controllers\Web;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;
use Modules\Docs\Entities\DocsApp;
use Modules\Docs\Entities\DocsAppUser;
use Modules\Docs\Http\Controllers\DocsBaseController;
use Modules\Core\Services\ImagesServices;

//use Modules\Docs\Services\DocsAppService;

class DocsAppController extends DocsBaseController
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
        if ($toAuth = $this->guestToAuth()) {
            return $toAuth;
        }
        return view('docs::apps.create', ['menu_active' => 'create']);
    }

    public function store(ImagesServices $imagesServices)
    {
        if ($toAuth = $this->guestToAuth()) {
            return $toAuth;
        }
        DB::beginTransaction();

        $request = $this->request;
        if ($request->hasFile('app_cover')) {
            $uploadInfo = $imagesServices->upload('app_cover');
            $app_cover  = $uploadInfo['url'];
        } else {
            $app_cover = empty($request->app_cover) ? DocsApp::DEFAULT_COVER_PATH : $request->app_cover;
        }

        $userId = auth('web')->id(); // 创建人

        $urls = [];
        if (!empty($request->urls) && !empty($request->urls['url_prefix'])) {
            foreach ($request->urls['url_prefix'] as $key => $item) {
                $urls[] = [
                    'alias'      => $request->urls['alias'][$key],
                    'url_prefix' => $request->urls['url_prefix'][$key],
                ];
            }
        }

        $app = new DocsApp();
        $app->fill([
            'app_name'           => $request->app_name,
            'app_cover'          => $app_cover,
            'urls'               => (array)$urls,
            'description'        => $request->description,
            'open_type'          => $request->open_type,
            'author'             => $userId,
            'team_name'          => $request->team_name,
            'mark_day'           => $request->mark_day,
            'comments_open_type' => DocsApp::COMMENTS_OPEN_TYPE_SHOW,
            'status'             => $request->status,
        ]);
        $app->save();

        // 设置文档创始人者角色
        $app->users()->syncWithoutDetaching([
            $userId => [
                'audit_id'   => $userId,
                'doc_app_id' => $app->id,
                'role'       => DocsAppUser::ROLE_FOUNDER,
                'status'     => DocsAppUser::STATUS_PASS,
            ],
        ]);

        $jump = route('docs.edit', ['app' => $app->id]); // 成功后跳转到文档设置页面地址
        DB::commit();

        return $this->success([], '创建成功', 200, $jump);
    }

    /**
     * 某文档首页
     */
    public function firstPage(DocsApp $docsApp)
    {
//        // 获取第一篇doc并展示
//        list($viewPath, $_app, $firstDoc) = DocsAppService::instance()->getDefaultPageOfApp($app);
//        DocsAppService::instance()->renderAppInfoAndMenuStr($_app, $firstDoc ? $firstDoc->id : '');
//
//        return view($viewPath);
    }
}
