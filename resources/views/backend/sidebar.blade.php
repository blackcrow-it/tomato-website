<!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
<li class="nav-item">
    <a href="{{ route('admin.home') }}" class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>Trang chủ</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.course.list') }}" class="nav-link {{ request()->routeIs('admin.course.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-graduation-cap"></i>
        <p>Khóa học</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.post.list') }}" class="nav-link {{ request()->routeIs('admin.post.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-newspaper"></i>
        <p>Bài viết</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.book.list') }}" class="nav-link {{ request()->routeIs('admin.book.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-book"></i>
        <p>Sách</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.category.list') }}" class="nav-link {{ request()->routeIs('admin.category.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-list"></i>
        <p>Danh mục</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.teacher.list') }}" class="nav-link {{ request()->routeIs('admin.teacher.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-chalkboard-teacher"></i>
        <p>Giảng viên</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.recharge.list') }}" class="nav-link {{ request()->routeIs('admin.recharge.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-money-check-alt"></i>
        <p>Nạp tiền</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.invoice.list') }}" class="nav-link {{ request()->routeIs('admin.invoice.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-shopping-cart"></i>
        <p>Đơn hàng</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.user.list') }}" class="nav-link {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-friends"></i>
        <p>Thành viên</p>
    </a>
</li>
<li class="nav-item has-treeview {{ request()->routeIs('admin.setting.*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->routeIs('admin.setting.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-cogs"></i>
        <p>
            Cài đặt
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.setting.edit', [ 'view' => 'homepage' ]) }}" class="nav-link {{ request()->routeIs('admin.setting.edit') && request()->route('view') == 'homepage' ? 'active' : '' }}">
                <i class="nav-icon fas fa-home"></i>
                <p>Trang chủ</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.setting.edit', [ 'view' => 'info' ]) }}" class="nav-link {{ request()->routeIs('admin.setting.edit') && request()->route('view') == 'info' ? 'active' : '' }}">
                <i class="nav-icon fas fa-info-circle"></i>
                <p>Thông tin</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.setting.edit', [ 'view' => 'recharge' ]) }}" class="nav-link {{ request()->routeIs('admin.setting.edit') && request()->route('view') == 'recharge' ? 'active' : '' }}">
                <i class="nav-icon fas fa-money-check-alt"></i>
                <p>Nạp tiền</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.setting.edit', [ 'view' => 'drive' ]) }}" class="nav-link {{ request()->routeIs('admin.setting.edit') && request()->route('view') == 'drive' ? 'active' : '' }}">
                <i class="nav-icon fab fa-google-drive"></i>
                <p>Google drive</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.setting.edit', [ 'view' => 'email_notification' ]) }}" class="nav-link {{ request()->routeIs('admin.setting.edit') && request()->route('view') == 'email_notification' ? 'active' : '' }}">
                <i class="nav-icon far fa-bell"></i>
                <p>Thông báo qua email</p>
            </a>
        </li>
    </ul>
</li>
