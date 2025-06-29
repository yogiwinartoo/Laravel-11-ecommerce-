@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
      <h2 class="page-title">Akun Saya</h2>
      <div class="row">
        <div class="col-lg-3">
          @include('user.account-nav')
        </div>
        <div class="col-lg-9">
          <div class="page-content my-account__dashboard">
            <p>Hello <strong>User</strong></p>
            <p>Dari dasbor akun Anda, Anda dapat melihat <a class="unerline-link" href="account_orders.html">pesanan
            terbaru</a>, kelola anda <a class="unerline-link" href="account_edit_address.html">alamat pengiriman</a>, dan <a class="unerline-link" href="account_edit.html">edit kata sandi dan detail akun Anda.</a></p>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection