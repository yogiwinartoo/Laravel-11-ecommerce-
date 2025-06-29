@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
      <h2 class="page-title">Pengiriman dan Pembayaran</h2>
      <div class="checkout-steps">
        <a href="{{route('cart.index')}}" class="checkout-steps__item active">
          <span class="checkout-steps__item-number">01</span>
          <span class="checkout-steps__item-title">
            <span>Keranjang Belanja</span>
            <em>Kelola Daftar Item Anda</em>
          </span>
        </a>
        <a href="javascrip:void(0)" class="checkout-steps__item active">
          <span class="checkout-steps__item-number">02</span>
          <span class="checkout-steps__item-title">
            <span>Pengiriman dan Pembayaran</span>
            <em>Periksa Daftar Barang Anda</em>
          </span>
        </a>
        <a href="javascrip:void(0)" class="checkout-steps__item">
          <span class="checkout-steps__item-number">03</span>
          <span class="checkout-steps__item-title">
            <span>Konfirmasi</span>
            <em>Tinjau dan Kirimkan Pesanan Anda</em>
          </span>
        </a>
      </div>
      <form name="checkout-form" action="{{route('cart.place_an_order')}}" method="POST">
        @csrf
        <div class="checkout-form">
          <div class="billing-info__wrapper">
            <div class="row">
              <div class="col-6">
                <h4>RINCIAN PENGIRIMAN</h4>
              </div>
              <div class="col-6">
              </div>
            </div>
            @if ($address)
                <div class="row">
                    <div class="col-md-12">
                        <div class="my-account_address-list">
                            <div class="my-account_address-list-item">
                                <div class="my-account_address-item_detail">
                                    <p>{{ $address->name }}</p>
                                    <p>{{ $address->address }}</p>
                                    <p>{{ $address->landmark }}</p>
                                    <p>{{ $address->city }}, {{ $address->state }}, {{ $address->country }}</p>
                                    <p>{{ $address->zip }}</p>
                                    <br/>
                                    <p>{{ $address->phone }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @else
            <div class="row mt-5">
              <div class="col-md-6">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="name" required="" value="{{old('name')}}">
                  <label for="name">Nama Lengkap *</label>
                  @error('name') <span class="text-danger">{{$message}}</span> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="phone" required="" value="{{old('phone')}}">
                  <label for="phone">Nomor telepon *</label>
                  @error('phone') <span class="text-danger">{{$message}}</span> @enderror
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="zip" required="" value="{{old('zip')}}">
                  <label for="zip">Kode pos *</label>
                  @error('zip') <span class="text-danger">{{$message}}</span> @enderror
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-floating mt-3 mb-3">
                  <input type="text" class="form-control" name="state" required="" value="{{old('state')}}">
                  <label for="state">Negara *</label>
                  @error('state') <span class="text-danger">{{$message}}</span> @enderror
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="city" required="" value="{{old('city')}}">
                  <label for="city">Kota *</label>
                  @error('city') <span class="text-danger">{{$message}}</span> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="address" required="" value="{{old('address')}}">
                  <label for="address">Nomor Rumah, Nama Bangunan *</label>
                  @error('address') <span class="text-danger">{{$message}}</span> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="locality" required="" value="{{old('locality')}}">
                  <label for="locality">Nama Jalan, Wilayah *</label>
                  @error('locality') <span class="text-danger">{{$message}}</span> @enderror
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="landmark" required="" value="{{old('landmark')}}">
                  <label for="landmark">Patokan *</label>
                  @error('landmark') <span class="text-danger">{{$message}}</span> @enderror
                </div>
              </div>
            </div>
            @endif
          </div>
          <div class="checkout__totals-wrapper">
            <div class="sticky-content">
              <div class="checkout__totals">
                <h3>Pesanan Anda</h3>
                <table class="checkout-cart-items">
                  <thead>
                    <tr>
                      <th>PRODUK</th>
                      <th align="right">SUBTOTAL</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach (Cart::instance('cart') as $item)
                    <tr>
                        <td>
                            {{ $item->name }} x {{ $item->qty }}
                        </td>
                        <td align="right">
                            Rp{{ number_format($item->subtotal(), 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                <table class="checkout-totals">
                  <tbody>
                    <tr>
                      <th>SUBTOTAL</th>
                      <td class="text-right">Rp{{ Cart::instance('cart')->subtotal() }}</td>
                    </tr>
                    <tr>
                      <th>PENGIRIMAN</th>
                      <td class="text-right">Gratis</td>
                    </tr>
                    <tr>
                      <th>PAJAK</th>
                      <td class="text-right">Rp{{ Cart::instance('cart')->tax() }}</td>
                    </tr>
                    <tr>
                      <th>TOTAL</th>
                      <td class="text-right">Rp{{ Cart::instance('cart')->total() }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="checkout__payment-methods">
                <div class="form-check">
                  <input class="form-check-input form-check-input_fill" type="radio" name="mode" id="mode1" value="card">
                  <label class="form-check-label" for="checkout_payment_method_2">
                    Kartu Debit atau Kredit
                    
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input form-check-input_fill" type="radio" name="mode" id="mode2" value="paypal">
                  <label class="form-check-label" for="checkout_payment_method_4">
                    Dana
                  </label>
                </div>

                <div class="form-check">
                  <input class="form-check-input form-check-input_fill" type="radio" name="mode" id="mode3" value="cod">
                  <label class="form-check-label" for="checkout_payment_method_3">
                    Bayar di tempat
                  </label>
                </div>
                
                <div class="policy-text">
                 Data pribadi Anda akan digunakan untuk memproses pesanan Anda, mendukung pengalaman Anda di seluruh situs web ini, dan untuk tujuan lain yang dijelaskan dalam <a href="terms.html" target="_blank">kebijakan privasi</a>.
                </div>
              </div>
              <button class="btn btn-primary btn-checkout">TEMPATKAN PESANAN</button>
            </div>
          </div>
        </div>
      </form>
    </section>
  </main>
@endsection