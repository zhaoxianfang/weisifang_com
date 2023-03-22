<?php

namespace Modules\Home\Http\Controllers\Web\tools\string;

use Modules\Home\Http\Controllers\HomeBase;

// unicode 转码
class Unicode extends HomeBase
{
    public function index()
    {
        return view('home::home.tools.string.unicode', []);
    }

}
