@extends('dashboard.layouts.app')

@push('admin_scripts')
    <script src="{{ asset('dashboard/js/custom/floors.js') }}"></script>
    <script src="{{ asset('dashboard/js/custom/paths.js') }}"></script>
    <script>
        $(".new_title").click( function (e) {
            e.preventDefault();
            $('.new_title').before(
                '<div class="form-group titles">'+
                    '<label for="title"> {{ __("Title") }}</label>'+
                    '<input id="title" name="title[]" dir="auto" type="text" class="form-control" placeholder="{{ __("Title") }}" value="{{ old("title") }}">'+
                '</div>'
            );
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
                    <h1 class="m-0"> {{ __('Offices') }} </h1>
                </div>
            </div>
        </div>

        <div class="container-fluid page__container">

            <div class="card card-form__body card-body">
                <form method="post" action="{{ route('offices.store') }}">

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

                    <div class="form-group col-lg-6">
                        <label for="path_id"> {{ __('Paths') }}</label> <br>
                        <select id="path_id" name="path_id" data-toggle="select" class="form-control select2 paths">
                            <option value="" selected> {{ __('Path Title') }} </option>
                        </select>
                    </div>

                    <div class="form-group titles">
                        <label for="title"> {{ __("Title") }}</label>
                        <input id="title" name="title[]" dir="auto" type="text" class="form-control" placeholder="{{ __("Title") }}" value="{{ old("title") }}">
                    </div>

                    <button class="btn btn-info ml-3 new_title">
                        {{ __('Create new Title') }} <i class="material-icons">add</i>
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
