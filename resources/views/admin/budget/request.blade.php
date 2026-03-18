@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-hand-holding-usd me-2"></i>Daftar Permintaan Saldo
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Peminta</th>
                                    <th>Perihal</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests as $item)
                                <tr>
                                    <td class="small">{{ $item->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2 bg-primary-subtle text-primary rounded-circle p-2 text-center" style="width: 35px; height: 35px; line-height: 20px;">
                                                <i class="fas fa-user-circle"></i>
                                            </div>
                                            <div>
                                                <span class="fw-bold d-block">{{ $item->user->name }}</span>
                                                <small class="text-muted text-uppercase" style="font-size: 0.7rem;">{{ $item->user->role }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="d-block fw-semibold">{{ $item->perihal }}</span>
                                        @if($item->catatan)
                                            <small class="text-muted">{{ Str::limit($item->catatan, 50) }}</small>
                                        @endif
                                    </td>
                                    <td class="fw-bold text-dark">
                                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @if($item->status == 'pending')
                                            <span class="badge rounded-pill bg-warning text-dark">Menunggu</span>
                                        @elseif($item->status == 'approved')
                                            <span class="badge rounded-pill bg-success text-white">Disetujui</span>
                                        @else
                                            <span class="badge rounded-pill bg-danger text-white">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($item->status == 'pending')
                                            <div class="d-flex justify-content-center gap-2">
                                                <form action="{{ route('admin.budget.approve', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3" onclick="return confirm('Setujui permintaan dana ini?')">
                                                    <i class="fas fa-check me-1"></i> Setuju
                                                </button>
                                            </form>

                                                <button class="btn btn-sm btn-outline-danger rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $item->id }}">
                                                    <i class="fas fa-times me-1"></i> Tolak
                                                </button>
                                            </div>
                                        @else
                                            <button class="btn btn-sm btn-light disabled rounded-pill">Selesai</button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                        Belum ada permintaan saldo masuk.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection