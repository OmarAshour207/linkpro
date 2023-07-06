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
                    <h1 class="m-0"> {{ __('Supplies Tickets') }} </h1>
                </div>
            </div>
        </div>

        <div class="container-fluid page__container">

            <div class="card card-form__body card-body">
                <form method="post" action="{{ route('tickets.store', 'supplies') }}">

                    @csrf
                    @include('dashboard.partials._errors')

                    <div class="form-group col-lg-6">
                        <label for="user"> {{ __('Companies') }}</label>
                        <select id="user" name="user_id" data-toggle="select" class="form-control companies select2">
                            <option value="" selected> {{ __('Companies') }} </option>
                            @forelse($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}> {{ $user->name . ' -- ' . $user->email }} </option>
                            @empty
                                <option value="" selected> {{ __('No Records') }} </option>
                            @endforelse
                        </select>
                    </div>

                    <label> {{ __('Supplies') }}</label>
                    <div class="form-group supplies">

                    </div>

                    <div class="form-group">
                        <label for="content"> {{ __("Content") }}</label>
                        <input id="content" name="content" dir="auto" type="text" class="form-control" placeholder="{{ __("Content") }}" value="{{ old("content") }}">
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
                        url: "{{ route('tickets.supplies') }}",
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

