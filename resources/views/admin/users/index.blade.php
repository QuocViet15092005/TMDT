@extends('layouts.app')

@section('title', 'Quản lý khách hàng')

@section('content')
<div class="container section-space">
    <div class="orders-page-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <h1 class="section-title" style="margin-bottom: 4px;">👥 Quản lý khách hàng</h1>
            <p class="section-subtitle" style="margin: 0;">Danh sách thành viên và khách hàng mua sắm tại cửa hàng</p>
        </div>
        <div>
            <form action="{{ route('admin.users.index') }}" method="GET" style="display: flex; gap: 8px;">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Tìm theo tên, email..." 
                    class="form-control" 
                    style="padding: 8px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 0.9rem;"
                >
                <button type="submit" class="btn btn-primary" style="padding: 8px 16px;">Tìm kiếm</button>
                @if(request('search'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary" style="padding: 8px 14px;">Xóa lọc</a>
                @endif
            </form>
        </div>
    </div>

    @if($users->isEmpty())
        <div class="alert alert-info">
            Không tìm thấy khách hàng nào.
        </div>
    @else
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ và tên</th>
                        <th>Email</th>
                        <th>Số đơn hàng đã đặt</th>
                        <th>Ngày đăng ký</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>#{{ $user->id }}</td>
                            <td>
                                <strong>{{ $user->name }}</strong>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <a href="{{ route('admin.orders.index') }}" class="badge bg-primary" style="text-decoration: none;">
                                    {{ $user->orders_count }} đơn hàng
                                </a>
                            </td>
                            <td>{{ $user->created_at?->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4" style="margin-top: 20px;">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
