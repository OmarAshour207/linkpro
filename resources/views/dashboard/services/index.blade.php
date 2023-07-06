@extends('dashboard.layouts.app')

@section('content')
    <div class="mdk-drawer-layout__content page">
        <div class="container-fluid page__heading-container">
            <div class="page__heading d-flex align-items-center">
                <div class="flex">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"><i class="material-icons icon-20pt">home</i> {{ __('Home') }} </a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Services') }}</li>
                        </ol>
                    </nav>
                    <h1 class="m-0"> {{ __('Services') }} </h1>
                </div>
                <a href="{{ route('services.create') }}" class="btn btn-success ml-3">{{ __('Create') }} <i class="material-icons">add</i></a>
            </div>
        </div>

        <div class="container-fluid page__container">

            <div class="card">
                <div class="table-responsive" data-toggle="lists" data-lists-values='["js-lists-values-employee-name"]'>

                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>

                            <th style="width: 10%;">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input js-toggle-check-all" data-target="#companies" id="customCheckAll">
                                    <label class="custom-control-label" for="customCheckAll"><span class="text-hide">Toggle all</span></label>
                                </div>
                            </th>

                            <th style="width: 10%;"> # </th>
                            <th style="width: 30%;"> {{ __('Arabic Title') }} </th>
                            <th style="width: 30%;"> {{ __('English Title') }} </th>
                            <th style="width: 30%;"> {{ __('Action') }} </th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                        @if($services->count() > 0)
                            @foreach($services as $index => $service)
                        <tr>
                            <td class="text-left">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input js-check-selected-row" id="customCheck1_20">
                                    <label class="custom-control-label" for="customCheck1_20"><span class="text-hide">Check</span></label>
                                </div>
                            </td>

                            <td style="width: 10%;">
                                <div class="badge badge-soft-dark"> {{ $index+1 }} </div>
                            </td>

                            <td style="width: 30%;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        {{ mb_substr($service->getTranslation('ar')->name, 0, 20) }}
                                    </div>
                                </div>
                            </td>
                            <td style="width: 30%;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        {{ substr($service->getTranslation('en')->name, 0, 20) }}
                                    </div>
                                </div>
                            </td>

                            <td>
                                <a href="{{ route('services.edit', $service->id) }}" class="btn btn-sm btn-link">
                                    <i class="fa fa-edit fa-2x"></i>
                                </a>
                                <form action="{{ route('services.destroy', $service->id) }}" method="post" style="display: inline-block">
                                    @csrf
                                    @method('delete')

                                    <button type="submit" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i> </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                            {{ $services->appends(request()->query())->links() }}
                        @else
                            <h1> {{ __('No records') }} </h1>
                        @endif
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <!-- // END drawer-layout__content -->
    </div>
@endsection
