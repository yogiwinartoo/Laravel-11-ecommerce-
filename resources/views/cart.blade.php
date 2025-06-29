@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
      <h2 class="page-title">Keranjang</h2>
      <div class="checkout-steps">
        <a href="javacript:void(0)" class="checkout-steps__item active">
          <span class="checkout-steps__item-number">01</span>
          <span class="checkout-steps__item-title">
            <span>Keranjang Belanja</span>
            <em>Kelola Daftar Item Anda</em>
          </span>
        </a>
        <a href="javacript:void(0)" class="checkout-steps__item">
          <span class="checkout-steps__item-number">02</span>
          <span class="checkout-steps__item-title">
            <span>Pengiriman dan Pembayaran</span>
            <em>Periksa Daftar Barang Anda</em>
          </span>
        </a>
        <a href="javacript:void(0)" class="checkout-steps__item">
          <span class="checkout-steps__item-number">03</span>
          <span class="checkout-steps__item-title">
            <span>Konfirmasi</span>
            <em>Tinjau dan Kirimkan Pesanan Anda</em>
          </span>
        </a>
      </div>
      <div class="shopping-cart">
        @if($items->count()>0)
        <div class="cart-table__wrapper">
          <table class="cart-table">
            <thead>
              <tr>
                <th>Produk</th>
                <th></th>
                <th>Harga</th>
                <th>Kuantitas</th>
                <th>Subtotal</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            @foreach ($items as $item)
              <tr>
                <td>
                  <div class="shopping-cart__product-item">
                    <img loading="lazy" src="{{ asset('uploads/products/thumbnails') }}/{{ $item->model->image }}" width="120" height="120" alt="{{$item->name}}" />
                  </div>
                </td>
                <td>
                  <div class="shopping-cart__product-item__detail">
                    <h4>{{$item->name}}</h4>
                    <ul class="shopping-cart__product-item__options">
                      <li>Color: Yellow</li>
                      <li>Size: L</li>
                    </ul>
                  </div>
                </td>
                <td>
                  <span class="shopping-cart__product-price">Rp{{number_format($item->price, 0, ',', '.')}}</span>
                </td>
                <td>
                  <div class="qty-control position-relative">
                    <input type="number" name="quantity" value="{{$item->qty}}" min="1" class="qty-control__number text-center">
                    <form method="POST" action="{{route('cart.qty.decrease', ['rowId' => $item->rowId])}}">
                        @csrf
                        @method('PUT')
                        <div class="qty-control__reduce">-</div> 
                    </form>

                    <form method="POST" action="{{route('cart.qty.increase', ['rowId' => $item->rowId])}}">
                        @csrf
                        @method('PUT')
                        <div class="qty-control__increase">+</div> 
                    </form>
                </div>
                </td>
                <td>
                  <span class="shopping-cart__subtotal">Rp{{number_format($item->subtotal, 0, ',', '.')}}</span>
                </td>
                <td>
                  <form method="POST" action="{{route('cart.item.remove', ['rowId' => $item->rowId])}}">
                  @csrf
                  @method('DELETE')
                  <a href="#" class="remove-cart">
                    <svg width="10" height="10" viewBox="0 0 10 10" fill="#767676" xmlns="http://www.w3.org/2000/svg">
                      <path d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z" />
                      <path d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z" />
                    </svg>
                  </a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <div class="cart-table-footer">
            <form action="#" class="position-relative bg-body">
              <input class="form-control" type="text" name="coupon_code" placeholder="Coupon Code">
              <input class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4" type="submit"
                value="">
            </form>
            <form action="{{ route('cart.empty') }}" method="POST">
              @csrf
              @method('DELETE')
            <button class="btn btn-light" type="submit">Hapus Keranjang</button>
          </div>
        </div>
        <div class="shopping-cart__totals-wrapper">
          <div class="sticky-content">
            <div class="shopping-cart__totals">
              <h3>Cart Totals</h3>
              <table class="cart-totals">
                <tbody>
                  <tr>
                    <th>Subtotal</th>
                    <td>Rp{{Cart::instance('cart')->subtotal()}}</td>
                  </tr>
                  <tr>
                    <th>Pengiriman</th>
                    <td>Gratis</td>
                  </tr>
                  <tr>
                    <th>Pajak</th>
                    <td>Rp{{Cart::instance('cart')->tax()}}</td>
                  </tr>
                  <tr>
                    <th>Total</th>
                    <td>Rp{{Cart::instance('cart')->total()}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="mobile_fixed-btn_wrapper">
              <div class="button-wrapper container">
                <a href="{{route('cart.checkout')}}" class="btn btn-primary btn-checkout">LANJUTKAN KE PEMBAYARAN</a>
              </div>
            </div>
          </div>
        </div>
        @else
            <div class="row">
                <div class="col-md-12 text-center pt-5 bp-5">
                    <p>Tidak ada barang yang ditemukan di keranjang Anda</p>
                    <a href="{{route('shop.index')}}" class="btn btn-info">Belanja Sekarang</a>
                </div>
            </div>
        @endif
      </div>
    </section>
  </main>
@endsection

@push('scripts')
<script>
  $(function(){
      $(".qty-control__increase").on("click", function(){ 
          $(this).closest('form').submit();
      });

      $(".qty-control__reduce").on("click", function(){ 
          $(this).closest('form').submit();
      });

      $('.remove-cart').on("click", function() {
          $(this).closest('form').submit();
      });
});
</script>
@endpush