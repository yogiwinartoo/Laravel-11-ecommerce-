@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
<div class="main-content-wrap">
    <div class="tf-section-2 mb-30">
        <div class="flex gap20 flex-wrap-mobile">
            <div class="w-half">

                <div class="wg-chart-default mb-20">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap14">
                            <div class="image ic-bg">
                                <i class="icon-shopping-bag"></i>
                            </div>
                            <div>
                                <div class="body-text mb-2">Total Pesanan</div>
                                <h4>{{ $dashboardData->Total }}</h4>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="wg-chart-default mb-20">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap14">
                            <div class="image ic-bg">
                                <span style="font-size: 2em; font-weight: bold; line-height: 2.5;">Rp</span>
                            </div>
                            <div>
                                <div class="body-text mb-2">Jumlah Total</div>
                                <h4>Rp{{ number_format($dashboardData->TotalAmount, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="wg-chart-default mb-20">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap14">
                            <div class="image ic-bg">
                                <i class="icon-shopping-bag"></i>
                            </div>
                            <div>
                                <div class="body-text mb-2">Pesanan Tertunda</div>
                               <h4>{{ $dashboardData->TotalOrdered }}</h4>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="wg-chart-default">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap14">
                            <div class="image ic-bg">
                                <span style="font-size: 2em; font-weight: bold; line-height: 2.5;">Rp</span>
                                {{-- <i class="icon-dollar-sign"></i> --}}
                            </div>
                            <div>
                                <div class="body-text mb-2">Jumlah Pesanan Tertunda</div>
                              <h4>Rp{{ number_format( $dashboardData->TotalOrderedAmount, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="w-half">

                <div class="wg-chart-default mb-20">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap14">
                            <div class="image ic-bg">
                                <i class="icon-shopping-bag"></i>
                            </div>
                            <div>
                                <div class="body-text mb-2">Pesanan Terkirim</div>
                               <h4>{{ $dashboardData->TotalDelivered }}</h4>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="wg-chart-default mb-20">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap14">
                            <div class="image ic-bg">
                                <span style="font-size: 2em; font-weight: bold; line-height: 2.5;">Rp</span>
                                {{-- <i class="icon-dollar-sign"></i> --}}
                            </div>
                            <div>
                                <div class="body-text mb-2">Jumlah Pesanan Terkirim</div>
                               <h4>Rp{{ number_format( $dashboardData->TotalDeliveredAmount, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="wg-chart-default mb-20">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap14">
                            <div class="image ic-bg">
                                <i class="icon-shopping-bag"></i>
                            </div>
                            <div>
                                <div class="body-text mb-2">Pesanan dibatalkan</div>
                                <h4>{{ $dashboardData->TotalCanceled }}</h4>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="wg-chart-default">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap14">
                            <div class="image ic-bg">
                                 <span style="font-size: 2em; font-weight: bold; line-height: 2.5;">Rp</span>
                                {{-- <i class="icon-dollar-sign"></i> --}}
                            </div>
                            <div>
                                <div class="body-text mb-2">Jumlah Pesanan dibatalkan</div>
                                <h4>Rp{{ number_format( $dashboardData->TotalCanceledAmount, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between">
                <h5>Pendapatan</h5>
                <div class="dropdown default">
                    <button class="btn btn-secondary dropdown-toggle" type="button"
                        data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <span class="icon-more"><i class="icon-more-horizontal"></i></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="javascript:void(0);">Minggu Ini</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Minggu Lalu</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="flex flex-wrap gap40">
                <div>
                    <div class="mb-2">
                        <div class="block-legend">
                            <div class="dot t1"></div>
                            <div class="text-tiny">Pendapatan</div>
                        </div>
                    </div>
                    <div class="flex items-center gap10">
                        <h4>Rp96.800</h4>
                        <div class="box-icon-trending up">
                            <i class="icon-trending-up"></i>
                            <div class="body-title number">0.56%</div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="mb-2">
                        <div class="block-legend">
                            <div class="dot t2"></div>
                            <div class="text-tiny">Pesanan</div>
                        </div>
                    </div>
                    <div class="flex items-center gap10">
                        <h4>Rp96.800</h4>
                        <div class="box-icon-trending up">
                            <i class="icon-trending-up"></i>
                            <div class="body-title number">0.56%</div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="line-chart-8"></div>
        </div>

    </div>
    <div class="tf-section mb-30">

        <div class="wg-box">
            <div class="flex items-center justify-between">
                <h5>Pesanan terbaru</h5>
                <div class="dropdown default">
                    <a class="btn btn-secondary dropdown-toggle" href="{{route('admin.orders')}}">
                        <span class="view-all">Lihat semua</span>
                    </a>
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
        </div>

    </div>
</div>

</div>
@endsection