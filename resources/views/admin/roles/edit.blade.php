@extends('layouts.admin.app', ['title' => 'Edit Role'])

@push('styles')
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
    /* Overlay styles */
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        z-index: 9999;
    }

    /* Loader styles */
    .loader-container {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        z-index: 10000;
        display: none;
    }
</style>    
@endpush

@section('content')
    <section>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Role</h3>
                        </div>
                        <form action="{{route('roles.update', [$role->id])}}" method="POST" autocomplete="off">
                            @csrf
                            <input type="hidden" name="id" value="{{$role->id}}">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card shadow mb-4">
                                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                            <div class="form-group w-100 mb-0">
                                                <h3>{{$role->title}}</h3>
                                            </div>
                                        </div>
                                        <div class="card-body px-5">
                                            <h3 class="h3 mb-4">Permisions</h3>

                                            @foreach(config('settings.permissions') as $key => $permissions)
                                                <div class="role-permission">
                                                    <h4 class="h5 mt-4">
                                                        <div class="custom-control custom-checkbox mr-3">
                                                            <input type="checkbox" id="{{$key}}" class=" custom-control-input " name="{{$key}}">
                                                            <label for="{{$key}}" class=" h5 custom-control-label">
                                                                {{$key}}
                                                            </label>
                                                        </div>
                                                    </h4>
                                                    <div class="form-group mb-4 d-flex permissions">
                                                        @foreach($permissions as $name => $permission)
                                                            <div class="custom-control custom-checkbox mr-3 small">
                                                                <input id="{{$permission}}" type="checkbox" class="permission custom-control-input " name="permission[{{$permission}}]" {!! in_array($permission ,$abilitiesarray) == true ?"checked":"" !!}  value="1" >
                                                                <label for="{{$permission}}" class="custom-control-label">
                                                                    {{$name}}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <hr class="sidebar-divider">
                                            @endforeach
                                            <div class="form-group mt-4">
                                                <button type="submit" style="width: 20%" class="btn btn-primary btn-block px-5 btn-update">
                                                    {{ __('Update') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Loader and Overlay -->
            <div class="overlay" id="overlay"></div>
            <div class="loader-container" id="loader">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('Admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>

    <script type="text/javascript"> 

        $('.role-permission h4 input[type="checkbox"]').change(function () {
            if($(this).is(':checked')){
                $(this).parents('.role-permission').find('input[type="checkbox"]').prop('checked', 'checked');
            }
            else{
                $(this).parents('.role-permission').find('input[type="checkbox"]').prop('checked', false);
            }
        });        
        
        $('.role-permission').each(function(index,item){
            let len = $(item).children('.form-group').find('input[type="checkbox"]').length;
            if($(item).children('.form-group').find('input[type="checkbox"]:checked').length == len){
                $(item).children('h4').find('input[type="checkbox"]').prop('checked', true);
            }
        });

        $('.permission').change(function () {

            let checkbox_length = $(this).parents('.permissions').find('input[type="checkbox"]').length;
            let checked_checkbox_length = $(this).parents('.permissions').find('input[type="checkbox"]:checked').length;
           
            if(checkbox_length === checked_checkbox_length){
                $(this).parents('.role-permission').find('h4 input[type="checkbox"]').prop('checked', 'checked');
            } else {
                $(this).parents('.role-permission').find('h4 input[type="checkbox"]').prop('checked', false);
            }
        }) ;

        $('.btn-update').on('click', function(e) {
            e.preventDefault();
            if (confirm("Are you sure you want to update?")) {
                $(this).closest('form').submit();
                $("#overlay,#loader").css({'display':'block'});
            }
        });
    </script>
@endpush