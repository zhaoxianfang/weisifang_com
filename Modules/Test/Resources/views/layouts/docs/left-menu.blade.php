{{--{-- white 或者默认黑色 --}}
<div class="docs-menu-box white" id="docs-left-menus-box">
    <div class="docs-menu-box-header">
        Header
    </div>
    <ul>
        {!! empty($left_menu_str)?'':$left_menu_str !!}
        <li class="active">
            <a class="docs-menu-item" href="#">
                Home
            </a>
        </li>
        <li>
            <a class="docs-menu-item" href="#">
                Events
            </a>
        </li>
        <li>
            <a class="docs-menu-item" href="#">
                Gallery
            </a>
            <span class="docs-menu-box-label">
                12
            </span>
        </li>
        <li>
            <a class="docs-menu-item" href="#">
                Services
                <span class="docs-menu-box-label">+1</span>
                <span class="custom-arrow custom-right-arrow"></span>
            </a>
            <ul class="submenu">
                <li>
                    <a class="docs-menu-item" href="#">
                        Web Design
                    </a>
                </li>
                <li>
                    <a class="docs-menu-item" href="#">
                        Hosting
                    </a>
                </li>
                <li>
                    <a class="docs-menu-item" href="#">
                        Design
                    </a>
                    <ul class="submenu">
                        <li>
                            <a class="docs-menu-item" href="#">
                                Graphics
                            </a>
                        </li>
                        <li class="active">
                            <a class="docs-menu-item" href="#">
                                Vectors -active
                            </a>
                        </li>
                        <li>
                            <a class="docs-menu-item" href="#">
                                Photoshop
                            </a>
                        </li>
                        <li>
                            <a class="docs-menu-item" href="#">
                                Fonts
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="docs-menu-item" href="#">
                        Consulting
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a class="docs-menu-item" href="#">
                News
            </a>
        </li>
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
        <li>
            <a class="docs-menu-item" href="#">
                Contact
            </a>
        </li>
    </ul>
    <div class="docs-menu-box-footer test-close">
        Footer  test Close
    </div>
</div>
