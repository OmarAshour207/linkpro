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
                    <h1 class="m-0"> {{ __('Supplies') }} </h1>
                </div>
            </div>
        </div>

        <div class="container-fluid page__container">

            <div class="card card-form__body card-body">
                <form method="post" action="{{ route('orders.supplies.store') }}">

                    @csrf
                    @include('dashboard.partials._errors')

                    <div class="row no-gutters">
                        <div class="col card-form__body card-body">
                            <div class="row">
                                <div class="form-group col">
                                    <label for="company_id"> {{ __('Companies') }}</label>
                                    <select id="company_id" name="company_id" data-toggle="select" class="form-control companies select2">
                                        <option value="" selected> {{ __('Companies') }} </option>
                                        @forelse($companies as $company)
                                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}> {{ $company->name . ' -- ' . $company->email }} </option>
                                        @empty
                                            <option value="" selected> {{ __('No Records') }} </option>
                                        @endforelse
                                    </select>
                                </div>

                                <div class="form-group col">
                                    <label for="notes"> {{ __("Notes") }}</label>
                                    <input id="notes" name="notes" value="{{ old("notes") }}" dir="auto" type="text" class="form-control" placeholder="{{ __("Notes") }}">
                                </div>

                            </div>
                        </div>
                    </div>

                    <label> {{ __('Supplies') }}</label>
                    <div class="form-group supplies">
                        <label for="notes"> {{ __("Select Company") }}</label>
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
        $(document).ready(function () {
            $(document).on('change', '.companies', function () {
                var userId = $('.companies option:selected').val();
                if(userId) {
                    $.ajax({
                        url: "{{ route('orders.supply.data') }}",
                        data_type: 'html',
                        method: "POST",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            user_id: userId
                        }, success: function (data) {
                            $('.supplies').html(data);
                        }
                    });
                } else {
                    $('.supplies').html('');
                }
            });
        });
    </script>
@endpush

