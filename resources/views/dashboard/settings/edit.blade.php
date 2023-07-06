@extends('dashboard.layouts.app')

@section('content')
    <div class="mdk-drawer-layout__content page">
        <div class="container-fluid page__heading-container">
            <div class="page__heading d-flex align-items-center">
                <div class="flex">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"><i class="material-icons icon-20pt">home</i> {{ __('Home') }} </a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Settings') }}</li>
                        </ol>
                    </nav>
                    <h1 class="m-0"> {{ __('Settings') }} </h1>
                </div>
            </div>
        </div>

        <div class="container-fluid page__container">

            <div class="card card-form__body card-body">
                <form method="post" action="{{ route('settings.store') }}">
                    @csrf

                    <div class="row no-gutters">
                        <div class="col-lg card-form__body card-body">
                            <div class="form-group">
                                <label for="title">{{ __('Website') }} / {{ __('Title') }}</label>
                                <input id="title" dir="auto" type="text" name="title" class="form-control" value="{{ old('title', setting('title')) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description"> {{ __('Website') }} / {{ __('Description') }}</label>
                        <textarea id="description" dir="auto" name="description" rows="4" class="form-control">{{ old('description', setting('description')) }}</textarea>
                    </div>

                    <div class="row no-gutters">
                        <div class="col-lg card-form__body card-body">
                            <div class="form-group">
                                <label for="phonenumber">{{ __('Website') }} / {{ __('Phone number') }}</label>
                                <input id="phonenumber" type="text" placeholder="{{ __('Phone number') }}" name="phonenumber" class="form-control" value="{{ old('phonenumber', setting('phonenumber')) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <div class="col-lg card-form__body card-body">
                            <div class="form-group">
                                <label for="email">{{ __('Website') }} / {{ __('Email') }}</label>
                                <input id="email" dir="ltr" type="email" name="email" class="form-control" value="{{ old('email', setting('email')) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <div class="col-lg card-form__body card-body">
                            <div class="form-group">
                                <label for="facebook">{{ __('Website') }} / {{ __('Facebook') }}</label>
                                <input id="facebook" type="url" name="facebook" placeholder="{{ __('Facebook') }}" class="form-control" value="{{ old('facebook', setting('facebook')) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <div class="col-lg card-form__body card-body">
                            <div class="form-group">
                                <label for="instagram">{{ __('Website') }} / {{ __('Instagram') }}</label>
                                <input id="instagram" placeholder="{{ __('Instagram') }}" type="url" name="instagram" class="form-control" value="{{ old('instagram', setting('instagram')) }}">
                            </div>
                        </div>
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
