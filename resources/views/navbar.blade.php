<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">
            @auth
                @if (auth()->user()->isRole('admin'))
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ url('/') }}">List User <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('role.index') }}">List Role</a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-success" href="{{ route('messenger.notify') }}" style="color: white">
                        Tin nhắn của bạn: {{ $messAll }}
                    </a>
                </li>
            @endauth
        </ul>
        <span class="navbar-text">
            @if(auth()->check())
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ auth()->user()->name }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="{{ route('user.show', ['user' => auth()->user()->id]) }}">Thông tin tài khoản</a>
                            <a class="dropdown-item" href="{{ route('logout') }}">Đăng xuất</a>
                        </div>
                    </li>
                </ul>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">Đăng nhập</a>
                <a href="{{ route('register.index') }}" class="btn btn-primary">Đăng ký</a>
            @endif
        </span>
    </div>
</nav>