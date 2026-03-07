<nav class="bg-white text-dark vh-100 position-fixed top-0 start-0 shadow-sm border-end border-light" style="width: 260px; z-index: 1000; overflow-y: auto; transition: all 0.3s ease;" id="sidebar">
    <div class="p-4 text-center border-bottom border-light">
        <h4 class="mb-1 fw-bold text-primary">MBG DAPUR 01</h4>
        <small class="text-muted">Sistem Terintegrasi</small>
    </div>

    <div class="p-3">
        <ul class="nav flex-column gap-1">
            <!-- <li class="nav-item">
                <a class="nav-link text-dark d-flex align-items-center py-2 px-3 rounded {{ request()->routeIs('dashboard') ? 'active bg-primary-subtle text-primary' : 'hover-bg-light' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt me-3 fs-5"></i> Dashboard
                </a>
            </li> -->
        <!====================================== ROLE GUDANG =====================================>
            @if(in_array(auth()->user()->role, ['gudang']))
                <div class="text-muted small fw-bold text-uppercase mt-3 mb-2 px-3" style="font-size: 0.7rem; letter-spacing: 0.5px;">Manajemen Gudang</div>
                <li class="nav-item">
                <a class="nav-link text-dark d-flex align-items-center py-2 px-3 rounded {{ request()->routeIs('dashboard') ? 'active bg-primary-subtle text-primary' : 'hover-bg-light' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt me-3 fs-5"></i> Dashboard
                </a>

                <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center py-2 px-3 rounded {{ request()->routeIs('gudang.saldo.*') ? 'active bg-primary-subtle text-primary' : 'hover-bg-light' }}" href="{{ route('gudang.saldo.index') }}">
                        <i class="fas fa-credit-card me-3 fs-5"></i> Saldo Anggaran
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center py-2 px-3 rounded {{ request()->routeIs('item.*') ? 'active bg-primary-subtle text-primary' : 'hover-bg-light' }}" href="{{ route('item.index') }}">
                        <i class="fas fa-boxes me-3 fs-5"></i> Master Barang
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center py-2 px-3 rounded {{ request()->routeIs('stok.index') ? 'active bg-primary-subtle text-primary' : 'hover-bg-light' }}" href="{{ route('stok.index') }}">
                        <i class="fas fa-layer-group me-3 fs-5"></i> Stok Gudang
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center py-2 px-3 rounded {{ request()->routeIs('penerimaan.*') ? 'active bg-primary-subtle text-primary' : 'hover-bg-light' }}" href="{{ route('penerimaan.index') }}">
                        <i class="fas fa-truck-loading me-3 fs-5"></i> Penerimaan
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center py-2 px-3 rounded {{ request()->routeIs('gudang.request.*') ? 'active bg-primary-subtle text-primary' : 'hover-bg-light' }}" href="{{ route('gudang.request.index') }}">
                        <i class="fas fa-clipboard-list me-3 fs-5"></i> Permintaan Dapur
                    </a>
                </li>
            @endif
        <!====================================== ROLE DAPUR =====================================>

            @if(in_array(auth()->user()->role, ['dapur']))
                <div class="text-muted small fw-bold text-uppercase mt-3 mb-2 px-3" style="font-size: 0.7rem; letter-spacing: 0.5px;">Operasional Dapur</div>
                <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center py-2 px-3 rounded {{ request()->routeIs('dapur.dashboard') ? 'active bg-primary-subtle text-primary' : 'hover-bg-light' }}" href="{{ route('dapur.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-3 fs-5"></i> Dashboard Dapur
                    </a>
                 <div class="text-muted small fw-bold text-uppercase mt-3 mb-2 px-3" style="font-size: 0.7rem; letter-spacing: 0.5px;">Managemen Dapur</div>
                <li class="nav-item">
                </li>
                
                 <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center py-2 px-3 rounded {{ request()->routeIs('production.*') ? 'active bg-primary-subtle text-primary' : 'hover-bg-light' }}" href="{{ route('production.index') }}">
                        <i class="fas fa-bar-chart me-3 fs-5"></i> Produksi
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center py-2 px-3 rounded {{ request()->routeIs('menu.*') ? 'active bg-primary-subtle text-primary' : 'hover-bg-light' }}" href="{{ route('menu.index') }}">
                        <i class="fas fa-truck-loading me-3 fs-5"></i> Menu
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center py-2 px-3 rounded {{ request()->routeIs('dapur.request.*') ? 'active bg-primary-subtle text-primary' : 'hover-bg-light' }}" href="{{ route('dapur.request.create') }}">
                        <i class="fas fa-clipboard-list me-3 fs-5"></i> Suplai Bahan
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center py-2 px-3 rounded {{ request()->routeIs('dapur.stok.*') ? 'active bg-primary-subtle text-primary' : 'hover-bg-light' }}" href="{{ route('dapur.stok.index') }}">
                        <i class="fas fa-boxes me-3 fs-5"></i> Stok Dapur
                    </a>
                </li>
            @endif
        <!====================================== ROLE KURIR =====================================>
            @if(in_array(auth()->user()->role, ['kurir']))
                <div class="text-muted small fw-bold text-uppercase mt-3 mb-2 px-3" style="font-size: 0.7rem; letter-spacing: 0.5px;">Logistik</div>
                <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center py-2 px-3 rounded {{ request()->routeIs('kurir.*') ? 'active bg-primary-subtle text-primary' : 'hover-bg-light' }}" href="{{ route('kurir.dashboard') }}">
                        <i class="fas fa-motorcycle me-3 fs-5"></i> Dashboard Kurir
                    </a>
                </li>
                
            @endif

        <!====================================== ROLE ADMIN =====================================>
            @if(in_array(auth()->user()->role, ['admin']))
                
            <div class="text-muted small fw-bold text-uppercase mt-3 mb-2 px-3" style="font-size: 0.7rem; letter-spacing: 0.5px;">Manajemen Admin</div>
                
            <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center py-2 px-3 rounded {{ request()->routeIs('admin.dashboard') ? 'active bg-primary-subtle text-primary' : 'hover-bg-light' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-3 fs-5"></i> Dashboard Admin
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center py-2 px-3 rounded {{ request()->routeIs('item.*') ? 'active bg-primary-subtle text-primary' : 'hover-bg-light' }}" href="{{ route('item.index') }}">
                        <i class="fas fa-users me-3 fs-5"></i> Kelola User
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center py-2 px-3 rounded {{ request()->routeIs('stok.index') ? 'active bg-primary-subtle text-primary' : 'hover-bg-light' }}" href="{{ route('stok.index') }}">
                        <i class="fas fa-bar-chart me-3 fs-5"></i> Laporan
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center py-2 px-3 rounded {{ request()->routeIs('penerimaan.*') ? 'active bg-primary-subtle text-primary' : 'hover-bg-light' }}" href="{{ route('penerimaan.index') }}">
                        <i class="fas fa-truck-loading me-3 fs-5"></i> Penerimaan
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center py-2 px-3 rounded {{ request()->routeIs('admin.salary.*') ? 'active bg-primary-subtle text-primary' : 'hover-bg-light' }}" href="{{ route('salary.index') }}">
                        <i class="fas fa-credit-card me-3 fs-5"></i> Salary Management
                    </a>
                </li>

                 <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center py-2 px-3 rounded {{ request()->routeIs('admin.budget.*') ? 'active bg-primary-subtle text-primary' : 'hover-bg-light' }}" href="{{ route('admin.budget.index') }}">
                        <i class="fas fa fa-balance-scale me-3 fs-5"></i> Budget Management
                    </a>
                </li>
            @endif

            <li class="nav-item mt-4 pt-4 border-top">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link text-danger d-flex align-items-center py-2 px-3 rounded w-100 text-start bg-transparent border-0 hover-bg-danger-subtle">
                        <i class="fas fa-sign-out-alt me-3 fs-5"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>