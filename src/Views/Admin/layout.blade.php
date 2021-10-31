@extends($templatePathAdmin.'layout')
@section('main')
<div class="p-3">
  <h1 class="text-4xl -mt-14">{{ trans($pathPlugin.'::lang.admin.title') }}</h1>
  <div class="flex flex-row gap-4 mt-10">
    {{-- Navbar --}}
    <div class="tbk-nav w-52">
      <div class="nav-title flex items-center text-white rounded-t-xl border-b py-3 pr-3 pl-3.5">Configuración</div>
      <ul class="flex flex-col content-center bg-white rounded-b-xl">
        <li class="p-3 {{ $view == 'configWebpay' ? 'active' : '' }}">
          <a href="{{ sc_route_admin('admin_webpayplus.index', ['option' => 'config']) }}">
            Webpay Plus <i class="icon fas fa-arrow-right"></i>
          </a>
        </li>
        <li class="p-3 {{ $view == 'transaction' ? 'active' : '' }}">
          <a href="{{ sc_route_admin('admin_webpayplus.index', ['option' => 'transactions']) }}">
            {{ trans($pathPlugin.'::lang.admin.navbar.transactions') }} <i class="icon fas fa-arrow-right"></i>
          </a>
        </li>
        <li class="p-3 {{ $view == 'healthcheck' ? 'active' : '' }}">
          <a href="{{ sc_route_admin('admin_webpayplus.index', ['option' => 'healthcheck']) }}">
            {{ trans($pathPlugin.'::lang.admin.navbar.healthcheck') }} <i class="icon fas fa-arrow-right"></i>
          </a>
        </li>
      </ul>
    </div>
    {{-- Navbar --}}
    {{-- Content --}}
    <div class="w-full" id="content">
      @yield('content')
    </div>
    {{-- Content --}}
  </div>
</div>
@endsection

@push('styles')
<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
<style type="text/css">
  .tbk-nav .nav-title {
    background: #6B1A6B;
  }

  .tbk-nav ul {
    list-style: none;
    margin: 0;
  }

  .tbk-nav ul li {
    margin: 0;
    padding: 0;
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