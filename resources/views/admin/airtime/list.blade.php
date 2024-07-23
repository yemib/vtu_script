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
                                <th>Network</th>
                                <th>Type</th>
                                <th>Discount  %</th>
                                <th>Resseller Discount %</th>
                                <th>Actual Discount %</th>
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
                      <?php $netw =  App\Models\network::where( "network_code",   $list->network)->first();  ?>
                                    <span class="font-weight-bold d-block">
                                    @if(isset($netw->id))
                                    {{ $netw->network_name }}

                                    @endif
                                    </span>

                                </td>
                                  <td data-label="Type">
                                    <span class="font-weight-bold d-block">{{ $list->type }}</span>

                                </td>

                              <td data-label="Discount">
                           <span class="font-weight-bold d-block">   {{   $list->discount    }}   </span>

                                </td>


                                   <td data-label="Reseller Discount">
                           <span class="font-weight-bold d-block">   {{   $list->ressller_discount    }}   </span>

                                </td>

                                   <td data-label="Actual Discount">
                           <span class="font-weight-bold d-block">   {{   $list->actual_discount    }}   </span>

                                </td>




                                <td data-label="Action">
                                 <a href="/iamadmin@6019/edit_airtime/{{$list->id}}" class="btn btn-primary">Edit</a>
                                 <a onClick="return confirm('Delete?')" href="/iamadmin@6019/delete_airtime/{{$list->id}}"  class="btn btn-danger"> Delete</a>
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
            <input type="text" name="search" class="form-control" placeholder="Airtime" value="{{ request()->search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush
