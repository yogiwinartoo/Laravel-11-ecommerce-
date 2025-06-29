<ul class="account-nav">
    <li><a href="{{ route('user.index') }}" class="menu-link menu-link_us-s">Dashboard</a></li>
    <li><a href="{{ route('user.orders') }}" class="menu-link menu-link_us-s">Pesanan</a></li>
    <li><a href="account-address.html" class="menu-link menu-link_us-s">Alamat</a></li>
    <li><a href="account-details.html" class="menu-link menu-link_us-s">Detail Akun</a></li>
    <li><a href="account-wishlist.html" class="menu-link menu-link_us-s">Wishlist</a></li>

    {{-- Logout yang benar --}}
    <li>
        <form method="POST" action="{{ route('logout') }}" id="logout-form">
            @csrf
            <a href="{{ route('logout') }}" class="menu-link menu-link_us-s" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Keluar
            </a>
        </form>
    </li>
</ul>
