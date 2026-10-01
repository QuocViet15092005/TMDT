@extends('layouts.app')

@section('title', 'Đặt hàng thành công')

@section('content')

<div class="container section-space">

    <div class="alert alert-success">
        <h2>Đặt hàng thành công!</h2>

        <p>
            Cảm ơn bạn đã mua hàng tại SPORT SHOP.
        </p>
    </div>


    {{-- THÔNG TIN ĐƠN HÀNG --}}
    <div class="card mb-4">

        <div class="card-header">
            <h3>Thông tin đơn hàng</h3>
        </div>

        <div class="card-body">

            <p>
                <strong>Mã đơn hàng:</strong>
                #{{ $order->id }}
            </p>

            <p>
                <strong>Khách hàng:</strong>
                {{ $order->customer_name }}
            </p>

            <p>
                <strong>Số điện thoại:</strong>
                {{ $order->customer_phone }}
            </p>

            @if($order->customer_email)
                <p>
                    <strong>Email:</strong>
                    {{ $order->customer_email }}
                </p>
            @endif

            <p>
                <strong>Địa chỉ giao hàng:</strong>
                {{ $order->shipping_address }}
            </p>


            <p>
                <strong>Phương thức thanh toán:</strong>

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
                        Thanh toán thất bại
                    </span>

                @else

                    <span class="badge bg-warning">
                        Chưa thanh toán
                    </span>

                @endif
            </p>


            <p>
                <strong>Trạng thái đơn:</strong>

                {{ $order->order_status }}
            </p>

        </div>

    </div>


    {{-- CHI TIẾT SẢN PHẨM --}}
    <div class="card mb-4">

        <div class="card-header">

            <h3>
                Sản phẩm đã đặt
            </h3>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered">

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
                                {{ $detail->product_name }}
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

    </div>


    {{-- TỔNG TIỀN --}}
    <div class="text-end">

        <h2>

            Tổng thanh toán:

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
            href="{{ route('products.index') }}"
            class="btn btn-primary"
        >
            Tiếp tục mua sắm
        </a>


        @auth

            <a
                href="{{ route('orders.show', $order) }}"
                class="btn btn-secondary"
            >
                Xem chi tiết đơn hàng
            </a>

        @endauth

    </div>

</div>

@endsection