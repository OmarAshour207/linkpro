@extends('dashboard.layouts.app')

@push('admin_scripts')
    <script src="{{ asset('dashboard/js/custom/floors.js') }}"></script>
@endpush

@section('content')
    <div class="mdk-drawer-layout__content page">
        <div class="container-fluid page__heading-container">
            <div class="page__heading d-flex align-items-center">
                <div class="flex">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"><i class="material-icons icon-20pt">home</i> {{ __('Home') }} </a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
                        </ol>
                    </nav>
                    <h1 class="m-0"> {{ __('Paths') }} </h1>
                </div>
            </div>
        </div>

        <div class="container-fluid page__container">

            <div class="card card-form__body card-body">
                <form method="post" action="{{ route('paths.update', $path->id) }}">

                    @csrf
                    @method('put')

                    @include('dashboard.partials._errors')

                    <div class="form-group">
                        <label for="title"> {{ __("Title") }}</label>
                        <input id="title" name="title" dir="auto" type="text" class="form-control" placeholder="{{ __("Title") }}" value="{{ old("title", $path->title) }}">
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="company"> {{ __('Company') }}</label> <br>
                        <select id="company" name="user_id" data-toggle="select" class="form-control select2 company_id">
                            <option value="" selected> {{ __('Company') }} </option>
                            @forelse($companies as $company)
                                <option value="{{ $company->id }}" {{ old('user_id', $path->user_id) == $company->id ? 'selected' : '' }}>
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
                            @forelse($floors as $floor)
                                <option value="{{ $floor->id }}" {{ old('floor_id', $path->floor_id) == $floor->id ? 'selected' : '' }}>
                                    {{ $floor->title }}
                                </option>
                            @empty
                            @endforelse
                        </select>
                    </div>


                    <div class="text-right mb-5">
                        <input type="submit" class="btn btn-success" value="{{ __('Update') }}">
                    </div>
                </form>
            </div>
        </div>
        <!-- // END drawer-layout__content -->
    </div>
@endsection
