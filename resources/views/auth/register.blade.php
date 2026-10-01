@extends('layouts.app')

@section('title', 'Đăng ký tài khoản mới')

@section('content')
<div class="container section-space">
    <div class="auth-box">
        <div style="text-align: center; margin-bottom: 20px;">
            <div style="width: 56px; height: 56px; background: #ecfdf5; color: #10b981; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 1.6rem; margin-bottom: 12px;">
                ✨
            </div>
            <h1>Đăng ký tài khoản</h1>
            <p class="auth-subtitle">Tạo tài khoản để nhận nhiều ưu đãi độc quyền và theo dõi đơn hàng dễ dàng</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin-left: 18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label>Họ và tên của bạn <span style="color: #ef4444;">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Ví dụ: Nguyễn Văn A" required autofocus>
            </div>

            <div class="form-group">
                <label>Địa chỉ Email <span style="color: #ef4444;">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="example@email.com" required>
            </div>

            <div class="form-group">
                <label>Mật khẩu <span style="color: #ef4444;">*</span></label>
                <input type="password" name="password" placeholder="Tối thiểu 6 ký tự" required>
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label>Xác nhận lại mật khẩu <span style="color: #ef4444;">*</span></label>
                <input type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu ở trên" required>
            </div>

            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-bottom: 16px;">
                🎉 Đăng ký tài khoản ngay
            </button>
        </form>

        <div style="text-align: center; font-size: 0.9rem; color: var(--slate-500); padding-top: 16px; border-top: 1px dashed var(--border-color);">
            Đã có tài khoản thành viên? 
            <a href="{{ route('login.form') }}" style="color: var(--primary-color); font-weight: 700;">
                Đăng nhập ngay &rarr;
            </a>
        </div>
    </div>
</div>
@endsection

