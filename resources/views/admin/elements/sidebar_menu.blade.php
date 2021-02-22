<!-- menu profile quick info -->
<div class="profile clearfix">
    <div class="profile_pic">
        <img src="{{ asset('admin/img/img.jpg') }}" alt="..." class="img-circle profile_img">
    </div>
    <div class="profile_info">
        <span>Welcome,</span>
        <h2>luutruonghailan</h2>
    </div>
</div>
<!-- /menu profile quick info -->
<br/>
<!-- sidebar menu -->
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
        <h3>Menu</h3>
        <ul class="nav side-menu">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
            <li><a href="{{ route('user') }}"><i class="fa fa-user"></i> User</a></li>
            <li><a href="{{ route('category') }}"><i class="fa fa fa-building-o"></i> Category</a></li>
            <li><a href="{{ route('article') }}"><i class="fa fa-newspaper-o"></i> Article</a></li>
            <li><a href="{{ route('slider') }}"><i class="fa fa-sliders"></i> Silders</a></li>
            <li><a href="{{ route('rss') }}"><i class="fa fa-rss"></i> RSS</a></li>
            <li><a href="{{ route('menu') }}"><i class="fa fa-sitemap"></i> Menu</a></li>
            <li><a href="{{ route('contact') }}"><i class="fa fa-paper-plane"></i> Liên hệ</a></li>
            <li><a href="{{ route('gallery') }}"><i class="fa fa-file-image-o"></i> Gallery</a></li>
            <li>
                <a><i class="fa fa-cog"></i> Cấu hình <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{ route('setting', ['type' => 'general']) }}"></i>Cấu hình chung</a></li>
                    <li><a href="{{ route('setting', ['type' => 'email']) }}"></i>Email</a></li>
                    <li><a href="{{ route('setting', ['type' => 'social']) }}"></i>Social</a></li>
                </ul>
            </li>
            <li><a href="{{ route('user/change-logged-password') }}"><i class="fa fa-refresh"></i> Change Password</a></li>
        </ul>
    </div>
</div>
<!-- /sidebar menu -->
