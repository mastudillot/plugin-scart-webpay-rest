@extends($pathPlugin.'::Admin.layout')
@section('content')
    <div class="tbk-content">
        <div class="card-header with-border">
            <div class="card-tools">
                @if (!empty($topMenuRight) && count($topMenuRight))
                    @foreach ($topMenuRight as $item)
                        <div class="menu-right">
                            @php
                                $arrCheck = explode('view::', $item);
                            @endphp
                            @if (count($arrCheck) == 2)
                                @if (view()->exists($arrCheck[1]))
                                    @include($arrCheck[1])
                                @endif
                            @else
                                {!! trim($item) !!}
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="float-left">
                @if (!empty($topMenuLeft) && count($topMenuLeft))
                    @foreach ($topMenuLeft as $item)
                        <div class="menu-left">
                            @php
                                $arrCheck = explode('view::', $item);
                            @endphp
                            @if (count($arrCheck) == 2)
                                @if (view()->exists($arrCheck[1]))
                                    @include($arrCheck[1])
                                @endif
                            @else
                                {!! trim($item) !!}
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
            <!-- /.box-tools -->
        </div>
        <div class="card-body p-0" id="pjax-container">
            <div class="table-responsive">
                <table class="table table-hover box-body text-wrap table-bordered">
                    <thead>
                        <tr>
                            @foreach ($tableHeader as $key => $th)
                                <th>{!! $th !!}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tableRows as $keyRow => $tr)
                            <tr>
                                @foreach ($tr as $key => $trTd)
                                    <td>{!! $trTd !!}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="block-pagination clearfix m-10">
                <div class="ml-3 float-left">
                    {!! $resultItems ?? '' !!}
                </div>
                <div class="pagination pagination-sm mr-3 float-right">
                    {!! $pagination ?? '' !!}
                </div>
            </div>
        </div>
    </div>
@endsection
