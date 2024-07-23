{{--   modal----}}
<div class="modal fade bd-example-modal-lg" id="cronModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('Cron Job Setting Instruction')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <p class="cron-p-style"> @lang('To automate Loan, DPS & FDR installments')
                            <code> @lang('cron job') </code> @lang('on your server. ')
                            </p>
                    </div>
                    <div class="col-md-12 form-group">
                        <label>@lang('Loan Cron Command')</label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg copyText" value="curl -s {{route('cron.loan')}}"  readonly>

                            <div class="input-group-append">
                                <button class="input-group-text btn--success copyBtn"> @lang('COPY')</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 form-group">
                        <label>@lang('DPS Cron Command')</label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg copyText" value="curl -s {{route('cron.dps')}}"  readonly>

                            <div class="input-group-append">
                                <button class="input-group-text btn--success copyBtn"> @lang('COPY')</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 form-group">
                        <label>@lang('FDR Cron Command')</label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg copyText" value="curl -s {{route('cron.fdr')}}"  readonly>

                            <div class="input-group-append">
                                <button class="input-group-text btn--success copyBtn"> @lang('COPY')</button>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="mt-3">@lang('Set the cron time as minimum as possible. Twice per hour is ideal')</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>

@push('script')
    @if(
        Carbon\Carbon::parse($general->last_loan_cron)->diffInSeconds()>=5400 ||
        Carbon\Carbon::parse($general->last_dps_cron)->diffInSeconds()>=5400 ||
        Carbon\Carbon::parse($general->last_fdr_cron)->diffInSeconds()>=5400 ||
        !$general->last_loan_cron ||
        !$general->last_dps_cron ||
        !$general->last_fdr_cron
    )
        <script>
            'use strict';
            (function($){
                $("#cronModal").modal('show');

                $('.copyBtn').on('click', function(){
                    // var copyText = $(this).parent().find('input');
                    var copyText = $(this).parents('.input-group').find('.copyText')[0];

                    copyText.select();
                    copyText.setSelectionRange(0, 99999)
                    document.execCommand("copy");
                    notify('success', 'Url copied successfully ' + copyText.value);
                });
            })(jQuery)

        </script>
    @endif
@endpush

