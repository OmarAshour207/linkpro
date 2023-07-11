@extends('dashboard.layouts.app')

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
                    <h1 class="m-0"> {{ __('Tickets') }} </h1>
                </div>
            </div>
        </div>

        <div class="container-fluid page__container">

            <div class="card card-form__body card-body">
                <form method="post" action="{{ route('tickets.store') }}">

                    @csrf
                    @include('dashboard.partials._errors')

                    <div class="form-group col-lg-6">
                        <label for="user"> {{ __('Companies') }}</label>
                        <select id="user" name="company_id" data-toggle="select" class="form-control company_id select2">
                            <option value="" selected> {{ __('Companies') }} </option>
                            @forelse($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}> {{ $company->name . ' -- ' . $company->email }} </option>
                            @empty
                                <option value="" selected> {{ __('No Records') }} </option>
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

                    <div class="contents">

                    </div>
                    <div class="text-right mb-5">
                        <input type="submit" class="btn btn-success" value="{{ __('Add') }}">
                    </div>
                </form>
            </div>
        </div>
        <!-- // END drawer-layout__content -->
    </div>
@endsection
@push('admin_scripts')
    <script src="{{ asset('dashboard/js/custom/floors.js') }}"></script>
    <script src="{{ asset('dashboard/js/custom/paths.js') }}"></script>
    <script src="{{ asset('dashboard/js/custom/offices.js') }}"></script>

    <script>
        $(document).ready(function () {
            $(document).on('change', '.offices', function () {
                var officeId = $('.offices option:selected').val();
                if(officeId) {
                    $.ajax({
                        url: "{{ route('office.contents') }}",
                        data_type: 'html',
                        method: "POST",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            office_id: officeId
                        }, success: function (data) {
                            $('.contents').html(data);
                        }
                    });
                } else {
                    $('.contents').html('');
                }
            });
        });
    </script>
@endpush

