@extends('layouts.admin')
@section('content')
<style>
    .pt-90 {
      padding-top: 90px !important;
    }

    .pr-6px {
      padding-right: 6px;
      text-transform: uppercase;
    }

    .my-account .page-title {
      font-size: 1.5rem;
      font-weight: 700;
      text-transform: uppercase;
      margin-bottom: 40px;
      border-bottom: 1px solid;
      padding-bottom: 13px;
    }

    .my-account .wg-box {
      display: -webkit-box;
      display: -moz-box;
      display: -ms-flexbox;
      display: -webkit-flex;
      display: flex;
      padding: 24px;
      flex-direction: column;
      gap: 24px;
      border-radius: 12px;
      background: var(--White);
      box-shadow: 0px 4px 24px 2px rgba(20, 25, 38, 0.05);
    }

    .bg-success {
      background-color: #40c710 !important;
    }

    .bg-danger {
      background-color: #f44032 !important;
    }

    .bg-warning {
      background-color: #f5d700 !important;
      color: #000;
    }

    .table-transaction>tbody>tr:nth-of-type(odd) {
      --bs-table-accent-bg: #fff !important;

    }

    .table-transaction th,
    .table-transaction td {
      padding: 0.625rem 1.5rem .25rem !important;
      color: #000 !important;
    }

    .table> :not(caption)>tr>th {
      padding: 0.625rem 1.5rem .25rem !important;
      background-color: #0191ffd4 !important;
    }

    .table-bordered>:not(caption)>*>* {
      border-width: inherit;
      line-height: 32px;
      font-size: 14px;
      border: 1px solid #e1e1e1;
      vertical-align: middle;
    }

    .table-striped .image {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 50px;
      height: 50px;
      flex-shrink: 0;
      border-radius: 10px;
      overflow: hidden;
    }

    .table-striped td:nth-child(1) {
      min-width: 250px;
      padding-bottom: 7px;
    }

    .pname {
      display: flex;
      gap: 13px;
    }

    .table-bordered> :not(caption)>tr>th,
    .table-bordered> :not(caption)>tr>td {
      border-width: 1px 1px;
      border-color: #0191ffd4;
    }
  </style>
