@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('menus.backend.apps.varnish.name'))

@push('after-scripts')
    <script>
        $(function() {
            $(".js-example-placeholder-single").select2({
                placeholder: "Select a state",
                allowClear: false,
                width: 75
            });

            /*$('.ajax-popup-link').magnificPopup({
                type: 'ajax',
                modal: false,
                alignTop: true,
                tClose: 'Cerrar (Esc)',
                tLoading: 'Cargando...',
                ajax: {
                    tError: '<a href="%url%">El contenido</a> no puede cargarse.'
                },
                callbacks: {
                    parseAjax: function(mfpResponse) {
                        mfpResponse.data = $(mfpResponse.data).find('#pip');
                        console.log('Ajax content loaded:', mfpResponse);
                    },
                    ajaxContentAdded: function() {
                        console.log(this.content);
                    }
                }
            });*/
        });
    </script>
@endpush

@section('content')
    <style>
        .mfp-content {
            width: 80% !important;
            height: 80vh !important;
            margin-top: 30px;
        }
    </style>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>Prestashop - Listado de compras</strong>
                </div>
                <div class="card-block">
                    @if (count($orders) > 0)
                    <div class="text-right" style="padding: 0 0 20px 0;">
                        <form>
                            <select id="pagination" class="js-example-placeholder-single js-states form-control">
                                <option value="5" @if($items == 5) selected @endif>5</option>
                                <option value="10" @if($items == 10) selected @endif>10</option>
                                <option value="25" @if($items == 25) selected @endif>25</option>
                                <option value="50" @if($items == 50) selected @endif>50</option>
                            </select>
                        </form>

                        <script>
                            document.getElementById('pagination').onchange = function() {
                                window.location = "{{ $orders->url(1) }}&items=" + this.value;
                            };
                        </script>
                    </div>
                    <table class="table table-responsive-sm table-bordered table-striped table-sm table-hover">
                        <caption>Listado de compras</caption>
                        <thead>
                            <tr>
                                <th class="text-center" scope="col"># ORDEN</th>
                                <th class="text-center" scope="col"># FACTURA</th>
                                <th class="text-center" scope="col">TIENDA</th>
                                <th class="text-center" scope="col">CLIENTE</th>
                                <th class="text-center" scope="col">TOTAL</th>
                                <th class="text-center" scope="col">FECHA COMPRA</th>
                                <th class="text-center" scope="col">ESTADO</th>
                                <th class="text-center" scope="col">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $key => $order)
                            <tr>
                                <th class="text-center" scope="row">{{ $order->id_order }}</th>
                                <th class="text-center" scope="row">{{ $order->invoice_number }}</th>
                                <th class="text-center" scope="row">{{ $order->nombre_comercial }}</th>
                                <td class="text-left">{{ $order->firstname .' '. $order->lastname }}</td>
                                <td class="text-right">${{ number_format($order->total_paid_tax_incl, 2, '.', '.') }}</td>
                                <td class="text-center">{{ $order->invoice_date }}</td>
                                <td class="text-left">
                                    <span class="badge badge-pill text-light" style="background-color: {{ $order->status_bg_color }}; padding: 5px 10px;" title="{{ $order->status }}">{{ $order->status }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.billing.process', $order->id_order) }}" class="btn btn-sm btn-primary" title="Procesar"><i class="fa fa-retweet" aria-hidden="true"></i></a>
                                    <a href="{{ route('admin.billing.verify', $order->id_order) }}" class="btn btn-sm btn-success" title="Verificar"><i class="fa fa-check" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @if ($orders->total() > $orders->perPage())
                        <tfoot>
                            <tr>
                                <td colspan="8" class="text-center" style="padding: 20px 0 0 0;">{{ $orders->links() }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                    @else
                    <p class="text-center">No existe información</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection