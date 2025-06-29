@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
  <div class="main-content-wrap">
      <div class="flex items-center flex-wrap justify-between gap20 mb-27">
          <h3>Pesanan</h3>
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
                  <div class="text-tiny">Pesanan</div>
              </li>
          </ul>
      </div>

      <div class="wg-box">
          <div class="flex items-center justify-between gap10 flex-wrap">
              <div class="wg-filter flex-grow">
                  <form class="form-search">
                      <fieldset class="name">
                          <input type="text" placeholder="Search here..." class="" name="name"
                              tabindex="2" value="" aria-required="true" required="">
                      </fieldset>
                      <div class="button-submit">
                          <button class="" type="submit"><i class="icon-search"></i></button>
                      </div>
                  </form>
              </div>
          </div>
          <div class="wg-table table-all-user">
              <div class="table-responsive">
                  <table class="table table-striped table-bordered">
                      <thead>
                          <tr>
                              <th style="width:70px">No Pesanan</th>
                              <th class="text-center">Nama</th>
                              <th class="text-center">Nomor</th>
                              <th class="text-center">Subtotal</th>
                              <th class="text-center">Pajak</th>
                              <th class="text-center">Total</th>
                              <th class="text-center">Status</th>
                              <th class="text-center">Tanggal Pesanan</th>
                              <th class="text-center">Jumlah Barang</th>
                              <th class="text-center">Dikirim</th>
                              <th></th>
                          </tr>
                      </thead>
                      <tbody>
                        @foreach ($orders as $order)
                          <tr>
                              <td class="text-center">{{ $order->id }}</td>
                              <td class="text-center">{{ $order->name }}</td>
                              <td class="text-center">{{ $order->phone }}</td>
                              <td class="text-center">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</td>
                              <td class="text-center">Rp{{ number_format($order->tax, 0, ',', '.') }}</td>
                              <td class="text-center">Rp{{ number_format($order->total, 0, ',', '.') }}</td>  
                              <td class="text-center">
                                @if ($order->status == 'delivered')
                                    <span class="badge bg-success">Dikirim</span>
                                @elseif($order->status == 'canceled')
                                    <span class="badge bg-danger">Dibatalkan</span>
                                @else
                                    <span class="badge bg-warning">Dipesan</span>
                                @endif
                              </td>
                              <td class="text-center">{{ $order->created_at }}</td>
                              <td class="text-center">{{ $order->orderItems->count() }}</td>
                              <td class="text-center">{{ $order->delivered_date }}</td>
                              <td class="text-center">
                                  <a href="{{ route('admin.order.details', ['id' => $order->id]) }}">
                                      <div class="list-icon-function view-icon">
                                          <div class="item eye">
                                              <i class="icon-eye"></i>
                                          </div>
                                      </div>
                                  </a>
                              </td>
                          </tr>
                          @endforeach
                      </tbody>
                  </table>
              </div>
          </div>
          <div class="divider"></div>
          <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
            {{ $orders->links('pagination::bootstrap-5') }}
          </div>
      </div>
  </div>
</div>
@endsection