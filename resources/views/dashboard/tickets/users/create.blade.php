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
                    <h1 class="m-0"> {{ __('Users Tickets') }} </h1>
                </div>
            </div>
        </div>

        <div class="container-fluid page__container">

            <div class="card card-form__body card-body">
                <form method="post" action="{{ route('tickets.store', 'users') }}">

                    @csrf
                    @include('dashboard.partials._errors')

                    <div class="form-group">
                        <label for="content"> {{ __("Content") }}</label>
                        <input id="content" name="content" dir="auto" type="text" class="form-control" placeholder="{{ __("Content") }}" value="{{ old("content") }}">
                    </div>

                    <div class="row no-gutters">
                        <div class="col card-form__body card-body">
                            <div class="row">
                                <div class="form-group col">
                                    <label class="text-muted ml-1">
                                        <i class="material-icons icon-18pt">today</i>
                                    </label>
                                    <label class="text-label" for="date_from">{{ __('Date From') }}</label>
                                    <input id="date_from" name="date_from" type="text" class="form-control datepicker" placeholder="{{ __('Select Date') }}" data-toggle="flatpickr" data-flatpickr-alt-format="F j, Y" data-flatpickr-date-format="Y-m-d" value="{{ old('date_from') }}">
                                </div>
                                <div class="form-group col">
                                    <label class="text-muted ml-1">
                                        <i class="material-icons icon-18pt">today</i>
                                    </label>
                                    <label class="text-label" for="date_to">{{ __('Date To') }}</label>
                                    <input id="date_to" name="date_to" type="text" class="form-control datepicker" placeholder="{{ __('Select Date') }}" data-toggle="flatpickr" data-flatpickr-alt-format="F j, Y" data-flatpickr-date-format="Y-m-d" value="{{ old('date_to') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="text-label" for="time">{{ __('Time From') }}</label>
                        <input id="time" name="time_from" type="text" class="form-control" placeholder="{{ __('Select Time') }}" data-toggle="flatpickr" data-flatpickr-enable-time="true" data-flatpickr-no-calendar="true" data-flatpickr-alt-format="H:i" data-flatpickr-date-format="H:i" value="{{ old('time_from') }}">
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

