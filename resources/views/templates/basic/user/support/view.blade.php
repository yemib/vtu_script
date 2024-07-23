@extends($activeTemplate. $extends)

@section('content')
    <div class="container @guest pt-100 pb-100 @endguest">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card border border--base">
                    <div class="card-header bg--base d-flex flex-wrap justify-content-between align-items-center mb-3">
                        <h5 class="card-title text-white m-0">
                            @if($my_ticket->status == 0)
                                <span class="badge badge--success">@lang('Open')</span>
                            @elseif($my_ticket->status == 1)
                                <span class="badge badge--primary">@lang('Answered')</span>
                            @elseif($my_ticket->status == 2)
                                <span class="badge badge--warning">@lang('Replied')</span>
                            @elseif($my_ticket->status == 3)
                                <span class="badge badge--danger">@lang('Closed')</span>
                            @endif
                            [@lang('Ticket')#{{ $my_ticket->ticket }}] {{ $my_ticket->subject }}
                        </h5>
                        <div>

                            @if($my_ticket->status != 3)
                                <button class="btn btn--danger btn-sm close-btn" type="button" data-bs-toggle="tooltip" title="@lang('Close')"><i class="la la-times-circle"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @if($my_ticket->status != 4)
                            <form method="post" action="{{ route('ticket.reply', $my_ticket->id) }}" enctype="multipart/form-data">
                                @csrf
                                    <div class="form-group">
                                        <textarea name="message" class="form-control" id="inputMessage" placeholder="@lang('Your Reply')" rows="4" cols="10"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputAttachments">@lang('Attachments')</label>
                                        <input type="file" name="attachments[]" accept=".jpg, .jpeg, .png, .docx, .doc, .pdf" id="inputAttachments" class="form-control" accept="" />
                                        <div id="fileUploadsContainer"></div>
                                        <p class="my-2 ticket-attachments-message text-muted">
                                            @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'), .@lang('pdf'), .@lang('doc'), .@lang('docx')
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <a href="javascript:void(0)" class="btn btn-sm btn--success addFile">
                                            <i class="la la-plus"></i>
                                        </a>
                                    </div>
                                <div class="row justify-content-between">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn w-100 btn--base" name="replayTicket" value="1">
                                            <i class="la la-reply"></i> @lang('Reply')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="card mt-4 border">
                    <div class="card-body ">
                            @foreach($messages as $message)
                                @if($message->admin_id == 0)
                                    <div class="row border border-info border-radius-3 my-3 py-3 mx-2">
                                        <div class="col-md-3 border-right text-right">
                                            <h5 class="my-3">{{ $message->ticket->name }}</h5>
                                        </div>
                                        <div class="col-md-9">
                                            <p class="text-muted font-weight-bold my-3">
                                                @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                            <p>{{$message->message}}</p>
                                            @if($message->attachments->count() > 0)
                                                <div class="mt-2">
                                                    @foreach($message->attachments as $k=> $image)
                                                        <a href="{{route('ticket.download',encrypt($image->id))}}" class="mr-3"><i class="fa fa-file"></i>  @lang('Attachment') {{++$k}} </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="row border border-warning border-radius-3 my-3 py-3 mx-2">
                                        <div class="col-md-3 border-right text-right">
                                            <h5 class="my-3">{{ $message->admin->name }}</h5>
                                            <p class="lead text-muted">@lang('Staff')</p>
                                        </div>
                                        <div class="col-md-9">
                                            <p class="text-muted font-weight-bold my-3">
                                                @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                            <p>{{$message->message}}</p>
                                            @if($message->attachments()->count() > 0)
                                                <div class="mt-2">
                                                    @foreach($message->attachments as $k=> $image)
                                                        <a href="{{route('ticket.download',encrypt($image->id))}}" class="mr-3"><i class="fa fa-file"></i>  @lang('Attachment') {{++$k}} </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>

@push('modal')
    <div class="modal fade" id="DelModal" tabindex="-1" aria-labelledby="DelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="{{ route('ticket.reply', $my_ticket->id) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"> @lang('Confirmation')!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @lang('Are you sure to close this support ticket?')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-md" data-bs-dismiss="modal">
                            @lang('No')
                        </button>
                        <button type="submit" class="btn custom--bg text-white btn-md" name="replayTicket" value="2">@lang("Yes")
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@endsection

@push('script')
    <script>
        (function ($) {
            "use strict";
            $('.delete-message').on('click', function (e) {
                $('.message_id').val($(this).data('id'));
            });


            $('.close-btn').on('click', function (){
                $('#DelModal').modal('show');
            })

            $('.addFile').on('click',function(){
                $("#fileUploadsContainer").append('<input type="file" name="attachments[]" class="form-control mt-3" required />')
            });
        })(jQuery);

    </script>
@endpush

@push('bottom-menu')
    <li><a href="{{ route('user.profile.setting') }}">@lang('Profile')</a></li>
    @if($general->modules->referral_system)
        <li><a href="{{ route('user.referral.users') }}">@lang('Referral')</a></li>
    @endif
    <li><a href="{{ route('user.twofactor') }}">@lang('2FA Security')</a></li>
    <li><a href="{{ route('user.change.password') }}">@lang('Change Password')</a>
    </li>
    <li><a href="{{ route('user.transaction.history') }}">@lang('Transactions')</a></li>
    <li><a class="active" href="{{ route('ticket') }}">@lang('Support Tickets')</a></li>
@endpush
