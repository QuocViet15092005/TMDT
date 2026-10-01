@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container section-space">
    <div class="product-detail">
        {{-- HÌNH ẢNH SẢN PHẨM --}}
        <div class="detail-image-wrapper">
            <img 
                src="{{ $product->image ? asset('images/products/' . $product->image) : asset('images/no-image.jpg') }}" 
                alt="{{ $product->name }}" 
                class="detail-image"
            >
        </div>

        {{-- THÔNG TIN CHI TIẾT SẢN PHẨM --}}
        <div class="detail-content">
            @if(auth()->check() && auth()->user()->role === 'admin')
                <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: var(--radius-md); padding: 12px 16px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                    <span style="font-size: 0.88rem; color: #0369a1; font-weight: 700;">🛡️ Quản trị viên</span>
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.82rem;">
                            ✏️ Sửa sản phẩm
                        </a>
                        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.82rem;">
                            🎨 Quản lý Size
                        </a>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    ✓ {{ session('success') }}
                </div>
            @endif

            <span class="product-category-tag">
                {{ $product->category->name ?? 'Thể thao' }}
            </span>

            <h1 style="font-size: 2rem; margin: 6px 0 14px; font-weight: 800; letter-spacing: -0.02em;">
                {{ $product->name }}
            </h1>

            <div class="product-price" style="font-size: 1.8rem; margin-bottom: 20px;">
                {{ number_format($product->price, 0, ',', '.') }} <span style="font-size: 1rem; color: var(--slate-500); font-weight: 600;">VNĐ</span>
            </div>

            <p style="color: var(--slate-600); line-height: 1.7; margin-bottom: 24px; font-size: 0.98rem;">
                {{ $product->description ?: 'Sản phẩm thể thao cao cấp chính hãng từ Sport Shop, chất liệu co giãn thoáng khí, form dáng hiện đại, độ bền cao.' }}
            </p>
            
            <div style="background: var(--slate-100); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 18px 20px; margin-bottom: 24px; display: grid; gap: 10px;">
                <div style="display: flex; justify-content: space-between;">
                    <span class="text-muted">Danh mục:</span>
                    <strong style="color: var(--dark-color);">{{ $product->category->name ?? 'Chưa phân loại' }}</strong>
                </div>
                @if($product->brand)
                    <div style="display: flex; justify-content: space-between;">
                        <span class="text-muted">Thương hiệu:</span>
                        <strong style="color: var(--dark-color);">{{ $product->brand }}</strong>
                    </div>
                @endif
                @php
                    $commonColor = $product->variants->firstWhere('color', '!=', null)?->color;
                @endphp
                @if($commonColor)
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="text-muted">Màu sắc:</span>
                        <span class="badge badge-primary">🎨 {{ $commonColor }}</span>
                    </div>
                @endif
                <div style="display: flex; justify-content: space-between;">
                    <span class="text-muted">Tổng tồn kho:</span>
                    <strong style="color: #10b981;">{{ $product->variants->sum('quantity') }} chiếc</strong>
                </div>
            </div>

            <form action="{{ route('cart.add') }}" method="POST" class="buy-form" style="margin-top: 10px; padding: 20px; background: #fafbfc; border-radius: var(--radius-lg);">
                @csrf
                
                @if($product->variants->count() > 0)
                    <div style="margin-bottom: 18px;">
                        <label style="display: block; font-weight: 700; margin-bottom: 10px; color: var(--dark-color); font-size: 0.95rem;">
                            Chọn kích cỡ (Size) <span style="color: #ef4444;">*</span>:
                        </label>

                        {{-- NÚT CHỌN SIZE TƯƠNG TÁC (SIZE PILLS) --}}
                        <div id="size-options-container" style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 10px;">
                            @foreach($product->variants as $variant)
                                @php
                                    $isOutOfStock = $variant->quantity <= 0;
                                    $sizeLabel = $variant->size ?: 'Tiêu chuẩn';
                                @endphp
                                <button 
                                    type="button" 
                                    class="size-pill-btn" 
                                    data-variant-id="{{ $variant->id }}" 
                                    data-quantity="{{ $variant->quantity }}"
                                    {{ $isOutOfStock ? 'disabled' : '' }}
                                    onclick="selectSizeVariant('{{ $variant->id }}', {{ $variant->quantity }}, this)"
                                    style="min-width: 56px; height: 44px; padding: 0 16px; border: 2px solid {{ $isOutOfStock ? '#e2e8f0' : '#cbd5e1' }}; border-radius: var(--radius-md); background: {{ $isOutOfStock ? '#f8fafc' : '#ffffff' }}; color: {{ $isOutOfStock ? '#94a3b8' : '#1e293b' }}; font-weight: 700; font-size: 0.95rem; cursor: {{ $isOutOfStock ? 'not-allowed' : 'pointer' }}; transition: var(--transition-fast); {{ $isOutOfStock ? 'text-decoration: line-through;' : '' }}"
                                    title="{{ $isOutOfStock ? 'Hết hàng' : 'Còn ' . $variant->quantity . ' cái' }}"
                                >
                                    {{ $sizeLabel }}
                                </button>
                            @endforeach
                        </div>

                        {{-- SELECT DROPDOWN ĐỂ SUBMIT VÀO FORM --}}
                        <select name="product_variant_id" id="variant" required onchange="onVariantSelectChange(this)" style="display: none;">
                            <option value="">-- Chọn kích cỡ --</option>
                            @foreach($product->variants as $variant)
                                <option value="{{ $variant->id }}" data-quantity="{{ $variant->quantity }}" {{ $variant->quantity <= 0 ? 'disabled' : '' }}>
                                    Size: {{ $variant->size ?: 'Tiêu chuẩn' }} (Còn: {{ $variant->quantity }})
                                </option>
                            @endforeach
                        </select>

                        <div id="size-status" style="font-size: 0.88rem; color: #64748b; margin-top: 8px; min-height: 22px;">
                            <span id="variant-quantity" style="font-weight: 600;">Vui lòng chọn size để tiếp tục</span>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        ⚠️ Sản phẩm này hiện chưa có biến thể kích cỡ trong kho.
                    </div>
                @endif
                
                <div style="margin-bottom: 20px;">
                    <label for="quantity" style="display: block; font-weight: 700; margin-bottom: 6px;">Số lượng mua</label>
                    <input type="number" name="quantity" id="quantity" min="1" value="1" style="max-width: 120px; font-weight: 700; text-align: center;">
                </div>
                
                @if($product->variants->sum('quantity') > 0)
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <button type="submit" name="buy_now" value="0" class="btn btn-outline-primary btn-lg" style="font-size: 1rem;">
                            🛒 Thêm vào giỏ
                        </button>
                        <button type="submit" name="buy_now" value="1" class="btn btn-lg" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: #fff; box-shadow: 0 4px 14px rgba(249, 115, 22, 0.35); font-size: 1rem;">
                            ⚡ Mua ngay
                        </button>
                    </div>
                @else
                    <button type="button" class="btn btn-secondary btn-lg" disabled style="width: 100%;">
                        ❌ Tạm thời hết hàng
                    </button>
                @endif
            </form>

            {{-- NÚT THÊM / BỎ YÊU THÍCH --}}
            @auth
                @php
                    $isWishlisted = \App\Models\Wishlist::where('user_id', auth()->id())
                        ->where('product_id', $product->id)
                        ->exists();
                @endphp

                @if($isWishlisted)
                    <form action="{{ route('wishlists.remove', $product) }}" method="POST" style="margin-top: 14px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary" style="width: 100%; color: #ef4444; border-color: #fecaca; background: #fff5f5;">
                            ❤️ Đã lưu vào yêu thích (Bấm để xóa)
                        </button>
                    </form>
                @else
                    <form action="{{ route('wishlists.add') }}" method="POST" style="margin-top: 14px;">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="btn btn-secondary" style="width: 100%;">
                            🤍 Thêm vào danh sách yêu thích
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login.form') }}" class="btn btn-secondary" style="width: 100%; margin-top: 14px; color: var(--slate-600);">
                    🤍 Đăng nhập để lưu vào yêu thích
                </a>
            @endauth

            <script>
                function selectSizeVariant(variantId, quantity, buttonEl) {
                    if (quantity <= 0) return;
                    
                    const select = document.getElementById('variant');
                    if (select) {
                        select.value = variantId;
                    }

                    const allButtons = document.querySelectorAll('.size-pill-btn');
                    allButtons.forEach(btn => {
                        if (!btn.disabled) {
                            btn.style.borderColor = '#cbd5e1';
                            btn.style.background = '#ffffff';
                            btn.style.color = '#1e293b';
                            btn.style.boxShadow = 'none';
                        }
                    });

                    if (buttonEl) {
                        buttonEl.style.borderColor = 'var(--primary-color)';
                        buttonEl.style.background = 'var(--primary-color)';
                        buttonEl.style.color = '#ffffff';
                        buttonEl.style.boxShadow = '0 4px 12px rgba(37, 99, 235, 0.25)';
                    }

                    const quantityInput = document.getElementById('quantity');
                    const quantitySpan = document.getElementById('variant-quantity');
                    if (quantitySpan) {
                        quantitySpan.innerHTML = `✅ Đã chọn size, còn lại: <strong style="color: #16a34a;">${quantity} chiếc</strong> trong kho`;
                    }
                    if (quantityInput) {
                        quantityInput.max = quantity;
                        if (parseInt(quantityInput.value) > quantity) {
                            quantityInput.value = quantity;
                        }
                    }
                }

                function onVariantSelectChange(select) {
                    const option = select.options[select.selectedIndex];
                    const quantity = option.dataset.quantity;
                    const variantId = select.value;

                    const allButtons = document.querySelectorAll('.size-pill-btn');
                    allButtons.forEach(btn => {
                        if (btn.dataset.variantId === variantId) {
                            selectSizeVariant(variantId, quantity, btn);
                        }
                    });
                }

                document.addEventListener('DOMContentLoaded', function() {
                    const firstAvailableBtn = document.querySelector('.size-pill-btn:not([disabled])');
                    if (firstAvailableBtn) {
                        firstAvailableBtn.click();
                    }
                });
            </script>

            {{-- ĐÁNH GIÁ SẢN PHẨM --}}
            <div style="margin-top: 36px; border-top: 1px solid var(--border-color); padding-top: 24px;">
                <h3 style="font-size: 1.3rem; margin-bottom: 16px; font-weight: 800;">⭐ Đánh giá & Nhận xét</h3>

                @auth
                    @php
                        $userReview = $product->reviews->firstWhere('user_id', auth()->id());
                        $hasPurchased = \App\Models\Order::where('user_id', auth()->id())
                            ->where('order_status', 'completed')
                            ->whereHas('details', function ($query) use ($product) {
                                $query->where('product_id', $product->id);
                            })
                            ->exists();
                    @endphp

                    @if($hasPurchased)
                        <form action="{{ route('reviews.store', $product) }}" method="POST" class="review-form" style="background: #f8fafc; padding: 20px; border-radius: var(--radius-lg); margin-bottom: 24px; border: 1px solid var(--border-color);">
                            @csrf
                            <label style="font-weight: 700; margin-bottom: 8px; display: block;">
                                {{ $userReview ? 'Cập nhật đánh giá của bạn' : 'Gửi đánh giá sản phẩm' }}
                            </label>
                            <select name="rating" style="margin-bottom: 12px; width: 100%; max-width: 280px;">
                                <option value="5" {{ ($userReview?->rating ?? 5) == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 sao - Cực kỳ hài lòng)</option>
                                <option value="4" {{ ($userReview?->rating ?? 5) == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ (4 sao - Hài lòng)</option>
                                <option value="3" {{ ($userReview?->rating ?? 5) == 3 ? 'selected' : '' }}>⭐⭐⭐ (3 sao - Bình thường)</option>
                                <option value="2" {{ ($userReview?->rating ?? 5) == 2 ? 'selected' : '' }}>⭐⭐ (2 sao - Chưa ưng ý)</option>
                                <option value="1" {{ ($userReview?->rating ?? 5) == 1 ? 'selected' : '' }}>⭐ (1 sao - Thất vọng)</option>
                            </select>
                            <textarea name="comment" placeholder="Chia sẻ trải nghiệm sử dụng thực tế của bạn về chất liệu, độ vừa vặn..." rows="3" style="margin-bottom: 12px;">{{ $userReview?->comment }}</textarea>
                            <button type="submit" class="btn btn-primary">
                                {{ $userReview ? '💾 Cập nhật đánh giá' : '🚀 Gửi đánh giá' }}
                            </button>
                        </form>
                    @else
                        <div style="background: #f1f5f9; padding: 14px 18px; border-radius: var(--radius-md); color: #475569; font-size: 0.9rem; margin-bottom: 24px; border-left: 4px solid var(--primary-color);">
                            💡 <strong>Ghi chú:</strong> Chỉ khách hàng đã mua sản phẩm này và đơn hàng ở trạng thái <strong>Hoàn thành</strong> mới có thể gửi đánh giá.
                        </div>
                    @endif
                @else
                    <div style="background: #f1f5f9; padding: 14px 18px; border-radius: var(--radius-md); color: #475569; font-size: 0.9rem; margin-bottom: 24px;">
                        Vui lòng <a href="{{ route('login.form') }}" style="color: var(--primary-color); font-weight: 700;">đăng nhập</a> và mua sản phẩm để đánh giá.
                    </div>
                @endauth

                @if($product->reviews->isEmpty())
                    <p style="color: #64748b; font-style: italic;">Chưa có đánh giá nào cho sản phẩm này.</p>
                @else
                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        @foreach($product->reviews as $review)
                            <div style="background: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 16px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                    <strong>{{ $review->user->name ?? 'Khách hàng' }}</strong>
                                    <span style="color: #f59e0b; font-weight: 700;">{{ str_repeat('⭐', $review->rating) }}</span>
                                </div>
                                <p style="margin: 0 0 6px; color: var(--slate-700);">{{ $review->comment ?? 'Không có nhận xét chi tiết.' }}</p>
                                <small style="color: var(--slate-400); font-size: 0.8rem;">{{ $review->created_at?->format('d/m/Y H:i') }}</small>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

