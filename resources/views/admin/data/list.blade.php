@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <form  onsubmit="return confirm('Are You sure?')"  action="{{  route('admin.default_price') }}">
            <div class="col-sm-2">
                <label><strong>Selling Increase By:  </strong></label>
            </div>
            <div class="col-sm-2">

            <input  value="50"   name="selling"  class="form-control" />

            </div>

            <div class="col-sm-2">
                <label><strong>Seller Increase By:  </strong></label>
            </div>
            <div class="col-sm-2">
            <input  value="40"   name="seller"  class="form-control" />
            </div>

<br/>

      <button    href=""  class="btn btn-primary">  Update  Price </button>
        </form>
      <br/> <br/>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Network</th>
                                <th>Plan </th>
                                <th>Plan Type</th>
                                <th>Plan Validity</th>
                                <th>Plan Price</th>
                                 <th>Reseller Price</th>
                                 <th>Selling  Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           <?php     $count  = 1  ; ?>
                            @forelse($data as $list)
                            <tr>
                                <td data-label="No">
                                    <span class="font-weight-bold">{{ $count}}</span>

                                </td>
                                <td data-label="Name">
                      <?php $netw =  App\Models\network::find($list->network);  ?>
                                    <span class="font-weight-bold d-block">
                                    @if(isset($netw->id))
                                    {{ $netw->network_name }}

                                    @endif
                                    </span>

                                </td>
                                <td data-label="Plan">
                                    <span class="font-weight-bold d-block">{{ $list->plan }}</span>

                                </td>
                                  <td data-label="Plan_type">
                                    <span class="font-weight-bold d-block">{{ $list->plan_type }}</span>

                                </td>                         <td data-label="monthly_validate">
                                    <span class="font-weight-bold d-block">{{ $list->month_validate }}</span>

                                </td>

                              <td data-label="Default Price">
                           <span class="font-weight-bold d-block">   #{{  number_format( $list->plan_amount  , 2)   }}   </span>

                                </td>
                                   <td data-label="Reseller Price">
                           <span class="font-weight-bold d-block">   #{{  number_format( $list->reseller_price  , 2)   }}   </span>

                                </td>
                                    <td data-label="Actual Price">
                           <span class="font-weight-bold d-block">   #{{  number_format( $list->default_price  , 2)   }}   </span>

                                </td>




                                <td data-label="@lang('Action')">
                                 <a href="/iamadmin@6019/edit_data/{{$list->id}}" class="btn btn-primary">Edit</a>
                                 <a onClick="return confirm('Delete?')" href="/iamadmin@6019/delete_data/{{$list->id}}"  class="btn btn-danger"> Delete</a>
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
            <input type="text" name="search" class="form-control" placeholder="Data" value="{{ request()->search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush
