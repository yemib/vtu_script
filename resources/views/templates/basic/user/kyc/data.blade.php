@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="container">
        @if(auth()->user()->kyc_data)
            <div class="row justify-content-center mt-4">
                <div class="col-md-8">
                    <div class="card border border--base">
                        <div class="card-body">
                            @if(auth()->user()->kycv)
                            <div class="d-flex justify-content-end mb-3">
                                <span class="badge badge--success"> <i class="fas fa-check"></i> @lang('Approved')</span>
                            </div>
                            @endif
                            <ul class="list-group">
                                @foreach(auth()->user()->kyc_data as $key=>$item)
                                    @if($item->type == 'file')
                                        <li class="list-group-item d-flex flex-wrap justify-content-between">
                                            <span>@lang(keyToTitle($key))</span>
                                            <img class="w-25" src="{{ asset(imagePath()['verify']['user_kyc']['path']).'/'.$item->value}}">
                                        </li>
                                    @else
                                        <li class="list-group-item d-flex flex-wrap justify-content-between">
                                            <span>@lang(keyToTitle($key))</span>
                                            {{ $item->value }}
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
