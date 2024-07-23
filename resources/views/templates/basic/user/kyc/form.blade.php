@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="container">
        @if($general->kyc_form)
            <div class="row justify-content-center mt-4">
                <div class="col-md-8">
                    <div class="card border border--base">
                        <div class="card-header bg--base">
                            <h5 class="card-title m-0 p-2 text-white">@lang('Change Your Password')</h5>
                        </div>
                        <div class="card-body">
                            <form action="" method="post" class="register" enctype="multipart/form-data">
                                @csrf
                                @foreach($general->kyc_form as $item)
                                    @if($item->type == 'text')
                                        <div class="form-group">
                                            <label>
                                                @lang($item->field_name)
                                                @if($item->validation == "required")
                                                <span class="text--danger">*</span>
                                                @endif
                                            </label>
                                            <input type="text" name="{{ snakeCase($item->field_name) }}" class="form--control" {{ $item->validation }}>
                                        </div>
                                    @elseif($item->type == 'textarea')
                                        <div class="form-group">
                                            <label>
                                                @lang($item->field_name)
                                                @if($item->validation == "required")
                                                <span class="text--danger">*</span>
                                                @endif
                                            </label>
                                            <textarea type="text" name="{{ snakeCase($item->field_name) }}"  class="form--control" {{ $item->validation }}></textarea>
                                        </div>
                                    @elseif($item->type == 'file')
                                        <div class="form-group">
                                            <label>
                                                @lang($item->field_name)
                                                @if($item->validation == "required")
                                                <span class="text--danger">*</span>
                                                @endif
                                            </label>
                                            <input type="file" name="{{ snakeCase($item->field_name) }}" class="form--control" accept=".jpg,.jpeg,.png" {{ $item->validation }}>
                                        </div>
                                    @endif
                                @endforeach
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
