@extends('dashboard.layouts.app')

@section('content')
    <div class="mdk-drawer-layout__content page">
        <div class="container-fluid page__heading-container">
            <div class="page__heading d-flex align-items-center">
                <div class="flex">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item active" aria-current="page"><i class="material-icons icon-20pt"> {{ __('Home') }} </i></li>
                        </ol>
                    </nav>
                    <h1 class="m-0"> {{ __('Dashboard') }} </h1>
                </div>
            </div>
        </div>

        <div class="container-fluid page__container">
        </div>
        <!-- // END drawer-layout__content -->
    </div>
@endsection
@push('admin_scripts')

@endpush
