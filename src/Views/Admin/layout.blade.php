@extends($templatePathAdmin.'layout')
@section('main')
<div class="p-3">
  <h1 class="tbk-title">{{ trans($pathPlugin.'::lang.admin.title') }}</h1>
  <div class="tbk-content d-flex flex-row">
    {{-- Navbar --}}
    <div class="tbk-nav">
      <div class="nav-title d-flex align-items-center text-white border-bottom">Configuración</div>
      <ul class="d-flex flex-column aling-content-center bg-white">
        <li class="{{ $view == 'configWebpay' ? 'active' : '' }}">
          <a href="{{ sc_route_admin('admin_webpayplus.index', ['option' => 'config']) }}">
            Webpay Plus <em class="icon fas fa-arrow-right"></em>
          </a>
        </li>
        <li class="{{ $view == 'transactionsWebpay' ? 'active' : '' }}">
          <a href="{{ sc_route_admin('admin_webpayplus.index', ['option' => 'transactions']) }}">
            {{ trans($pathPlugin.'::lang.admin.navbar.transactions') }} <em class="icon fas fa-arrow-right"></em>
          </a>
        </li>
        <li class="{{ $view == 'healthcheck' ? 'active' : '' }}">
          <a href="{{ sc_route_admin('admin_webpayplus.index', ['option' => 'healthcheck']) }}">
            {{ trans($pathPlugin.'::lang.admin.navbar.healthcheck') }} <em class="icon fas fa-arrow-right"></em>
          </a>
        </li>
      </ul>
    </div>
    {{-- Navbar --}}
    {{-- Content --}}
    <div class="w-100" id="content">
      @yield('content')
    </div>
    {{-- Content --}}
  </div>
</div>
@endsection

@push('styles')
<style type="text/css">
.tbk-title {
  margin-top: -3.5rem;
  font-size: 2.25rem;
  line-height: 2.5rem;
}
  .tbk-content {
    gap: 1rem;
    margin-top: 2.5rem;
  }
  .tbk-nav {
    width: 12.5rem;
  }
  .tbk-nav .nav-title {
    background: #6B1A6B;
    border-radius: 0.75rem 0.75rem 0 0;
    padding: 0.75rem 0.75rem 0.75rem 0.875rem
  }

  .tbk-nav ul {
    list-style: none;
    margin: 0;
    border-radius: 0 0 0.75rem 0.75rem;
    padding: 0;
  }

  .tbk-nav ul li {
    margin: 0;
    padding: 0.75rem;
  }

  .tbk-nav ul li a {
    vertical-align: middle;
    display: block;
    color: #333 !important;
    text-decoration: none;
    /* padding: 15px; */
    /* border-bottom: solid 1px #ccc; */
    transition: .5s;
  }

  .tbk-nav ul li .icon {
    float: right;
    opacity: 0;
    transition: .5s;
    right: 20px;
    position: relative;
    padding: 0.25rem;
  }

  .tbk-nav ul li.active {
    font-weight: bold;
  }

  .tbk-nav ul li.active a {
    color: #6B1A6B !important;
  }

  .tbk-nav ul li.active a:hover {
    color: #6B1A6B !important;
  }

  .tbk-nav ul li a:hover {
    color: #0a4b78 !important;
  }

  .tbk-nav ul li a:hover .icon {
    opacity: 1;
    right: 0;
  }
</style>
@endpush

@push('scripts')
<script type="text/javascript">
  $(document).ready(() => {
    document.title = '{{sc_config_admin('ADMIN_TITLE')}} | Webpay Plugin';
});

$('#content').on('change', '#environment', () => {
    let option = $('#environment option:selected').val();

    if(option === 'production') {
      $('#commerce-code').prop('disabled', false);
      $('#api-key').prop('disabled', false);
    }
    else {
      $('#commerce-code').prop('disabled', true);
      $('#api-key').prop('disabled', true);
    }
});

</script>
@endpush