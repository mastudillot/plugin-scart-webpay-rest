@extends($pathPlugin.'::Admin.layout')
@section('content')
    <div class="tbk-content col-sm-6">
        <div class="card-header with-border">
            <h3 class="card-title">{{ trans($detailTranslatePath . 'title') }} #{{ $transaction->id }}</h3>
            <div class="card-tools not-print">
                <div class="btn-group float-right" style="margin-right: 0px">
                    <a href="{{ sc_route_admin('admin_webpayplus.index', ['option' => 'transactions']) }}"
                        class="btn btn-flat btn-default">
                        <em class="fa fa-list"></em>&nbsp;
                        {{ sc_language_render('admin.back_list') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="row p-3">
            <table class=" table table-hover box-body text-wrap table-bordered">
                <tr>
                    <td class="td-title">{{ trans($detailTranslatePath . 'status') }}:</td>
                    <td>{!! $transbankResponse['status'] !!}</td>
                </tr>
                <tr>
                    <td class="td-title">{{ trans($detailTranslatePath . 'response_code') }}:</td>
                    <td>{!! $transbankResponse['responseCode'] !!}</td>
                </tr>
                <tr>
                    <td class="td-title">{{ trans($detailTranslatePath . 'amount') }}:</td>
                    <td>{!! sc_currency_render_symbol($transbankResponse['amount'] ?? 0, $transaction->order->currency) !!}</td>
                </tr>
                <tr>
                    <td class="td-title">{{ trans($detailTranslatePath . 'authorization_code') }}:</td>
                    <td>{!! $transbankResponse['authorizationCode'] !!}</td>
                </tr>
                <tr>
                    <td class="td-title">{{ trans($detailTranslatePath . 'payment_type') }}:</td>
                    <td>{!! $transbankResponse['paymentTypeCode'] !!}</td>
                </tr>
                @if ($transbankResponse['paymentTypeCode'] == 'VC' || $transbankResponse['paymentTypeCode'] == 'SI' || $transbankResponse['paymentTypeCode'] == 'S2' || $transbankResponse['paymentTypeCode'] == 'NC')
                    <tr>
                        <td class="td-title">{{ trans($detailTranslatePath . 'installments_number') }}:</td>
                        <td>{!! $transbankResponse['installmentsNumber'] !!}</td>
                    </tr>
                    <tr>
                        <td class="td-title">{{ trans($detailTranslatePath . 'installments_amount') }}:</td>
                        <td>{!! $transbankResponse['installmentsAmount'] !!}</td>
                    </tr>
                @endif
                <tr>
                    <td class="td-title">{{ trans($detailTranslatePath . 'card_number') }}:</td>
                    <td>{!! $transbankResponse['cardNumber'] !!}</td>
                </tr>
                <tr>
                    <td class="td-title">{{ trans($detailTranslatePath . 'transaction_date') }}:</td>
                    <td>{!! $transbankResponse['transactionDate'] !!}</td>
                </tr>
                @if ($transbankResponse['balance'])
                    <tr>
                        <td class="td-title">{{ trans($detailTranslatePath . 'balance') }}:</td>
                        <td>{!! $transbankResponse['balance'] !!}</td>
                    </tr>
                @endif
            </table>
        </div>
    </div>
@endsection
