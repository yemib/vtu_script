@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">

                @if($user->kyc_data)
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($user->kyc_data as $key=>$item)
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

                    @if(!$user->kycv)
                    <div class="d-flex flex-wrap justify-content-end mt-3">
                        <button class="btn btn--danger mr-3 reject-btn">@lang('Reject')</button>
                        <button class="btn btn--success approve-btn">@lang('Approve')</button>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Approve Modal --}}
    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    @lang('Are you sure to approve this documents?')
                </div>
                <div class="modal-footer">
                    <form action="{{ route('admin.users.detail.kyc.approve', $user->id) }}">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--success">@lang('Yes')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    @lang('Are you sure to reject this documents?')
                </div>
                <div class="modal-footer">
                    <form action="{{ route('admin.users.detail.kyc.reject', $user->id) }}">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--danger">@lang('Yes')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        $('.approve-btn').on('click', function(){
            let modal = $('#approveModal');
            modal.modal('show');
        });

        $('.reject-btn').on('click', function(){
            let modal = $('#rejectModal');

            modal.modal('show');
        });
    </script>
@endpush
