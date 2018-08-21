@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('menus.backend.apps.varnish.name'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>Prestashop - Listado de facturas</strong>
                </div>
                <div class="card-block">
                    @if (isset($responseData->GetDataResult))
                    <table class="table table-responsive-sm table-bordered table-striped table-sm table-hover">
                        <caption>Listado de facturas</caption>
                        <thead>
                            <tr>
                                <th class="text-center" scope="col">OrderId</th>
                                <th class="text-center" scope="col">Processed</th>
                                <th class="text-center" scope="col">DocumentId</th>
                                <th class="text-center" scope="col">DocumentType</th>
                                <th class="text-center" scope="col">DocumentTypeEmision</th>
                                <th class="text-center" scope="col">DocumentAutorizacionDate</th>
                                <th class="text-center" scope="col">DocumentStatus</th>
                                <th class="text-center" scope="col">DocumentInstance</th>
                                <th class="text-center" scope="col">DocumentNotified</th>
                                <th class="text-center" scope="col">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th class="text-center" scope="row">-</th>
                                <td class="text-center" scope="row">{{ $responseData->GetDataResult->Processed }}</td>
                                <td class="text-center" scope="row">{{ $responseData->GetDataResult->DocumentId }}</td>
                                <td class="text-center" scope="row">{{ $responseData->GetDataResult->DocumentType }}</td>
                                <td class="text-center" scope="row">{{ $responseData->GetDataResult->DocumentTypeEmision }}</td>
                                <td class="text-center" scope="row">{{ $responseData->GetDataResult->DocumentAutorizacionDate }}</td>
                                <td class="text-left">
                                    <span class="badge badge-pill badge-danger text-light" style="padding: 5px 10px;" title="{{ $responseData->GetDataResult->DocumentStatus }}">{{ $responseData->GetDataResult->DocumentStatus }}</span>
                                </td>
                                <td class="text-center" scope="row">{{ $responseData->GetDataResult->DocumentInstance }}</td>
                                <td class="text-center" scope="row">{{ $responseData->GetDataResult->DocumentNotified }}</td>
                                <td class="text-center" scope="row">
                                    <a href="{{ route('admin.billing.process', 1) }}" class="btn btn-sm btn-primary" title="Procesar"><i class="fa fa-retweet" aria-hidden="true"></i></a>
                                    <a href="{{ route('admin.billing.verify', 1) }}" class="btn btn-sm btn-success" title="Verificar"><i class="fa fa-check" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @else
                    <p class="text-center">No existe información</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection