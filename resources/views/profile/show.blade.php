@extends('layouts.app')

@section('title', 'Thông tin tài khoản')

@section('content')
<div class="container section-space">
    <div class="auth-box" style="max-width: 650px;">
        <h1 class="section-title" style="margin-bottom: 8px;">Thông tin tài khoản</h1>
        <p class="text-muted" style="margin-bottom: 24px;">Quản lý thông tin cá nhân và bảo mật tài khoản.</p>

        @if(session('success'))
            <div class="alert alert-success">
                ✓ {{ session('success') }}
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header">
                <h3>Hồ sơ cá nhân</h3>
            </div>
            <div class="card-body">
                <div class="info-block mb-3">
                    <div class="info-label">Họ và tên</div>
                    <div class="info-value font-bold" style="font-size: 1.1rem;">{{ $user->name }}</div>
                </div>

                <div class="info-block mb-3">
                    <div class="info-label">Địa chỉ Email</div>
                    <div class="info-value">{{ $user->email }}</div>
                </div>

                <div class="info-block mb-3">
                    <div class="info-label">Vai trò</div>
                    <div class="info-value">
                        @if($user->role === 'admin')
                            <span class="badge badge-primary">Quản trị viên (Admin)</span>
                        @else
                            <span class="badge badge-info">Khách hàng thành viên</span>
                        @endif
                    </div>
                </div>

                <div class="info-block">
                    <div class="info-label">Ngày tham gia</div>
                    <div class="info-value">{{ $user->created_at?->format('d/m/Y') }}</div>
                </div>
            </div>
            <div class="card-footer" style="padding: 16px 20px; background: #f8fafc; border-top: 1px solid var(--border-color); display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">Chỉnh sửa thông tin</a>
                <a href="{{ route('profile.password') }}" class="btn btn-secondary">Đổi mật khẩu</a>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Xem đơn mua</a>
            </div>
        </div>
    </div>
</div>
@endsection
