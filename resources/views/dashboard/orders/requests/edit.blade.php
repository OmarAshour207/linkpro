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
                    <h1 class="m-0"> {{ __('Requests') }} </h1>
                </div>
            </div>
        </div>

        <div class="container-fluid page__container">

            <div class="card-header card-header-tabs-basic nav" role="tablist">
                <a href="#ticket_tab" class="active" data-toggle="tab" role="tab" aria-controls="ticket_tab" aria-selected="true"><i class="fa fa-first-order"></i> {{ __('Order Details') }}</a>
                <a href="#comments_tab"data-toggle="tab" role="tab" aria-controls="comments_tab" aria-selected="false"><i class="fa fa-comment-alt"></i> {{ __('Comments') }}</a>
            </div>
            <div class="card card-form__body card-body">
                <div class="list-group tab-content list-group-flush">
                    <div class="tab-pane active show fade" id="ticket_tab">
                        <form method="post" action="{{ route('requests.update', $request->id) }}">
                            @csrf
                            @method('put')
                            @include('dashboard.partials._errors')

                            <div class="row no-gutters">
                                <div class="col card-form__body card-body">
                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="services"> {{ __('Services') }}</label> <br>
                                            <select id="services" name="service_id" data-toggle="select" class="form-control select2">
                                                <option value="" selected> {{ __('Services') }} </option>
                                                @forelse($services as $service)
                                                    <option value="{{ $service->id }}" {{ old('service_id', $request->service_id) == $service->id ? 'selected' : '' }}> {{ $service->name }} </option>
                                                @empty
                                                    <option value="" selected> {{ __('No Records') }} </option>
                                                @endforelse
                                            </select>
                                        </div>
                                        <div class="form-group col">
                                            <div class="form-group">
                                                <label for="notes"> {{ __("Notes") }}</label>
                                                <input id="notes" name="notes" value="{{ old("notes", $request->notes) }}" dir="auto" type="text" class="form-control" placeholder="{{ __("Notes") }}">
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
                                            <input id="date" name="date" value="{{ old('date', $request->date) }}" type="text" class="form-control datepicker" placeholder="{{ __('Select Date') }}" data-toggle="flatpickr" data-flatpickr-alt-format="F j, Y" data-flatpickr-date-format="Y-m-d">
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
                                            <input id="start_time" name="start_time" value="{{ old('start_time', $request->start_time) }}" type="text" class="form-control" placeholder="{{ __('Select Time') }}" data-toggle="flatpickr" data-flatpickr-enable-time="true" data-flatpickr-no-calendar="true" data-flatpickr-alt-format="H:i" data-flatpickr-date-format="H:i">
                                        </div>
                                        <div class="form-group col">
                                            <label class="text-muted ml-1">
                                                <i class="fa fa-clock"></i>
                                            </label>
                                            <label for="end_time" class="text-label">{{ __('End Time') }}</label>
                                            <input id="end_time" value="{{ old('end_time', $request->end_time) }}" name="end_time" type="text" class="form-control" placeholder="{{ __('Select Time') }}" data-toggle="flatpickr" data-flatpickr-enable-time="true" data-flatpickr-no-calendar="true" data-flatpickr-alt-format="H:i" data-flatpickr-date-format="H:i">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @php
                                // 1 => on hold, 2 => processing, 3 => approved, 4 => rejected, 5 => delayed
                                $status = [
                                    1     => __('On hold'),
                                    2     => __('Under Processing'),
                                    3     => __('Approved'),
                                    4     => __('Rejected'),
                                    5     => __('Delayed')
                                ];
                            @endphp
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="status"> {{ __('Status') }}</label> <br>
                                    <select id="status" name="status" data-toggle="select" class="form-control select2 status">
                                        @foreach($status as $key => $state)
                                            <option value="{{ $key }}" {{ old('status', $request->status) == $key ? 'selected' : '' }}> {{ $state }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 reason" style="display: {{ old('status', $request->status) == 4 ? 'block' : 'none' }}">
                                    <label for="reason"> {{ __("Reason") }}</label>
                                    <input id="reason" name="reason" dir="auto" type="text" class="form-control" placeholder="{{ __("Reason") }}" value="{{ old("reason", $request->reason) }}">
                                </div>
                            </div>

                            <div class="form-group col-lg-6">
                                <label for="user"> {{ __('Users') }}</label> <br>
                                <select id="user" name="user_id" data-toggle="select" class="form-control select2">
                                    <option value="" selected> {{ __('Users') }} </option>
                                    @forelse($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id', $request->user_id) == $user->id ? 'selected' : '' }}> {{ $user->name . ' -- ' . $user->email }} </option>
                                    @empty
                                        <option value="" selected> {{ __('No Records') }} </option>
                                    @endforelse
                                </select>
                            </div>

                            <div class="text-right mb-5">
                                <input type="submit" class="btn btn-success" value="{{ __('Update') }}">
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="comments_tab">
                        <div class="contents">
                            <div class="table-responsive border-bottom" data-toggle="lists" data-lists-values='["js-lists-values-employee-name"]'>
                                <table class="table mb-0 thead-border-top-0">
                                    <thead>
                                    <tr>
                                        <th style="width: 300px;">
                                            {{ __('Username') }}
                                        </th>
                                        <th>{{ __('Comment') }}</th>
                                        <th> {{ __('Created at') }} </th>
                                    </tr>
                                    </thead>
                                    <tbody class="list" id="staff">
                                    @forelse($comments as $index => $comment)
                                        <tr class="selected">
                                            <td style="width: 300px;">
                                                {{ $comment->user->name }}
                                            </td>

                                            <td style="width: 300px;">
                                                <div class="media align-items-center">
                                                    <div class="media-body">
                                                        {{ $comment->content }}
                                                    </div>
                                                </div>
                                            </td>

                                            <td style="width: 400px;">
                                                <div class="media align-items-center">
                                                    <div class="media-body">
                                                        {{ $comment->created_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- // END drawer-layout__content -->
    </div>
@endsection

@push('admin_scripts')
    <script>
        $("#date" ).flatpickr({
            minDate: '{{ $request->date }}',
            locale: "{{ session()->get('locale') }}",
            defaultDate: '{{ $request->date }}'
        });

        $(document).ready(function () {
            $(document).on('change', '.status', function () {
                var stateId = $('.status option:selected').val();
                if (stateId == 4) {
                    $('.reason').css('display', 'block');
                } else {
                    $('.reason').css('display', 'none');
                }
            });
        });
    </script>
@endpush
