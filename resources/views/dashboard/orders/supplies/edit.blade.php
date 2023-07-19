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

            <div class="card-header card-header-tabs-basic nav" role="tablist">
                <a href="#ticket_tab" class="active" data-toggle="tab" role="tab" aria-controls="ticket_tab" aria-selected="true"><i class="fa fa-first-order"></i> {{ __('Order Details') }}</a>
                <a href="#comments_tab"data-toggle="tab" role="tab" aria-controls="comments_tab" aria-selected="false"><i class="fa fa-comment-alt"></i> {{ __('Comments') }}</a>
            </div>
            <div class="card card-form__body card-body">
                <div class="list-group tab-content list-group-flush">
                    <div class="tab-pane active show fade" id="ticket_tab">
                        <form method="post" action="{{ route('orders.supplies.update', $ticket->id) }}">

                            @csrf
                            @method('put')

                            @include('dashboard.partials._errors')

                            <div class="row no-gutters">
                                <div class="col card-form__body card-body">
                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="company_id"> {{ __('Companies') }}</label>
                                            <select id="company_id" name="company_id" data-toggle="select" class="form-control companies select2">
                                                <option value="{{ $ticket->company_id }}" selected> {{ $ticket->company->name . ' -- ' . $ticket->company->email }} </option>
                                            </select>
                                        </div>

                                        <div class="form-group col">
                                            <label for="notes"> {{ __("Notes") }}</label>
                                            <input id="notes" name="notes" value="{{ old("notes", $ticket->notes) }}" dir="auto" type="text" class="form-control" placeholder="{{ __("Notes") }}">
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
                                            <option value="{{ $key }}" {{ old('status', $ticket->status) == $key ? 'selected' : '' }}> {{ $state }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 reason" style="display: {{ old('status', $ticket->status) == 4 ? 'block' : 'none' }}">
                                    <label for="reason"> {{ __("Reason") }}</label>
                                    <input id="reason" name="reason" dir="auto" type="text" class="form-control" placeholder="{{ __("Reason") }}" value="{{ old("reason", $ticket->reason) }}">
                                </div>
                            </div>

                            <label> {{ __('Supplies') }}</label>
                            <div class="form-group supplies">
                                <div class="table-responsive border-bottom" data-toggle="lists" data-lists-values='["js-lists-values-employee-name"]'>
                                    <table class="table mb-0 thead-border-top-0">
                                        <thead>
                                        <tr>
                                            <th style="width: 18px;">
                                                {{ __('Select') }}
                                            </th>
                                            <th> {{ __('Content') }}</th>
                                            <th> {{ __('Quantity') }} </th>
                                            <th> {{ __('Unit') }} </th>
                                        </tr>
                                        </thead>
                                        <tbody class="list" id="staff">
                                        @forelse($supplies as $index => $supply)
                                            @if(key_exists($supply->id, $suppliesIds))
                                                <input type="hidden" name="supplies[{{ $index }}][ticket_data_id]" value="{{ $suppliesIds[$supply->id]['ticket_data_id'] }}">
                                            @else
                                                <input type="hidden" name="supplies[{{ $index }}][ticket_data_id]" value="0">
                                            @endif
                                            <tr class="selected">
                                                <input type="hidden" name="supplies[{{ $index }}][supply_id]" value="{{ $supply->id }}">
                                                <td style="width: 18px;">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="supplies[{{ $index }}][box]" class="form-check-input" id="checkbox{{ $index }}" {{ key_exists($supply->id, $suppliesIds) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="checkbox{{ $index }}"><span class="text-hide">Check</span></label>
                                                    </div>
                                                </td>

                                                <td style="width: 300px;">
                                                    <div class="media align-items-center">
                                                        <div class="media-body">
                                                            <input type="text" class="form-control" placeholder="{{ __('Content') }}" value="{{ $supply->name }}" disabled>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td style="width: 400px;">
                                                    <div class="media align-items-center">
                                                        <div class="media-body">
                                                            <input id="quantity" value="{{ key_exists($supply->id, $suppliesIds) ? $suppliesIds[$supply->id]['quantity'] : '' }}" name="supplies[{{ $index }}][quantity]" type="number" class="form-control" placeholder="{{ __('Quantity') }}">
                                                        </div>
                                                    </div>
                                                </td>

                                                <td style="width: 400px;">
                                                    <div class="media align-items-center">
                                                        <div class="media-body">
                                                            <input id="unit" name="supplies[{{ $index }}][unit]" value="{{ key_exists($supply->id, $suppliesIds) ? $suppliesIds[$supply->id]['unit'] : '' }}" type="text" class="form-control" placeholder="{{ __('Unit') }}">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <div class="row no-gutters contents">
                                                <div class="col card-form__body card-body">
                                                    <div class="row">
                                                        <div class="form-group col">
                                                            <label for="content">{{ __('Empty Supplies') }}</label>
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

                                            <td style="width: 300px;">
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
