@extends($pathPlugin.'::Admin.layout')
@section('content')
    <div class="tbk-content col-sm-6">
        <div class="card-header with-border">
            <h3 class="card-title">{{ trans($detailTranslatePath . 'title') }} #{{ $transaction['id'] }}</h3>
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
                    <td>{{ $transaction['status'] }}</td>
                </tr>
                <tr>
                    <td class="td-title">{{ trans($detailTranslatePath . 'response_code') }}:</td>
                    <td>{{ $transaction['responseCode'] }}</td>
                </tr>
                <tr>
                    <td class="td-title">{{ trans($detailTranslatePath . 'amount') }}:</td>
                    <td>{{ $transaction['amount'] }}</td>
                </tr>
                <tr>
                    <td class="td-title">{{ trans($detailTranslatePath . 'authorization_code') }}:</td>
                    <td>{{ $transaction['authorizationCode'] }}</td>
                </tr>
                <tr>
                    <td class="td-title">{{ trans($detailTranslatePath . 'payment_type') }}:</td>
                    <td>{{ $transaction['paymentType'] }}</td>
                </tr>
                <tr>
                    <td class="td-title">{{ trans($detailTranslatePath . 'installments_number') }}:</td>
                    <td>{{ $transaction['installmentsNumber'] }}</td>
                </tr>
                <tr>
                    <td class="td-title">{{ trans($detailTranslatePath . 'installments_amount') }}:</td>
                    <td>{{ $transaction['installmentsAmount'] }}</td>
                </tr>
                <tr>
                    <td class="td-title">{{ trans($detailTranslatePath . 'card_number') }}:</td>
                    <td>{{ $transaction['cardNumber'] }}</td>
                </tr>
                <tr>
                    <td class="td-title">{{ trans($detailTranslatePath . 'transaction_date') }}:</td>
                    <td>{{ $transaction['transactionDate'] }}</td>
                </tr>
                <tr>
                    <td class="td-title">{{ trans($detailTranslatePath . 'balance') }}:</td>
                    <td>{{ $transaction['balance'] }}</td>
                </tr>
            </table>
        </div>
    </div>
@endsection
