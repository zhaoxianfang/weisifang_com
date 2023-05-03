<?php

namespace Modules\Test\Http\Controllers\Web;

use Modules\Core\Http\Controllers\Web\WebBaseController;

class EditorController extends WebBaseController
{
    public function index()
    {
        return $this->cherryMarkdown();
    }

    /**
     * 腾讯出的 markdown 编辑器
     *
     */
    public function cherryMarkdown()
    {
        return view('test::editor.cherry-markdown');
    }

}
