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
                    <h1 class="m-0"> {{ __('Services') }} </h1>
                </div>
            </div>
        </div>

        <div class="container-fluid page__container">

            <div class="card card-form__body card-body">
                <form method="post" action="{{ route('services.store') }}">

                    @csrf
                    @include('dashboard.partials._errors')

                    @foreach($available_locales as $key => $locale)
                        <div class="form-group">
                            <label for="name:{{ $locale }}"> {{ __("$key Name") }}</label>
                            <input id="name:{{ $locale }}" name="name:{{ $locale }}" dir="{{ $locale == 'ar' ? 'rtl' : 'ltr' }}" type="text" class="form-control" placeholder="{{ __("$key Name") }}" value="{{ old("name:$locale") }}">
                        </div>
                    @endforeach
                    @foreach($available_locales as $key => $locale)
                        <div class="form-group">
                            <label for="description:{{ $locale }}">{{ __("$key Description") }}</label>
                            <textarea id="description:{{ $locale }}" dir="{{ $locale == 'ar' ? 'rtl' : 'ltr' }}" name="description:{{ $locale }}" rows="4" class="form-control" placeholder="{{ __("$key Description") }}...">{{ old("description:$locale") }}</textarea>
                        </div>
                    @endforeach

                    <div class="text-right mb-5">
                        <input type="submit" class="btn btn-success" value="{{ __('Add') }}">
                    </div>
                </form>
            </div>
        </div>
        <!-- // END drawer-layout__content -->
    </div>
@endsection
