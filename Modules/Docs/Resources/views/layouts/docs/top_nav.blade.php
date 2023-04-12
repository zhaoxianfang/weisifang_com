<nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
    <div class="container">
        <a href="#" class="navbar-brand">
            <img src="{{ asset('static/inspinia/img/a6.jpg') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8;height: 33px;">
            <span class="brand-text font-weight-light">威四方</span>
        </a>

        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                {!! empty($top_nav_string)?'':$top_nav_string !!}
            </ul>

            <!-- SEARCH FORM -->
            <form class="form-inline ml-0 ml-md-3  unbind-form nav-search-form" action="/docs/search"  method="get">
                <div class="input-group input-group-sm">
                    <input class="form-control form-control-navbar" type="search" placeholder="搜索..."  name="keyword" value=""  aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-navbar" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Right navbar links -->
        <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">

            <li class="nav-item dropdown">
                <!-- <div class=" d-inline-block user-dropdown"> -->
                <a class="nav-link user-dropdown-link" data-toggle="dropdown" href="#" aria-expanded="false">
                    <img class="elevation-3 img-circle" src="{{ asset('static/inspinia/img/logo.png') }}" alt="Header Avatar" style="padding: 3px;height: 33px;margin-top: -6px;">
                    <span class="brand-text font-weight-light header-user-name">登录</span>
                    {{-- <span class="badge badge-warning navbar-badge user-logo-badge">15</span>--}}
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-user" style="width:230px;">
                    <div class="card card-user">
                        <div class="card-body">
                            <div class="text-center">
                                <img src="{{ asset('static/inspinia/img/logo.png') }}" alt="" class="avatar-logo avatar-sm" style="width: 70px;">
                                <div class="media-body">
                                    <!-- <h5 class="text-truncate"><a href="#" class="text-dark">请登录</a></h5> -->
                                    <p class="text-muted">
                                        <i class="mdi mdi-account mr-1"></i> 请登录
                                    </p>
                                </div>
                            </div>

                            <hr class="my-1">

                            <div class="row text-center" style="padding: 15px 0 0;">
                                <a href="/index/qqlogin" class="col-6">
                                    <div style="padding-top:5px;">
                                        <h5><i class="icon fa fa-qq fa-lg mr-2 ri-qq-line"></i></h5>
                                        <p class="text-muted mb-2 font-size-12">QQ登录</p>
                                    </div>
                                </a>
                                <a href="/index/qqlogin" class="col-6">
                                    <div style="padding-top:5px;">
                                        <h5><i class="icon fa fa-weibo fa-lg mr-2 ri-weibo-fill"></i></h5>
                                        <p class="text-muted mb-2 font-size-12">微博登录</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- item-->
                    <!-- <a class="dropdown-item" href="#"><i class="ri-user-line align-middle mr-1"></i> Profile</a>
                    <a class="dropdown-item" href="#"><i class="ri-wallet-2-line align-middle mr-1"></i> My Wallet</a>
                    <a class="dropdown-item d-block" href="#"><span class="badge badge-success float-right mt-1">11</span><i class="ri-settings-2-line align-middle mr-1"></i> Settings</a>
                    <a class="dropdown-item" href="#"><i class="ri-lock-unlock-line align-middle mr-1"></i> Lock screen</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="#"><i class="ri-shut-down-line align-middle mr-1 text-danger"></i> 退出</a> -->
                </div>
                <!-- </div> -->
            </li>
        </ul>
    </div>
</nav>