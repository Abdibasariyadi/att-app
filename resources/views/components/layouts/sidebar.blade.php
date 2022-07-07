<div>
    <div class="main-sidebar">
        <aside id="sidebar-wrapper">
            <div class="sidebar-brand">
                <a href="index.html">teamAPP</a>
            </div>
            <div class="sidebar-brand sidebar-brand-sm">
                <a href="index.html">St</a>
            </div>
            <ul class="sidebar-menu">

                @foreach($navigations as $nav)
                @can($nav->permission_name)
                <li class="menu-header" style="margin-bottom: -10px;">{{ $nav->name }}</li>

                @php
                $str = '';
                if (count(Request::segments()) == 2) {
                $str = Request::segment(1) . '/' . Request::segment(2);
                } elseif (count(Request::segments()) == 1) {
                $str = Request::segment(1);
                } elseif (count(Request::segments()) == 3) {
                $str = Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3);
                }

                @endphp

                @foreach($nav->children as $child)

                @if($str == $child->url)
                <li class="active">
                    @else
                <li>
                    @endif
                    <a class="nav-link" href="{{ url($child->url) }}" style="margin-bottom: -10px;">
                        <i class="{{ $child->icon }}"></i>
                        <span>{{ $child->name }}</span>
                    </a>
                </li>
                @endforeach

                @endcan
                @endforeach

        </aside>

    </div>
</div>
