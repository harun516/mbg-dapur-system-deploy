<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow-sm">
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle me-3">
        <i class="fa fa-bars"></i>
    </button>

    <div class="d-flex align-items-center ms-auto">
        <span class="text-gray-800 small fw-bold me-2">{{ auth()->user()->name }}</span>
        <span class="badge bg-primary rounded-pill px-3 py-1">{{ strtoupper(auth()->user()->role ?? 'User') }}</span>
    </div>

    <ul class="navbar-nav ms-3">
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user-circle fa-lg text-gray-600"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user fa-sm fa-fw me-2"></i> Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>