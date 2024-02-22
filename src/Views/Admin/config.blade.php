@extends($pathPlugin.'::Admin.layout')
@section('content')
<form action="{{ sc_route_admin('admin_webpayplus.config.save') }}" method="post"
  class="tbk-content d-flex flex-column">
  @csrf
  <div class="d-flex flex-row">
    <label for="environment">
      {{ trans($pathPlugin.'::lang.admin.environment') }}:
    </label>
    <select name="environment" id="environment" class="tbk-form-input">
      <option value="integration" {{ !$isProduction ? 'selected' : '' }}>
        {{ trans($pathPlugin.'::lang.admin.integration_mode') }}
      </option>
      <option value="production" {{ $isProduction ? 'selected' : '' }}>
        {{ trans($pathPlugin.'::lang.admin.production_mode') }}
      </option>
    </select>
  </div>
  @if ($errors->has('environment'))
  <span class="tbk-form-error">
    <em class="fa fa-info-circle"></em> {{ $errors->first('environment') }}
  </span>
  @endif
  <div class="d-flex flex-row">
    <label for="commerce-code">
      {{ trans($pathPlugin.'::lang.admin.commerce_code') }}:
    </label>
    <input type="text" name="commerce-code" id="commerce-code" class="tbk-form-input" placeholder="597012345678"
      value="{{ $isProduction ? $commerceCode : ''}}" {{ !$isProduction ? 'disabled' : '' }}>
  </div>
  @if ($errors->has('commerce-code'))
  <span class="tbk-form-error">
    <em class="fa fa-info-circle"></em> {{ $errors->first('commerce-code') }}
  </span>
  @endif
  <div class="d-flex flex-row">
    <label for="api-key">
      {{ trans($pathPlugin.'::lang.admin.api_key') }}:
    </label>
    <input type="password" name="api-key" id="api-key" class="tbk-form-input" value="{{ $isProduction ? $apiKey : ''}}"
      {{ !$isProduction ? 'disabled' : '' }}>
  </div>
  @if ($errors->has('api-key'))
  <span class="tbk-form-error">
    <em class="fa fa-info-circle"></em> {{ $errors->first('api-key') }}
  </span>
  @endif
  <div class="d-flex flex-row">
    <label for="order-status-success">
      {{ trans($pathPlugin.'::lang.admin.order_status_success') }}:
    </label>
    <select name="order-status-success" id="order-status-success" class="tbk-form-input">
      @for ($i = 1; $i <= count($statusOrder); $i++) <option value="{{ $i }}" {{ $orderStatusSuccess==$i ? 'selected'
        : '' }}>
        {{ $statusOrder[$i] }}
        </option>
        @endfor
    </select>
  </div>
  @if ($errors->has('order-status-succes'))
  <span class="tbk-form-error">
    <em class="fa fa-info-circle"></em> {{ $errors->first('order-status-succes') }}
  </span>
  @endif
  <div class="d-flex flex-row">
    <label for="order-status-failed">
      {{ trans($pathPlugin.'::lang.admin.order_status_failed') }}:
    </label>
    <select name="order-status-failed" id="order-status-failed" class="tbk-form-input">
      @for ($i = 1; $i <= count($statusOrder); $i++) <option value="{{ $i }}" {{ $orderStatusFailed==$i ? 'selected'
        : '' }}>
        {{ $statusOrder[$i] }}
        </option>
        @endfor
    </select>
  </div>
  @if ($errors->has('order-status-failed'))
  <span class="tbk-form-error">
    <em class="fa fa-info-circle"></em> {{ $errors->first('order-status-failed') }}
  </span>
  @endif
  <div class="d-flex flex-row">
    <label for="payment-status">
      {{ trans($pathPlugin.'::lang.admin.payment_status') }}:
    </label>
    <select name="payment-status" id="payment-status" class="tbk-form-input">
      @for ($i = 1; $i <= count($paymentStatus); $i++) <option value="{{ $i }}" {{ $orderPaymentStatus==$i ? 'selected'
        : '' }}>
        {{ $paymentStatus[$i] }}
        </option>
        @endfor
    </select>
  </div>
  @if ($errors->has('payment-status'))
  <span class="tbk-form-error">
    <em class="fa fa-info-circle"></em> {{ $errors->first('payment-status') }}
  </span>
  @endif
  <button type="submit" class="tbk-form-button">
    {{ trans($pathPlugin.'::lang.admin.save_button') }}
  </button>
</form>
@endsection

@push('styles')
<style type="text/css">
  .tbk-content div {
    --tw-space-y-reverse: 0;
    margin-top: calc(1.25rem * calc(1 - var(--tw-space-y-reverse)));
    margin-bottom: calc(1.25rem * var(--tw-space-y-reverse));
  }

  .tbk-content label {
    width: 10rem;
    margin: 0px;
    overflow-wrap: break-word;
  }

  .tbk-form-input {
    border: 1px solid #dee2e6 !important;
    border-radius: 0.25rem;
    margin-left: auto;
    width: 24rem;
    padding: 0.25rem;
  }

  .tbk-form-error {
    font-size: 0.875rem;
    line-height: 1.25rem;
    color: rgb(248 113 113);
    text-align: right;
  }

  .tbk-form-button {
    width: 5rem;
    padding-left: 0.5rem;
    padding-right: 0.5rem;
    padding-top: 0.25rem;
    padding-bottom: 0.25rem;
    border-radius: 0.25rem !important;
    border: 1px solid #dee2e6 !important;
    background-color: rgb(96 165 250);
    color: white;
  }

  .tbk-form-button:hover {
    background-color: rgb(59 130 246);
  }

  @media (min-width: 1024px) {
    .tbk-content {
      width: 83.333333%;
    }
  }

  @media (min-width: 1280px) {
    .tbk-content {
      width: 50%;
    }
  }
</style>
@endpush
