@extends('dashboard.layouts.app')

@section('content')
    <div class="mdk-drawer-layout__content page">
        <div class="container-fluid page__heading-container">
            <div class="page__heading d-flex align-items-center">
                <div class="flex">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"><i class="material-icons icon-20pt">home</i> {{ __('Home') }} </a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Offices') }}</li>
                        </ol>
                    </nav>
                    <h1 class="m-0"> {{ __('Offices Contents') }} </h1>
                </div>
                <a href="{{ route('contents.create') }}" class="btn btn-success ml-3">{{ __('Create') }} <i class="material-icons">add</i></a>
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

                            <th style="width: 5%;"> # </th>
                            <th style="width: 20%;"> {{ __('Content') }} </th>
                            <th style="width: 15%;"> {{ __('Office') }} </th>
                            <th style="width: 15%;"> {{ __('Path') }} </th>
                            <th style="width: 15%;"> {{ __('Floor') }} </th>
                            <th style="width: 15%;"> {{ __('Company') }} </th>
                            <th style="width: 10%;"> {{ __('Action') }} </th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                        @forelse ($contents as $index => $content)
                        <tr>
                            <td class="text-left" style="width: 5%">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input js-check-selected-row" id="customCheck1_20">
                                    <label class="custom-control-label" for="customCheck1_20"><span class="text-hide">Check</span></label>
                                </div>
                            </td>

                            <td style="width: 5%;">
                                <div class="badge badge-soft-dark"> {{ $index+1 }} </div>
                            </td>

                            <td style="width: 20%;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        {{ $content->content }}
                                    </div>
                                </div>
                            </td>

                            <td style="width: 15%;">
                                <a href="{{ route('contents.index', ['office' => $content->office->id]) }}">
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center">
                                            {{ $content->office->title }}
                                        </div>
                                    </div>
                                </a>
                            </td>

                            <td style="width: 15%;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        {{ $content->path->title }}
                                    </div>
                                </div>
                            </td>

                            <td style="width: 15%;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        {{ $content->floor->title }}
                                    </div>
                                </div>
                            </td>

                            <td style="width: 15%;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        {{ $content->company->name }}
                                    </div>
                                </div>
                            </td>

                            <td style="width: 10%">
                                <a href="{{ route('contents.edit', $content->id) }}" class="btn btn-sm btn-link">
                                    <i class="fa fa-edit fa-2x"></i>
                                </a>
                                <form action="{{ route('contents.destroy', $content->id) }}" method="post" style="display: inline-block">
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
                {{ $contents->links('dashboard.pagination.custom') }}
            </div>
        </div>
        <!-- // END drawer-layout__content -->
    </div>
@endsection
