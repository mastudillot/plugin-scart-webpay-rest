@extends($pathPlugin.'::Admin.layout')
@section('content')
<form action="{{ sc_route_admin('admin_webpayplus.config.save') }}" method="post"
  class="flex flex-col bg-white rounded-xl p-3 xl:w-1/2 lg:w-5/6 space-y-5">
  @csrf
  <div class="flex flex-row">
    <label for="environment" class="w-40 m-0 break-words">
      {{ trans($pathPlugin.'::lang.admin.webpay_plus_environment') }}:
    </label>
    <select name="environment" id="environment" class="border rounded ml-auto w-96 p-1">
      <option value="integration" {{ sc_config('WebpayPlus_environment')=='integration' ? 'selected' : '' }}>
        {{ trans($pathPlugin.'::lang.admin.webpay_plus_integration_mode') }}
      </option>
      <option value="production" {{ sc_config('WebpayPlus_environment')=='production' ? 'selected' : '' }}>
        {{ trans($pathPlugin.'::lang.admin.webpay_plus_production_mode') }}
      </option>
    </select>
  </div>
  @if ($errors->has('environment'))
  <span class="text-sm text-red-400 text-right">
    <em class="fa fa-info-circle"></em> {{ $errors->first('environment') }}
  </span>
  @endif
  <div class="flex flex-row">
    <label for="commerce-code" class="w-40 m-0 break-words">
      {{ trans($pathPlugin.'::lang.admin.webpay_plus_commerce_code') }}:
    </label>
    <input type="text" name="commerce-code" id="commerce-code" class="border rounded ml-auto w-96 p-1"
      placeholder="597012345678"
      value="{{ sc_config('WebpayPlus_environment') == 'production' ? sc_config('WebpayPlus_commerce_code') : ''}}" {{
      sc_config('WebpayPlus_environment')=='integration' ? 'disabled' : '' }}>
  </div>
  @if ($errors->has('commerce-code'))
  <span class="text-sm text-red-400 text-right">
    <em class="fa fa-info-circle"></em> {{ $errors->first('commerce-code') }}
  </span>
  @endif
  <div class="flex flex-row">
    <label for="api-key" class="w-40 m-0 break-words">
      {{ trans($pathPlugin.'::lang.admin.webpay_plus_api_key') }}:
    </label>
    <input type="password" name="api-key" id="api-key" class="border rounded ml-auto w-96 p-1"
      value="{{ sc_config('WebpayPlus_environment') == 'production' ? sc_config('WebpayPlus_commerce_code') : ''}}" {{
      sc_config('WebpayPlus_environment')=='integration' ? 'disabled' : '' }}>
  </div>
  @if ($errors->has('api-key'))
  <span class="text-sm text-red-400 text-right">
    <em class="fa fa-info-circle"></em> {{ $errors->first('api-key') }}
  </span>
  @endif
  <div class="flex flex-row">
    <label for="order-status-success" class="w-40 m-0 break-words">
      {{ trans($pathPlugin.'::lang.admin.webpay_plus_order_status_success') }}:
    </label>
    <select name="order-status-success" id="order-status-success" class="border rounded ml-auto w-96 p-1">
      @for ($i = 1; $i <= count($statusOrder); $i++) <option value="{{ $i }}" {{
        sc_config('WebpayPlus_order_status_success')==$i ? 'selected' : '' }}>
        {{ $statusOrder[$i] }}
        </option>
        @endfor
    </select>
  </div>
  @if ($errors->has('order-status-succes'))
  <span class="text-sm text-red-400 text-right">
    <em class="fa fa-info-circle"></em> {{ $errors->first('order-status-succes') }}
  </span>
  @endif
  <div class="flex flex-row">
    <label for="order-status-failed" class="w-40 m-0 break-words">
      {{ trans($pathPlugin.'::lang.admin.webpay_plus_order_status_failed') }}:
    </label>
    <select name="order-status-failed" id="order-status-failed" class="border rounded ml-auto w-96 p-1">
      @for ($i = 1; $i <= count($statusOrder); $i++) <option value="{{ $i }}" {{
        sc_config('WebpayPlus_order_status_failed')==$i ? 'selected' : '' }}>
        {{ $statusOrder[$i] }}
        </option>
        @endfor
    </select>
  </div>
  @if ($errors->has('order-status-failed'))
  <span class="text-sm text-red-400 text-right">
    <em class="fa fa-info-circle"></em> {{ $errors->first('order-status-failed') }}
  </span>
  @endif
  <div class="flex flex-row">
    <label for="payment-status" class="w-40 m-0 break-words">
      {{ trans($pathPlugin.'::lang.admin.webpay_plus_payment_status') }}:
    </label>
    <select name="payment-status" id="payment-status" class="border rounded ml-auto w-96 p-1">
      @for ($i = 1; $i <= count($paymentStatus); $i++) <option value="{{ $i }}" {{
        sc_config('WebpayPlus_payment_status')==$i ? 'selected' : '' }}>
        {{ $paymentStatus[$i] }}
        </option>
        @endfor
    </select>
  </div>
  @if ($errors->has('payment-status'))
  <span class="text-sm text-red-400 text-right">
    <em class="fa fa-info-circle"></em> {{ $errors->first('payment-status') }}
  </span>
  @endif
  <button type="submit" class="border rounded w-20 px-2 py-1 bg-blue-400 hover:bg-blue-500 text-white">
    {{ trans($pathPlugin.'::lang.admin.webpay_plus_save_button') }}
  </button>
</form>
@endsection