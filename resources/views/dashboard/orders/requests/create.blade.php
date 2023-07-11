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
                    <h1 class="m-0"> {{ __('Requests') }} </h1>
                </div>
            </div>
        </div>

        <div class="container-fluid page__container">

            <div class="card card-form__body card-body">
                <form method="post" action="{{ route('requests.store') }}">

                    @csrf
                    @include('dashboard.partials._errors')

                    <div class="row no-gutters">
                        <div class="col card-form__body card-body">
                            <div class="row">
                                <div class="form-group col">
                                    <label for="services"> {{ __('Services') }}</label> <br>
                                    <select id="services" name="service_id" data-toggle="select" class="form-control select2">
                                        <option value="" selected> {{ __('Services') }} </option>
                                        @forelse($services as $service)
                                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}> {{ $service->name }} </option>
                                        @empty
                                            <option value="" selected> {{ __('No Records') }} </option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="form-group col">
                                    <div class="form-group">
                                        <label for="notes"> {{ __("Notes") }}</label>
                                        <input id="notes" name="notes" value="{{ old("notes") }}" dir="auto" type="text" class="form-control" placeholder="{{ __("Notes") }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <div class="col card-form__body card-body">
                            <div class="row">
                                <div class="form-group col">
                                    <label class="text-muted ml-1">
                                        <i class="material-icons icon-18pt">today</i>
                                    </label>
                                    <label class="text-label" for="date">{{ __('Date') }}</label>
                                    <input id="date" name="date" value="{{ old('date') }}" type="text" class="form-control datepicker" placeholder="{{ __('Select Date') }}" data-toggle="flatpickr" data-flatpickr-alt-format="F j, Y" data-flatpickr-date-format="Y-m-d">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <div class="col card-form__body card-body">
                            <div class="row">
                                <div class="form-group col">
                                    <label class="text-muted ml-1">
                                        <i class="fa fa-clock"></i>
                                    </label>
                                    <label class="text-label" for="start_time">{{ __('Start Time') }}</label>
                                    <input id="start_time" name="start_time" value="{{ old('start_time') }}" type="text" class="form-control" placeholder="{{ __('Select Time') }}" data-toggle="flatpickr" data-flatpickr-enable-time="true" data-flatpickr-no-calendar="true" data-flatpickr-alt-format="H:i" data-flatpickr-date-format="H:i">
                                </div>
                                <div class="form-group col">
                                    <label class="text-muted ml-1">
                                        <i class="fa fa-clock"></i>
                                    </label>
                                    <label for="end_time" class="text-label">{{ __('End Time') }}</label>
                                    <input id="end_time" value="{{ old('end_time') }}" name="end_time" type="text" class="form-control" placeholder="{{ __('Select Time') }}" data-toggle="flatpickr" data-flatpickr-enable-time="true" data-flatpickr-no-calendar="true" data-flatpickr-alt-format="H:i" data-flatpickr-date-format="H:i">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="user"> {{ __('Users') }}</label> <br>
                        <select id="user" name="user_id" data-toggle="select" class="form-control select2">
                            <option value="" selected> {{ __('Users') }} </option>
                            @forelse($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}> {{ $user->name . ' -- ' . $user->email }} </option>
                            @empty
                                <option value="" selected> {{ __('No Records') }} </option>
                            @endforelse
                        </select>
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
    <script>
        $(".datepicker" ).flatpickr({
            minDate: "today",
            locale: "{{ session()->get('locale') }}"
        });
    </script>
@endpush

