@extends('dashboard.layouts.app')

@section('content')
    <div class="mdk-drawer-layout__content page">
        <div class="container-fluid page__heading-container">
            <div class="page__heading d-flex align-items-center">
                <div class="flex">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"><i class="material-icons icon-20pt">home</i> {{ __('Home') }} </a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Tickets') }}</li>
                        </ol>
                    </nav>
                    <h1 class="m-0"> {{ __('Tickets') }} </h1>
                </div>
                <a href="{{ route('tickets.create') }}" class="btn btn-success ml-3">{{ __('Create') }} <i class="material-icons">add</i></a>
            </div>
        </div>

        <div class="container-fluid page__container">

            <div class="card">
                <div class="table-responsive" data-toggle="lists" data-lists-values='["js-lists-values-employee-name"]'>

                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>

                            <th style="width: 5%;">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input js-toggle-check-all" data-target="#companies" id="customCheckAll">
                                    <label class="custom-control-label" for="customCheckAll"><span class="text-hide">Toggle all</span></label>
                                </div>
                            </th>

                            <th style="width: 10%;"> # </th>
                            <th style="width: 15%;"> {{ __('Company') }} </th>
                            <th style="width: 10%;"> {{ __('Mobile Number') }} </th>
                            <th style="width: 10%;"> {{ __('Note') }} </th>
                            <th style="width: 20%;"> {{ __('Office Content') }} </th>
                            <th style="width: 10%;"> {{ __('Created at') }} </th>
                            <th style="width: 10%;"> {{ __('Status') }} </th>
                            <th style="width: 10%;"> {{ __('Action') }} </th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                        @forelse ($tickets as $index => $ticket)
                        <tr>
                            <td class="text-left" style="width: 5%;">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input js-check-selected-row" id="customCheck1_20">
                                    <label class="custom-control-label" for="customCheck1_20"><span class="text-hide">Check</span></label>
                                </div>
                            </td>

                            <td style="width: 5%;">
                                <div class="badge badge-soft-dark"> {{ $index+1 }} </div>
                            </td>

                            <td style="width: 15%;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        {{ mb_substr($ticket->company->name, 0, 20) }}
                                    </div>
                                </div>
                            </td>

                            <td style="width: 10%;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        {{ $ticket->company->phonenumber }}
                                    </div>
                                </div>
                            </td>

                            <td style="width: 10%;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        {{ mb_substr($ticket->notes, 0, 50) }}
                                    </div>
                                </div>
                            </td>

                            <td style="width: 20%;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <ul>
                                        @forelse($ticket->ticketData as $data)
                                            <li>
                                                {{ $data->content->content }}
                                            </li>
                                        @empty
                                        @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </td>

                            <td style="width: 10%;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        {{ $ticket->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </td>

                            @php
                                // 1 => on hold, 2 => processing, 3 => approved, 4 => rejected, 5 => delayed
                                $status = [
                                    '1'     => ['name'  => __('On hold'), 'btn' => 'warning'],
                                    '2'     => ['name'  => __('Under Processing'), 'btn' => 'info'],
                                    '3'     => ['name'  => __('Delivered'), 'btn' => 'success'],
                                    '4'     => ['name'  => __('Rejected'), 'btn' => 'danger'],
                                    '5'     => ['name'  => __('Delayed'), 'btn' => 'secondary']
                                ];
                            @endphp
                            <td style="width: 10%;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center btn btn-{{ $status[$ticket->status]['btn'] }}">
                                        {{ $status[$ticket->status]['name'] }}
                                    </div>
                                </div>
                            </td>

                            <td style="width: 10%;">
                                <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-sm btn-link">
                                    <i class="fa fa-edit fa-2x"></i>
                                </a>
                                <form action="{{ route('tickets.destroy', $ticket->id) }}" method="post" style="display: inline-block">
                                    @csrf
                                    @method('delete')

                                    <button type="submit" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i> </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                            <h1> {{ __('No records') }} </h1>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4">
                {{ $tickets->links('dashboard.pagination.custom') }}
            </div>
        </div>
        <!-- // END drawer-layout__content -->
    </div>
@endsection
