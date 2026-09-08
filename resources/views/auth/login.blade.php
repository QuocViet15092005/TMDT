@extends('layouts.app')

@section('title', 'Đăng nhập tài khoản')

@section('content')
<div class="container section-space">
    <div class="auth-box">
        <div style="text-align: center; margin-bottom: 20px;">
            <div style="width: 56px; height: 56px; background: var(--primary-light); color: var(--primary-color); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 1.6rem; margin-bottom: 12px;">
                🔑
            </div>
            <h1>Đăng nhập</h1>
            <p class="auth-subtitle">Chào mừng bạn quay lại với hệ thống Sport Shop!</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin-left: 18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label>Địa chỉ Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="example@email.com" required autofocus>
            </div>
            
            <div class="form-group" style="margin-bottom: 24px;">
                <label>Mật khẩu</label>
                <input type="password" name="password" placeholder="Nhập mật khẩu của bạn" required>
            </div>

            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-bottom: 16px;">
                🚀 Đăng nhập ngay
            </button>
        </form>

        <div style="text-align: center; font-size: 0.9rem; color: var(--slate-500); padding-top: 16px; border-top: 1px dashed var(--border-color);">
            Chưa có tài khoản thành viên? 
            <a href="{{ route('register.form') }}" style="color: var(--primary-color); font-weight: 700;">
                Đăng ký tài khoản mới &rarr;
            </a>
        </div>
    </div>
</div>
@endsection

