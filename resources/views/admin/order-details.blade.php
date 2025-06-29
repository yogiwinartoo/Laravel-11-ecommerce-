@extends('layouts.admin')
@section('content')
<style>
    .table-transaction>tbody>tr:nth-of-type(odd) {
        --bs-table-accent-bg: #fff !important;
    }
</style>
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Order Details</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Detail Pesanan</Details></div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <h5>Detail Pesanan</h5>
                </div>
                <a class="tf-button style-1 w208" href="{{route('admin.orders')}}">Kembali</a>
            </div>
            <div class="table-responsive">
                @if(Session::has('status'))
                    <p class="alert alert-success">{{Session::get('status')}}</p>
                @endif
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>No Pesanan</th>
                        <td>{{ $order->id }}</td>
                        <th>Nomor</th>
                        <td>{{ $order->phone }}</td>
                        <th>Kode Pos</th>
                        <td>{{ $order->zip }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Pesanan</th>
                        <td>{{ $order->created_at }}</td>
                        <th>Tanggal Pengiriman</th>
                        <td>{{ $order->delivered_date }}</td>
                        <th>Tanggal Pembatalan</th>
                        <td>{{ $order->canceled_date }}</td>
                    </tr>
                    <tr>
                        <th>Status Pesanan</th>
                        <td colspan="5">
                            @if ($order->status == 'delivered')
                                <span class="badge bg-success">Dikirim</span>
                            @elseif($order->status == 'canceled')
                                <span class="badge bg-danger">Dibatalkan</span>
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
                    <h5>Jumlah Barang</h5>
                </div>  
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th class="text-center">Harga</th>
                            <th class="text-center">jumlah</th>
                            <th class="text-center">SKU</th>
                            <th class="text-center">Kategori</th>
                            <th class="text-center">Merek</th>
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
            <h5>Shipping Address</h5>
            <div class="my-account__address-item col-md-6">
                <div class="my-account__address-item__detail">
                    <p>{{ $order->name }}</p>
                    <p>{{ $order->address }}</p>
                    <p>{{ $order->locality }}</p>
                    <p>{{ $order->city }}, {{ $order->country }}</p>
                    <p>{{ $order->landmark }}</p>
                    <p>{{ $order->zip }}</p>
                    <br>
                    <p>Mobile : {{ $order->phone }}</p>
                </div>
            </div>
        </div>

        <div class="wg-box mt-5">
            <h5>Transactions</h5>
            <table class="table table-striped table-bordered table-transaction">
                <tbody>
                    <tr>
                        <th>Subtotal</th>
                        <td>${{ $order->subtotal }}</td>
                        <th>Tax</th>
                        <td>${{ $order->tax }}</td>
                        <th>Discount</th>
                        <td>${{ $order->discount }}</td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td>${{ $order->total }}</td>
                        <th>Payment Mode</th>
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

        <div class="wg-box mt-5">
            <h5>Update Order Status</h5>
            <form action="{{ route('admin.order.status.update') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="order_id" value="{{ $order->id }}" />
                <div class="row">
                    <div class="col-md-3">
                        <select id="order_status" name="order_status">
                            <option value="ordered" {{ $order->status == 'ordered' ? 'selected' : '' }}>Dipesan</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Dikirim</option>
                            <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary tf-button w208">Status Perbarui</button>
                    </div>
                </div>
            </form>           
        </div>

    </div>
</div>
@endsection