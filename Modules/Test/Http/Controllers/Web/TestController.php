<?php

namespace Modules\Test\Http\Controllers\Web;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Http\Controllers\Web\WebBaseController;

class TestController extends WebBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        // 写入默认日志通道
        // \Illuminate\Support\Facades\Log::error('===== test log =====');
        // 测试预览
        return view('test::index');
    }

    public function lang()
    {
        return view('test::lang');
    }

    public function table()
    {
        return view('test::table');
    }

    public function getTableList()
    {
        $list = [
            [
                'id'              => 1,
                'title'           => 'title',
                'classify_name'   => 'classify_name',
                'author_nickname' => 'author_nickname',
                'create_time'     => '2023-01-01 00:00:00',
                'status'          => 'status',
            ],
        ];
        return $this->json(['rows' => $list, 'total' => 1]);
    }


    public function testDocs()
    {
        $list        = [
            [
                'id'      => '1',
                'docs_id' => '1',
                'name'    => 'Name1',
                'label'   => '最新',
                'pid'     => '0',
                'sort'    => '0',
                'status'  => '0',
            ], [
                'id'      => '2',
                'docs_id' => '1',
                'name'    => 'Name2',
                'label'   => '最新1',
                'pid'     => '0',
                'sort'    => '0',
                'status'  => '0',
            ], [
                'id'      => '3',
                'docs_id' => '1',
                'name'    => 'Name3 2-3',
                'label'   => '最新1-1',
                'pid'     => '2',
                'sort'    => '0',
                'status'  => '0',
            ], [
                'id'      => '4',
                'docs_id' => '1',
                'name'    => 'Name4 3-4',
                'label'   => '最新4',
                'pid'     => '3',
                'sort'    => '0',
                'status'  => '0',
            ], [
                'id'      => '5',
                'docs_id' => '1',
                'name'    => 'Name5 2-5',
                'label'   => '最新4',
                'pid'     => '2',
                'sort'    => '0',
                'status'  => '0',
            ], [
                'id'      => '6',
                'docs_id' => '1',
                'name'    => 'Name6',
                'label'   => '最新4',
                'pid'     => '0',
                'sort'    => '0',
                'status'  => '0',
            ], [
                'id'      => '7',
                'docs_id' => '1',
                'name'    => 'Name7 6-7',
                'label'   => '最新4',
                'pid'     => '6',
                'sort'    => '0',
                'status'  => '0',
            ], [
                'id'      => '8',
                'docs_id' => '1',
                'name'    => 'Name8',
                'label'   => '最新4',
                'pid'     => '0',
                'sort'    => '0',
                'status'  => '0',
            ],
        ];
        $treeArr     = $this->tree($list);
        $leftMenuStr = $this->generateLeftMenu($treeArr);
        $this->view('left_menu_str', $leftMenuStr);
        return view('test::docs');
    }

    /**
     * 二维数组 转为 树形结构
     *
     * @param array  $array        二维数组
     * @param int    $superior_id  上级ID
     * @param string $superior_key 父级键名
     * @param string $primary_key  主键名
     * @param string $son_key      子级键名
     *
     * @return array
     **@author super
     * @time 2020-12-22 10:25:19
     */
    public function tree(array $array, $superior_id = 0, $superior_key = 'pid', $primary_key = 'id', $son_key = 'son'): array
    {
        $return = [];
        foreach ($array as $k => $v) {
            if ($v[$superior_key] == $superior_id) {
                $son = $this->tree($array, $v[$primary_key], $superior_key, $primary_key, $son_key);
                if ($son) {
                    $v[$son_key] = $son;
                }
                $return[] = $v;
            }
        }
        return $return;
    }

    public function generateLeftMenu($treeArr)
    {
        $str = '';
        if (empty($treeArr)) {
            return $str;
        }
        foreach ($treeArr as $item) {
            $str .= '<li class="active"><a class="docs-menu-item" href="#">' . $item['name'] . (
                empty($item['label']) ? '' : '<span class="docs-menu-box-label">' . $item['label'] . '</span>'
                ) . '</a>';
            if (!empty($item['son'])) {
                $str .= ' <ul class="submenu">' . $this->generateLeftMenu($item['son']) . '</ul>';
            }
            $str .= '</li>';
        }
        return $str;
    }
}