<main class="pt-90" style="padding-top: 0px;">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
         <h2 class="page-title">Rincian Pesanan</h2>
        <div class="row">
            <div class="col-lg-1">
            </div>

            <div class="col-lg-10">
                <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <h5>Rincian Pesanan</h5>
                </div>
                <a class="tf-button style-1 w208" href="{{route('user.orders')}}">Kembali</a>
            </div>
            <div class="table-responsive">
                @if (Session::has('status'))
                    <p class="alert alert-success">{{ Session::get('status') }}</p>
                @endif
                <table class="table table-bordered table-striped table-transaction">
                    <tr>
                        <th>Nomor Pesanan</th>
                        <td>{{ $order->id }}</td>
                        <th>Nomor Telepon</th>
                        <td>{{ $order->phone }}</td>
                        <th>Kode Pos</th>
                        <td>{{ $order->zip }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Pemesanan</th>
                        <td>{{ $order->created_at }}</td>
                        <th>Tanggal Pengiriman</th>
                        <td>{{ $order->delivered_date }}</td>
                        <th>Tanggal Dibatalkan</th>
                        <td>{{ $order->canceled_date }}</td>
                    </tr>
                    <tr>
                        <th>Status Pesanan</th>
                        <td colspan="5">
                            @if ($order->status == 'delivered')
                                <span class="badge bg-success">Terkirim</span>
                            @elseif($order->status == 'canceled')
                                <span class="badge bg-danger">Canceled</span>
                            @else
                                <span class="badge bg-warning">Dipesan</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <div class="divider"></div>
        </div>
        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <h5>Barang yang dipesan</h5>
                </div>  
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th class="text-center">Harga</th>
                            <th class="text-center">Kuantitas</th>
                            <th class="text-center">SKU</th>
                            <th class="text-center">Kategori</th>
                            <th class="text-center">Merk</th>
                            <th class="text-center">Pilihan</th>
                            <th class="text-center">Status Pengembalian</th>
                            <th class="text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orderItems as $item)
                        <tr>
                            <td class="pname">
                                <div class="image">
                                    <img src="{{ asset('uploads/products/thumbnails') }}/{{ $item->product->image }}" alt="{{$item->product->name}}" class="image">
                                </div>
                                <div class="name">
                                    <a href="{{ route('shop.product.details', ['product_slug' => $item->product->slug]) }}" target="_blank"
                                        class="body-title-2">{{$item->product->name}}</a>
                                </div>
                            </td>
                            <td class="text-center">Rp{{number_format($item->price, 0, ',', '.')}}</td>
                            <td class="text-center">{{$item->quantity}}</td>
                            <td class="text-center">{{$item->product->SKU}}</td>
                            <td class="text-center">{{$item->product->category->name}}</td>
                            <td class="text-center">{{$item->product->brand->name}}</td>
                            <td class="text-center">{{$item->option}}</td>
                            <td class="text-center">{{$item->rstatus == 0 ? "No":"Yes"}}</td>
                            <td class="text-center">
                                <div class="list-icon-function view-icon">
                                    <div class="item eye">
                                        <i class="icon-eye"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{ $orderItems->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <div class="wg-box mt-5">
            <h5>Alamat pengiriman</h5>
            <div class="my-account__address-item col-md-6">
                <div class="my-account__address-item__detail">
                    <p>{{ $order->name }}</p>
                    <p>{{ $order->address }}</p>
                    <p>{{ $order->locality }}</p>
                    <p>{{ $order->city }}, {{ $order->country }}</p>
                    <p>{{ $order->landmark }}</p>
                    <p>{{ $order->zip }}</p>
                    <br>
                    <p>Nomor Telepon : {{ $order->phone }}</p>
                </div>
            </div>
        </div>

        <div class="wg-box mt-5">
            <h5>Transaksi</h5>
            <table class="table table-bordered table-transaction">
                <tbody>
                    <tr>
                        <th>Subtotal</th>
                        <td>Rp{{number_format( $order->subtotal, 0, ',', '.') }}</td>
                        <th>Tax</th>
                        <td>Rp{{number_format( $order->tax, 0, ',', '.') }}</td>
                        <th>Discount</th>
                        <td>Rp{{number_format( $order->discount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td>Rp{{number_format( $order->total, 0, ',', '.') }}</td>
                        <th>Metode Pembayaran</th>
                        <td>{{ $transaction->mode }}</td>
                        <th>Status</th>
                        <td>
                            @if ($transaction->status == 'approved')
                                <span class="badge bg-success">Disetujui</span>
                            @elseif($transaction->status == 'declined')
                                <span class="badge bg-danger">Ditolak</span>
                            @elseif($transaction->status == 'refunded')
                                <span class="badge bg-secondary">Dikembalikan</span>
                            @else
                                <span class="badge bg-warning">Tertunda</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if ($order->status == 'ordered')
        <div class="wg-box mt-5 text-right">
            <form action="{{route('user.order.cancel')}}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="order_id" value="{{ $order->id }}" />
                <button type="button" class="btn btn-danger cancel-order btn-lg" style="float: right;">Batakan Pesanan</button>
            </form>
        </div>
        @endif
        </div>
            
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
    $(function() {
        $('.cancel-order').on('click', function(e){
            e.preventDefault();
            var form = $(this).closest('form');
            swal({
                title: "Are you sure?",
                text: "You want to cancel this order?",
                type: "warning",
                buttons: ["No", "Yes"],
                confirmButtonColor: '#dc3545'
            }).then(function(result){
                if(result){
                    form.submit();
                }
            });
        });
    });
</script>
@endpush