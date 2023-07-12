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
                    <h1 class="m-0"> {{ __('Ticket') }} </h1>
                </div>
            </div>
        </div>

        <div class="container-fluid page__container">

            <div class="card card-form__body card-body">
                <form method="post" action="{{ route('tickets.update', $ticket->id) }}">

                    @csrf
                    @method('put')

                    @include('dashboard.partials._errors')

                    <div class="form-group col-lg-6">
                        <label for="user"> {{ __('Companies') }}</label>
                        <select id="user" name="company_id" data-toggle="select" class="form-control company_id select2">
                            <option value="" selected> {{ __('Companies') }} </option>
                            @forelse($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id', $ticket->company->id) == $company->id ? 'selected' : '' }}> {{ $company->name . ' -- ' . $company->email }} </option>
                            @empty
                                <option value="" selected> {{ __('No Records') }} </option>
                            @endforelse
                        </select>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="floor_id"> {{ __('Floors') }}</label> <br>
                        <select id="floor_id" name="floor_id" data-toggle="select" class="form-control select2 floors">
                            @forelse($floors as $floor)
                                <option value="{{ $floor->id }}" {{ $floor->id == $ticket->floor_id ? 'selected' : '' }}> {{ $floor->title }} </option>
                            @empty
                                <option value="" selected> {{ __('Floor Title') }} </option>
                            @endforelse
                        </select>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label for="path_id"> {{ __('Paths') }}</label> <br>
                            <select id="path_id" name="path_id" data-toggle="select" class="form-control select2 paths">
                                @forelse($paths as $path)
                                    <option value="{{ $path->id }}" {{ $path->id == $ticket->path_id ? 'selected' : '' }}> {{ $path->title }} </option>
                                @empty
                                    <option value="" selected> {{ __('No Paths') }} </option>
                                @endforelse
                            </select>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="office_id"> {{ __('Offices') }}</label> <br>
                            <select id="office_id" name="office_id" data-toggle="select" class="form-control select2 offices">
                                @forelse($offices as $office)
                                    <option value="{{ $office->id }}" {{ $office->id == $ticket->office_id ? 'selected' : '' }}> {{ $office->title }} </option>
                                @empty
                                    <option value="" selected> {{ __('No Offices') }} </option>
                                @endforelse
                            </select>
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
                                    <option value="{{ $key }}" {{ old('status', $ticket->status) == $key ? 'selected' : '' }}> {{ $state }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-6 reason" style="display: {{ old('status', $ticket->status) == 4 ? 'block' : 'none' }}">
                            <label for="reason"> {{ __("Reason") }}</label>
                            <input id="reason" name="reason" dir="auto" type="text" class="form-control" placeholder="{{ __("Reason") }}" value="{{ old("reason", $ticket->reason) }}">
                        </div>
                    </div>

                    <div class="contents">
                        <div class="table-responsive border-bottom" data-toggle="lists" data-lists-values='["js-lists-values-employee-name"]'>
                            <table class="table mb-0 thead-border-top-0">
                                <thead>
                                <tr>
                                    <th style="width: 18px;">
                                        {{ __('Select') }}
                                    </th>
                                    <th>{{ __('Content') }}</th>
                                    <th> {{ __('Notes') }} </th>
                                </tr>
                                </thead>
                                <tbody class="list" id="staff">
                                @forelse($contents as $index => $content)
                                    <tr class="selected">
                                        <td style="width: 18px;">
                                            <div class="custom-control custom-checkbox">
                                                <input type="radio" {{ $content->id == $ticket->content_id ? 'checked' : '' }} value="{{ $content->id }}" name="content_id" class="form-check-input" id="selectbox{{ $index }}">
                                                <label class="form-check-label" for="selectbox{{ $index }}"><span class="text-hide">Check</span></label>
                                            </div>
                                        </td>

                                        <td style="width: 300px;">
                                            <div class="media align-items-center">
                                                <div class="media-body">
                                                    <input id="content" name="content" type="text" class="form-control" placeholder="{{ __('Content') }}" value="{{ $content->content }}" disabled>
                                                </div>
                                            </div>
                                        </td>

                                        <td style="width: 400px;">
                                            <div class="media align-items-center">
                                                <div class="media-body">
                                                    <input id="note" name="notes[{{ $content->id }}]" value="{{ $ticket->content_id == $content->id ? $ticket->notes : '' }}" type="text" class="form-control" placeholder="{{ __('Note') }}">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <div class="row no-gutters contents">
                                        <div class="col card-form__body card-body">
                                            <div class="row">
                                                <div class="form-group col">
                                                    <label for="content">{{ __('Empty Contents') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                                </tbody>
                            </table>
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

            $(document).on('change', '.status', function () {
                var stateId = $('.status option:selected').val();
                if(stateId == 4) {
                    $('.reason').css('display', 'block');
                } else {
                    $('.reason').css('display', 'none');
                }
            });
        });
    </script>
@endpush