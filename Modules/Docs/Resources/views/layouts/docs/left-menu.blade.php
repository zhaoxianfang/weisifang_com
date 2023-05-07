{{--{-- white 或者默认黑色 --}}
<div class="docs-menu-box white" id="docs-left-menus-box">
{{--    <div class="docs-menu-box-header">--}}
{{--        Header--}}
{{--    </div>--}}
    <ul>
        <li class="active">
            <a class="docs-menu-item" href="#">
                文件信息
            </a>
            <ul @if($menu_active && in_array($menu_active,['create','update','doc_users','help'])) class="submenu active" @else class="submenu" @endif" >
                @if($menu_active && $menu_active == 'create')
                    <li class="active" >
                        <a class="docs-menu-item" href="#">
                            创建文档
                        </a>
                    </li>
                @endif
                @if($menu_active && $menu_active == 'update')
                    <li class="active" >
                        <a class="docs-menu-item" href="#">
                            编辑文档
                        </a>
                    </li>
                @endif
                <li @if($menu_active && $menu_active == 'doc_users') class="active" @endif>
                    <a class="docs-menu-item" href="#">
                        文档成员
                    </a>
                    <span class="docs-menu-box-label">
                        10
                    </span>
                </li>
                <li @if($menu_active && $menu_active == 'help') class="active" @endif>
                    <a class="docs-menu-item" href="#">
                        使用手册
                    </a>
                </li>
            </ul>
        </li>
        {!! empty($left_menu_str)?'':$left_menu_str !!}
        <li>
            <a class="docs-menu-item" href="#">
                Portfolio
            </a>
            <ul class="submenu">
                <li>
                    <a class="docs-menu-item" href="#">
                        Web Design
                    </a>
                </li>
                <li>
                    <a class="docs-menu-item" href="#">
                        Graphics
                    </a>
                    <span class="docs-menu-box-label">
                        10
                    </span>
                </li>
                <li>
                    <a class="docs-menu-item" href="#">
                        Photoshop
                    </a>
                </li>
                <li>
                    <a class="docs-menu-item" href="#">
                        Programming
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a class="docs-menu-item" href="#">
                About
            </a>
        </li>
    </ul>
    <div class="docs-menu-box-footer test-close text-center">
        <button type="button" class="btn btn-outline btn-primary">
            <i class="fa fa-plus"></i>
            新建目录
        </button>
    </div>
</div>
