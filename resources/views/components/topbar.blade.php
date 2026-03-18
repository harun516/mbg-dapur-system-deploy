<nav class="navbar bg-white border-bottom px-3 px-md-4 py-0 shadow-sm" style="height:60px; position:sticky; top:0; z-index:999;">
    <!-- Hamburger Toggle - Bootstrap Offcanvas -->
    <button class="btn btn-sm btn-outline-secondary rounded-2 me-3 d-flex align-items-center justify-content-center"
            style="width:38px;height:38px;"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#sidebarOffcanvas"
            aria-controls="sidebarOffcanvas"
            title="Toggle Sidebar">
        <i class="fas fa-bars fs-6"></i>
    </button>

    <!-- Page Title -->
    <span class="fw-semibold text-dark d-none d-md-inline" style="font-size:0.95rem;">
        {{ config('app.name', 'DAPURKU') }}
    </span>

    <!-- Right side -->
    <div class="ms-auto d-flex align-items-center gap-2">
        <!-- User info -->
        <span class="text-muted small fw-semibold d-none d-sm-inline">{{ auth()->user()->name }}</span>
        <span class="badge rounded-pill px-3 py-1"
              style="background: linear-gradient(135deg,#2563eb,#1e40af); font-size:0.7rem; letter-spacing:0.5px;">
            {{ strtoupper(auth()->user()->role ?? 'User') }}
        </span>

        <!-- User Dropdown -->
        <div class="dropdown ms-1">
            <button class="btn btn-sm btn-light rounded-circle d-flex align-items-center justify-content-center p-0"
                    style="width:36px;height:36px;"
                    data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user-circle fs-5 text-secondary"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-1" style="min-width:200px;">
                <li class="px-3 py-2 border-bottom">
                    <div class="fw-bold text-dark small">{{ auth()->user()->name }}</div>
                    <div class="text-muted" style="font-size:0.75rem;">{{ auth()->user()->email }}</div>
                </li>
                <li>
                    <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                        <i class="fas fa-user-cog fa-sm me-2 text-primary"></i> Profile
                    </a>
                </li>
                <li><hr class="dropdown-divider my-1"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 text-danger">
                            <i class="fas fa-sign-out-alt fa-sm me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
