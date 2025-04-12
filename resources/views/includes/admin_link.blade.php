<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <!-- <img src="{{ config('app.logo') }}" alt="{{ config('app.name') }}" class="logo-icon" alt="logo icon"> -->
        </div>
        <div>
            <h4 class="logo-text">{{ config('app.name') }}</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-chevron-left'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu mm-show" id="menu">
        {{-- Get the menus --}}
        @php
        // Get the menus of the current logged in user
        $menus = MenuHelper::getMenus()->toArray();
        $sub_menus = MenuHelper::getSubMenus()->toArray();
        @endphp

        {{-- Loop through all the menus --}}
        @foreach ($menus as $menu)
        @php
        $menus_sub_menus = array_filter($sub_menus, function ($item) use ($menu) {
        return $item['menu_id'] == $menu['id'];
        });

        @endphp

        <li class="{{ MenuHelper::setMenuOpen($menus_sub_menus) }}">

            <a href="{{ isset($menu['resource_link']) ? url()->to($menu['resource_link']) : '#' }}" class=" @if (count($menus_sub_menus) > 0) has-arrow @endif" class="nav-link {{ MenuHelper::setMenuActive($menu['resource_link']) }}">
                <div class="parent-icon"><i class='{{ $menu["icon_class"] }}'></i>
                </div>
                <div class="menu-title">{{ $menu['menu_name'] }}</div>

            </a>

            {{-- Check if the menu has sub menu --}}
            @if (count($menus_sub_menus) > 0)
            <ul class="mm-collapse">
                {{-- Loop through all the menu's sub menu --}}
                @foreach ($menus_sub_menus as $sub_menu)
                <li>
                    <a href="{{ isset($sub_menu['resource_link']) ? url()->to($sub_menu['resource_link']) : '#' }}" class="{{ MenuHelper::setMenuActive($sub_menu['resource_link']) }}">
                        <i class="bx bx-radio-circle"></i>
                        {{ $sub_menu['sub_menu_name'] }}

                    </a>
                </li>
                @endforeach

            </ul>
            @endif
        </li>
        @endforeach
        <li>
            <a href="/profile" class="{{ MenuHelper::setMenuActive('/profile') }}">
                <div class="parent-icon">
                    <i class="bx bx-user"></i>
                </div>
                <div class="menu-title">Profile</div>
            </a>
        </li>
    </ul>
    <!--end navigation-->
</div>