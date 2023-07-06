@extends('dashboard.layouts.app')

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
                    <h1 class="m-0"> {{ __('Supplies') }} </h1>
                </div>
            </div>
        </div>

        <div class="container-fluid page__container">

            <div class="card card-form__body card-body">
                <form method="post" action="{{ route('supplies.update', $supply->id) }}">

                    @csrf
                    @method('put')

                    @include('dashboard.partials._errors')

                    <div class="form-group col-lg-6">
                        <label for="company"> {{ __('Company') }}</label> <br>
                        <select id="company" name="user_id" data-toggle="select" class="form-control select2 company_id">
                            <option value="" selected> {{ __('Company') }} </option>
                            @forelse($companies as $company)
                                <option value="{{ $company->id }}" {{ old('user_id', $supply->user_id) == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @empty
                            @endforelse
                        </select>
                    </div>

                    <div class="row no-gutters contents">
                        <div class="col card-form__body card-body">
                            <div class="row">
                                <div class="form-group col">
                                    <label for="name">{{ __('Name') }}</label>
                                    <input id="name" name="name" type="text" value="{{ old('name', $supply->name) }}" class="form-control" placeholder="{{ __('Name') }}">
                                </div>
                                <div class="form-group col">
                                    <label for="quantity">{{ __('Quantity') }}</label>
                                    <input id="quantity" name="quantity" value="{{ old('quantity', $supply->quantity) }}" type="number" class="form-control" placeholder="{{ __('Quantity') }}">
                                </div>
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
