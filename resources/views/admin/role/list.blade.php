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
                                <th>Profit %</th>
                                 
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
                 <span class="font-weight-bold d-block">{{ $list->name }}</span>
                                
                                </td>                        
                                
                 <td data-label="Profit">
                           <span class="font-weight-bold d-block">   {{   $list->profit   }}   </span>
                                
                                </td>
             
                           

                                <td data-label="Action">
                                 <a href="/iamadmin@6019/edit_role/{{$list->id}}" class="btn btn-primary">Edit</a>
                                 <a onClick="return confirm('Delete?')" href="/iamadmin@6019/delete_role/{{$list->id}}"  class="btn btn-danger"> Delete</a>
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
   
   <!---
    <form action="" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="Bill" value="{{ request()->search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>  -->
    
@endpush
