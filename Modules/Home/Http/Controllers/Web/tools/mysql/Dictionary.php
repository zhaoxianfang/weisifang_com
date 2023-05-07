<?php

namespace Modules\Home\Http\Controllers\Web\tools\mysql;

use Modules\Home\Http\Controllers\HomeBase;
use zxf\tools\MysqlTool;
use Exception;

// mysql 数据字典生成器
class Dictionary extends HomeBase
{
    public function index()
    {
        if ($this->request->isMethod('post')) {
            try {
                $input    = $this->request->input();
                $tableStr = MysqlTool::dictionary($input['db_host'], $input['db_username'], $input['db_password'], $input['db_database']);
                return $this->success(['table_str' => $tableStr], '转换成功', 200);
            } catch (Exception $e) {
                return $this->error($e->getMessage(), 500);
            }
        }
        return view('home::home.tools.mysql.dictionary', []);
    }

}
