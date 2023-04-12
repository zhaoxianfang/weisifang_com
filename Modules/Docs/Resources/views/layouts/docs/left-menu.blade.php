{{--{-- white 或者默认黑色 --}}
<div class="docs-menu-box white">
    <div class="docs-menu-box-header">
        Header
    </div>
    <ul>
        {!! empty($left_menu_str)?'':$left_menu_str !!}
        <li class="active">
            <a href="#">
                Home
            </a>
        </li>
        <li>
            <a href="#">
                Events
            </a>
        </li>
        <li>
            <a href="#">
                Gallery
            </a>
            <span class="docs-menu-box-label">
                12
            </span>
        </li>
        <li>
            <a href="#">
                Services
                <span class="docs-menu-box-label">+1</span>
                <span class="custom-arrow custom-right-arrow"></span>
            </a>
            <ul class="submenu">
                <li>
                    <a href="#">
                        Web Design
                    </a>
                </li>
                <li>
                    <a href="#">
                        Hosting
                    </a>
                </li>
                <li>
                    <a href="#">
                        Design
                    </a>
                    <ul class="submenu">
                        <li>
                            <a href="#">
                                Graphics
                            </a>
                        </li>
                        <li class="active">
                            <a href="#">
                                Vectors -active
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                Photoshop
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                Fonts
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        Consulting
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#">
                News
            </a>
        </li>
        <li>
            <a href="#">
                Portfolio
            </a>
            <ul class="submenu">
                <li>
                    <a href="#">
                        Web Design
                    </a>
                </li>
                <li>
                    <a href="#">
                        Graphics
                    </a>
                    <span class="docs-menu-box-label">
                        10
                    </span>
                </li>
                <li>
                    <a href="#">
                        Photoshop
                    </a>
                </li>
                <li>
                    <a href="#">
                        Programming
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#">
                About
            </a>
        </li>
        <li>
            <a href="#">
                Contact
            </a>
        </li>
    </ul>
    <div class="docs-menu-box-footer test-close">
        Footer  test Close
    </div>
</div>
