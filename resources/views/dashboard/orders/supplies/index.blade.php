@extends('dashboard.layouts.app')

@section('content')
    <div class="mdk-drawer-layout__content page">
        <div class="container-fluid page__heading-container">
            <div class="page__heading d-flex align-items-center">
                <div class="flex">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"><i class="material-icons icon-20pt">home</i> {{ __('Home') }} </a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Supplies') }}</li>
                        </ol>
                    </nav>
                    <h1 class="m-0"> {{ __('Supplies') }} </h1>
                </div>
                <a href="{{ route('orders.supplies.create') }}" class="btn btn-success ml-3">{{ __('Create') }} <i class="material-icons">add</i></a>
            </div>
        </div>

        <div class="container-fluid page__container">

            <div class="card">
                <div class="table-responsive" data-toggle="lists" data-lists-values='["js-lists-values-employee-name"]'>

                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                            <th style="width: 10%;"> # </th>
                            <th style="width: 20%;"> {{ __('Company') }} </th>
                            <th style="width: 10%;"> {{ __('Mobile Number') }} </th>
                            <th style="width: 30%;"> {{ __('Quantity') }} - {{ __('Unit') }} - {{ __('Supply') }} </th>
                            <th style="width: 10%;"> {{ __('Created at') }} </th>
                            <th style="width: 10%;"> {{ __('Status') }} </th>
                            <th style="width: 10%;"> {{ __('Action') }} </th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                        @forelse ($tickets as $index => $ticket)
                        <tr>
                            <td style="width: 10%;">
                                <div class="badge badge-soft-dark"> {{ $index+1 }} </div>
                            </td>

                            <td style="width: 20%;">
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

                            <td style="width: 30%;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <ul>
                                            @forelse($ticket->ticketData as $data)
                                                <li>
                                                    {{ $data->quantity }} - {{ $data->unit }} - {{ $data->supply->name }}
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
                                    '3'     => ['name'  => __('Approved'), 'btn' => 'success'],
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
                                <a href="{{ route('orders.supplies.edit', $ticket->id) }}" class="btn btn-sm btn-link">
                                    <i class="fa fa-edit fa-2x"></i>
                                </a>
                                <form action="{{ route('orders.supplies.destroy', $ticket->id) }}" method="post" style="display: inline-block">
                                    @csrf
                                    @method('delete')

                                    <button type="submit" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i> </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                            <h1> {{ __('No records') }} </h1>
                        @endforelse
                        {{ $tickets->appends(request()->query())->links() }}

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <!-- // END drawer-layout__content -->
    </div>
@endsection
