@extends('layouts.app')

@section('title', 'Đổi mật khẩu')

@section('content')
<div class="container section-space">
    <div class="auth-box" style="max-width: 600px;">
        <h1 class="section-title" style="margin-bottom: 24px;">Đổi mật khẩu</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <p class="m-0">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('profile.password.update') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="current_password" class="font-bold">Mật khẩu hiện tại</label>
                        <input type="password" id="current_password" name="current_password" required placeholder="Nhập mật khẩu hiện tại...">
                    </div>

                    <div class="form-group mb-3">
                        <label for="password" class="font-bold">Mật khẩu mới</label>
                        <input type="password" id="password" name="password" required placeholder="Tối thiểu 6 ký tự...">
                    </div>

                    <div class="form-group mb-4">
                        <label for="password_confirmation" class="font-bold">Xác nhận mật khẩu mới</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Nhập lại mật khẩu mới...">
                    </div>

                    <div style="display: flex; gap: 12px;">
                        <button type="submit" class="btn btn-primary">Cập nhật mật khẩu</button>
                        <a href="{{ route('profile.show') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
