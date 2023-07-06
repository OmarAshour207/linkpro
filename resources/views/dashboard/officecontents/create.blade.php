@extends('dashboard.layouts.app')

@push('admin_scripts')
    <script src="{{ asset('dashboard/js/custom/floors.js') }}"></script>
    <script src="{{ asset('dashboard/js/custom/paths.js') }}"></script>
    <script src="{{ asset('dashboard/js/custom/offices.js') }}"></script>

    <script>
        $(".new_content").click( function (e) {
            e.preventDefault();
            $('.contents').after(
                '<div class="row no-gutters">' +
                    '<div class="col card-form__body card-body">' +
                        '<div class="row">'+
                            '<div class="form-group col">'+
                                '<label for="content">{{ __('Content') }}</label>'+
                                '<input id="content" name="content[]" type="text" class="form-control" placeholder="{{ __('Content') }}">'+
                            '</div>'+
                            '<div class="form-group col">'+
                                '<label for="note">{{ __('Note') }}</label>'+
                                '<input id="note" name="note[]" type="text" class="form-control" placeholder="{{ __('Note') }}">'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>');
        });
    </script>
@endpush

@section('content')
    <div class="mdk-drawer-layout__content page">
        <div class="container-fluid page__heading-container">
            <div class="page__heading d-flex align-items-center">
                <div class="flex">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"><i class="material-icons icon-20pt">home</i> {{ __('Home') }} </a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Create') }}</li>
                        </ol>
                    </nav>
                    <h1 class="m-0"> {{ __('Office Contents') }} </h1>
                </div>
            </div>
        </div>

        <div class="container-fluid page__container">

            <div class="card card-form__body card-body">
                <form method="post" action="{{ route('contents.store') }}">

                    @csrf
                    @include('dashboard.partials._errors')

                    <div class="form-group col-lg-6">
                        <label for="role"> {{ __('Company') }}</label> <br>
                        <select id="role" name="user_id" data-toggle="select" class="form-control select2 company_id">
                            <option value="" selected> {{ __('Company Name') }} </option>
                            @forelse($companies as $company)
                                <option value="{{ $company->id }}" {{ old('user_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @empty
                            @endforelse
                        </select>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="floor_id"> {{ __('Floors') }}</label> <br>
                        <select id="floor_id" name="floor_id" data-toggle="select" class="form-control select2 floors">
                            <option value="" selected> {{ __('Floor Title') }} </option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label for="path_id"> {{ __('Paths') }}</label> <br>
                            <select id="path_id" name="path_id" data-toggle="select" class="form-control select2 paths">
                                <option value="" selected> {{ __('Path Title') }} </option>
                            </select>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="office_id"> {{ __('Offices') }}</label> <br>
                            <select id="office_id" name="office_id" data-toggle="select" class="form-control select2 offices">
                                <option value="" selected> {{ __('Office Title') }} </option>
                            </select>
                        </div>
                    </div>

                    <div class="row no-gutters contents">
                        <div class="col card-form__body card-body">
                            <div class="row">
                                <div class="form-group col">
                                    <label for="content">{{ __('Content') }}</label>
                                    <input id="content" name="content[]" type="text" class="form-control" placeholder="{{ __('Content') }}">
                                </div>
                                <div class="form-group col">
                                    <label for="note">{{ __('Note') }}</label>
                                    <input id="note" name="note[]" type="text" class="form-control" placeholder="{{ __('Note') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-info ml-3 new_content" id="new_content_btn">
                        {{ __('Create new Content') }} <i class="material-icons">add</i>
                    </button>


                    <div class="text-right mb-5">
                        <input type="submit" class="btn btn-success" value="{{ __('Add') }}">
                    </div>
                </form>
            </div>
        </div>
        <!-- // END drawer-layout__content -->
    </div>
@endsection
