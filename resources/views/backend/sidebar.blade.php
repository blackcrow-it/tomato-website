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
    <a href="{{ route('admin.category.list') }}" class="nav-link {{ request()->routeIs('admin.category.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-list"></i>
        <p>Danh mục</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.recharge.list') }}" class="nav-link {{ request()->routeIs('admin.recharge.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-money-check-alt"></i>
        <p>Nạp tiền</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.user.list') }}" class="nav-link {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-friends"></i>
        <p>Thành viên</p>
    </a>
</li>
