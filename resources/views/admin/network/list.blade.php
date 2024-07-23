@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           <?php     $count  = 1  ; ?>
                            @forelse($data as $list)
                            <tr>
                                <td data-label="@lang('DPS No. | Plan')">
                                    <span class="font-weight-bold">{{ $count}}</span>

                                </td>
                                <td data-label="@lang('User')">
                                    <span class="font-weight-bold d-block">{{ $list->network_name }}</span>

                                </td>
                                  <td data-label="@lang('User')">
                                    <span class="font-weight-bold d-block">{{ $list->network_code }}</span>

                                </td>




                                <td data-label="@lang('Action')">
                                 <a href=" {{ route('admin.network.edit' , [ 'id'=>$list->id] ) }}" class="btn btn-primary">Edit</a>
                                 <a onClick="return confirm('Delete Network?')" href="{{ route('admin.network.delete' , [ 'id'=>$list->id] ) }}"  class="btn btn-danger"> Delete</a>
                                </td>
                            </tr>

                            <?php   $count++ ; ?>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
            @if($data->hasPages())
                <div class="card-footer py-4">
                {{ paginateLinks($data) }}
                </div>
            @endif
        </div><!-- card end -->
    </div>
</div>
@endsection


@push('breadcrumb-plugins')
    <form action="" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="Network Name" value="{{ request()->search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush
