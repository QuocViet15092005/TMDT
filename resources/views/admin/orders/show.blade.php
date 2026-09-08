@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('content')

<div class="container section-space">

    <h1 class="section-title">
        Chi tiết đơn hàng #{{ $order->id }}
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


    {{-- ====================================== --}}
    {{-- THÔNG TIN KHÁCH HÀNG --}}
    {{-- ====================================== --}}

    <div class="order-box">

        <h3>Thông tin khách hàng</h3>

        <p>
            <strong>Khách hàng:</strong>
            {{ $order->customer_name }}
        </p>

        <p>
            <strong>Điện thoại:</strong>
            {{ $order->customer_phone }}
        </p>

        @if($order->customer_email)
            <p>
                <strong>Email:</strong>
                {{ $order->customer_email }}
            </p>
        @endif

        <p>
            <strong>Địa chỉ:</strong>
            {{ $order->shipping_address }}
        </p>

        <p>
            <strong>Ngày đặt:</strong>
            {{ $order->created_at?->format('d/m/Y H:i') }}
        </p>

    </div>


    {{-- ====================================== --}}
    {{-- THÔNG TIN THANH TOÁN --}}
    {{-- ====================================== --}}

    <div class="order-box">

        <h3>Thông tin thanh toán</h3>

        <p>
            <strong>Phương thức:</strong>

            @if($order->payment_method === 'cod')
                Thanh toán khi nhận hàng (COD)
            @else
                Thanh toán QR
            @endif
        </p>


        <p>
            <strong>Trạng thái thanh toán:</strong>

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
        </p>


        <p>
            <strong>Tổng tiền:</strong>

            <span class="text-danger">

                {{ number_format(
                    $order->total_amount,
                    0,
                    ',',
                    '.'
                ) }}

                VNĐ

            </span>

        </p>

    </div>


    {{-- ====================================== --}}
    {{-- CẬP NHẬT TRẠNG THÁI ĐƠN --}}
    {{-- ====================================== --}}

    <div class="order-box">

        <h3>Cập nhật trạng thái đơn hàng</h3>

        <form
            action="{{ route('admin.orders.update-status', $order) }}"
            method="POST"
            class="checkout-form"
        >

            @csrf
            @method('PATCH')

            <div class="form-group">

                <label>
                    Trạng thái đơn hàng
                </label>

                <select
                    name="order_status"
                    required
                >

                    <option
                        value="pending"
                        @selected(
                            $order->order_status === 'pending'
                        )
                    >
                        Chờ xử lý
                    </option>

                    <option
                        value="confirmed"
                        @selected(
                            $order->order_status === 'confirmed'
                        )
                    >
                        Đã xác nhận
                    </option>

                    <option
                        value="shipping"
                        @selected(
                            $order->order_status === 'shipping'
                        )
                    >
                        Đang giao
                    </option>

                    <option
                        value="completed"
                        @selected(
                            $order->order_status === 'completed'
                        )
                    >
                        Hoàn thành
                    </option>

                    <option
                        value="cancelled"
                        @selected(
                            $order->order_status === 'cancelled'
                        )
                    >
                        Đã hủy
                    </option>

                </select>

            </div>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Cập nhật trạng thái đơn
            </button>

        </form>

    </div>


    {{-- ====================================== --}}
    {{-- CẬP NHẬT THANH TOÁN --}}
    {{-- ====================================== --}}

    <div class="order-box">

        <h3>Cập nhật trạng thái thanh toán</h3>

        <form
            action="{{ route('admin.orders.update-payment', $order) }}"
            method="POST"
            class="checkout-form"
        >

            @csrf
            @method('PATCH')


            <div class="form-group">

                <label>
                    Trạng thái thanh toán
                </label>

                <select
                    name="payment_status"
                    required
                >

                    <option
                        value="unpaid"
                        @selected(
                            $order->payment_status === 'unpaid'
                        )
                    >
                        Chưa thanh toán
                    </option>


                    <option
                        value="paid"
                        @selected(
                            $order->payment_status === 'paid'
                        )
                    >
                        Đã thanh toán
                    </option>


                    <option
                        value="failed"
                        @selected(
                            $order->payment_status === 'failed'
                        )
                    >
                        Thanh toán thất bại
                    </option>

                </select>

            </div>


            <button
                type="submit"
                class="btn btn-success"
            >
                Cập nhật thanh toán
            </button>

        </form>

    </div>


    {{-- ====================================== --}}
    {{-- DANH SÁCH SẢN PHẨM --}}
    {{-- ====================================== --}}

    <div class="order-box">

        <h3>Sản phẩm trong đơn hàng</h3>


        <div class="table-responsive">

            <table class="data-table">

                <thead>

                    <tr>

                        <th>Sản phẩm</th>

                        <th>Size</th>

                        <th>Màu</th>

                        <th>Giá</th>

                        <th>Số lượng</th>

                        <th>Thành tiền</th>

                    </tr>

                </thead>


                <tbody>

                @foreach($order->details as $detail)

                    <tr>

                        <td>

                            <strong>
                                {{ $detail->product_name }}
                            </strong>

                        </td>


                        <td>

                            {{ $detail->size ?? 'Không có' }}

                        </td>


                        <td>

                            {{ $detail->color ?? 'Không có' }}

                        </td>


                        <td>

                            {{ number_format(
                                $detail->price,
                                0,
                                ',',
                                '.'
                            ) }}

                            VNĐ

                        </td>


                        <td>

                            {{ $detail->quantity }}

                        </td>


                        <td>

                            <strong>

                                {{ number_format(
                                    $detail->subtotal,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                                VNĐ

                            </strong>

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

    </div>


    {{-- TỔNG --}}
    <div class="text-end mt-4">

        <h2>

            Tổng đơn hàng:

            <span class="text-danger">

                {{ number_format(
                    $order->total_amount,
                    0,
                    ',',
                    '.'
                ) }}

                VNĐ

            </span>

        </h2>

    </div>


    <div class="mt-4">

        <a
            href="{{ route('admin.orders.index') }}"
            class="btn btn-secondary"
        >
            Quay lại danh sách đơn hàng
        </a>

    </div>

</div>

@endsection