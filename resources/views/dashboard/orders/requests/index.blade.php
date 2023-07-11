@extends('dashboard.layouts.app')

@section('content')
    <div class="mdk-drawer-layout__content page">
        <div class="container-fluid page__heading-container">
            <div class="page__heading d-flex align-items-center">
                <div class="flex">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"><i class="material-icons icon-20pt">home</i> {{ __('Home') }} </a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Requests') }}</li>
                        </ol>
                    </nav>
                    <h1 class="m-0"> {{ __('Requests') }} </h1>
                </div>
                <a href="{{ route('requests.create') }}" class="btn btn-success ml-3">{{ __('Create') }} <i class="material-icons">add</i></a>
            </div>
        </div>

        <div class="container-fluid page__container">

            <div class="card">
                <div class="table-responsive" data-toggle="lists" data-lists-values='["js-lists-values-employee-name"]'>

                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                            <th style="width: 5%;"> # </th>
                            <th style="width: 15%;"> {{ __('Name') }} </th>
                            <th style="width: 10%;"> {{ __('Phone Number') }} </th>
                            <th style="width: 10%;"> {{ __('Date') }} </th>
                            <th style="width: 10%;"> {{ __('Start Time') }} </th>
                            <th style="width: 10%;"> {{ __('End Time') }} </th>
                            <th style="width: 15%;"> {{ __('Notes') }} </th>
                            <th style="width: 10%;"> {{ __('Created at') }} </th>
                            <th style="width: 5%;"> {{ __('Status') }} </th>
                            <th style="width: 10%;"> {{ __('Action') }} </th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                        @forelse ($requests as $index => $request)
                        <tr>
                            <td style="width: 5%;">
                                <div class="badge badge-soft-dark"> {{ $index+1 }} </div>
                            </td>

                            <td style="width: 15%;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        {{ mb_substr($request->user->name, 0, 20) }}
                                    </div>
                                </div>
                            </td>

                            <td style="width: 10%;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        {{ $request->user->phonenumber }}
                                    </div>
                                </div>
                            </td>

                            <td style="width: 10%;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        {{ $request->date }}
                                    </div>
                                </div>
                             </td>

                            <td style="width: 10%;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $request->start_time)->format('g:i A') }}
                                    </div>
                                </div>
                            </td>

                            <td style="width: 10%;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $request->end_time)->format('g:i A') }}
                                    </div>
                                </div>
                            </td>

                            <td style="width: 15%;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        {{ $request->notes }}
                                    </div>
                                </div>
                            </td>

                            <td style="width: 10%;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        {{ $request->created_at->diffForHumans() }}
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
                            <td style="width: 5%;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center btn btn-{{ $status[$request->status]['btn'] }}">
                                        {{ $status[$request->status]['name'] }}
                                    </div>
                                </div>
                            </td>

                            <td style="width: 10%">
                                <a href="{{ route('requests.edit', $request->id) }}" class="btn btn-sm btn-link">
                                    <i class="fa fa-edit fa-2x"></i>
                                </a>
                                <form action="{{ route('requests.destroy', $request->id) }}" method="post" style="display: inline-block">
                                    @csrf
                                    @method('delete')

                                    <button type="submit" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i> </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                            <h1> {{ __('No records') }} </h1>
                        @endforelse
                        {{ $requests->appends(request()->query())->links() }}

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <!-- // END drawer-layout__content -->
    </div>
@endsection
