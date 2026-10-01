@extends('layouts.app')

@section('title', 'Quản lý đơn hàng')

@section('content')

<div class="container section-space">

    <h1 class="section-title">
        Quản lý đơn hàng
    </h1>


    {{-- THÔNG BÁO --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif


    {{-- FILTER TABS --}}
    <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 20px;">
        <a href="{{ route('admin.orders.index') }}" class="btn {{ !request('status') ? 'btn-primary' : 'btn-secondary' }}" style="padding: 6px 14px; font-size: 0.9rem;">
            Tất cả ({{ \App\Models\Order::count() }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn {{ request('status') === 'pending' ? 'btn-primary' : 'btn-secondary' }}" style="padding: 6px 14px; font-size: 0.9rem;">
            🕒 Chờ xác nhận ({{ \App\Models\Order::where('order_status', 'pending')->count() }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}" class="btn {{ request('status') === 'confirmed' ? 'btn-primary' : 'btn-secondary' }}" style="padding: 6px 14px; font-size: 0.9rem;">
            ✔️ Đã xác nhận ({{ \App\Models\Order::where('order_status', 'confirmed')->count() }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'shipping']) }}" class="btn {{ request('status') === 'shipping' ? 'btn-primary' : 'btn-secondary' }}" style="padding: 6px 14px; font-size: 0.9rem;">
            🚚 Đang giao ({{ \App\Models\Order::where('order_status', 'shipping')->count() }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="btn {{ request('status') === 'completed' ? 'btn-primary' : 'btn-secondary' }}" style="padding: 6px 14px; font-size: 0.9rem;">
            ✅ Hoàn thành ({{ \App\Models\Order::where('order_status', 'completed')->count() }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="btn {{ request('status') === 'cancelled' ? 'btn-primary' : 'btn-secondary' }}" style="padding: 6px 14px; font-size: 0.9rem;">
            ❌ Đã hủy ({{ \App\Models\Order::where('order_status', 'cancelled')->count() }})
        </a>
    </div>

    {{-- KHÔNG CÓ ĐƠN --}}
    @if($orders->isEmpty())

        <div class="alert alert-info">
            Hiện chưa có đơn hàng nào.
        </div>

    @else

        <div class="table-responsive">

            <table class="data-table">

                <thead>

                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Phương thức</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái đơn</th>
                        <th>Ngày đặt</th>
                        <th>Hành động</th>
                    </tr>

                </thead>


                <tbody>

                @foreach($orders as $order)

                    <tr>

                        {{-- ID --}}
                        <td>
                            #{{ $order->id }}
                        </td>


                        {{-- CUSTOMER --}}
                        <td>

                            <strong>
                                {{ $order->customer_name }}
                            </strong>

                            <br>

                            <small>
                                {{ $order->customer_phone }}
                            </small>

                        </td>


                        {{-- TOTAL --}}
                        <td>

                            <strong>

                                {{ number_format(
                                    $order->total_amount,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                                VNĐ

                            </strong>

                        </td>


                        {{-- PAYMENT METHOD --}}
                        <td>

                            @if($order->payment_method === 'cod')

                                COD

                            @elseif($order->payment_method === 'qr')

                                QR

                            @else

                                {{ $order->payment_method }}

                            @endif

                        </td>


                        {{-- PAYMENT STATUS --}}
                        <td>

                            @if($order->payment_status === 'paid')

                                <span class="badge bg-success">
                                    Đã thanh toán
                                </span>

                            @elseif($order->payment_status === 'failed')

                                <span class="badge bg-danger">
                                    Thất bại
                                </span>

                            @else

                                <span class="badge bg-warning">
                                    Chưa thanh toán
                                </span>

                            @endif

                        </td>


                        {{-- ORDER STATUS --}}
                        <td>

                            @switch($order->order_status)

                                @case('pending')

                                    <span class="badge bg-warning">
                                        Chờ xác nhận
                                    </span>

                                    @break


                                @case('confirmed')

                                    <span class="badge bg-primary">
                                        Đã xác nhận
                                    </span>

                                    @break


                                @case('shipping')

                                    <span class="badge bg-info">
                                        Đang giao
                                    </span>

                                    @break


                                @case('completed')

                                    <span class="badge bg-success">
                                        Hoàn thành
                                    </span>

                                    @break


                                @case('cancelled')

                                    <span class="badge bg-danger">
                                        Đã hủy
                                    </span>

                                    @break


                                @default

                                    {{ $order->order_status }}

                            @endswitch

                        </td>


                        {{-- CREATED --}}
                        <td>

                            {{ $order->created_at?->format('d/m/Y H:i') }}

                        </td>


                        {{-- ACTION --}}
                        <td>

                            <a
                                href="{{ route(
                                    'admin.orders.show',
                                    $order
                                ) }}"
                                class="btn btn-secondary"
                            >
                                Chi tiết
                            </a>

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        <div class="mt-4">

            {{ $orders->links() }}

        </div>

    @endif

</div>

@endsection