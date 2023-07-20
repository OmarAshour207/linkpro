@extends('dashboard.layouts.app')

@section('content')
    <div class="mdk-drawer-layout__content page">

        <div class="container-fluid page__container">
            <div class="page__heading d-flex align-items-center">
                <div class="flex">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Stat') }}</li>
                        </ol>
                    </nav>
                    <h1 class="m-0">{{ __('Home') }}</h1>
                </div>
            </div>
        </div>


        <div class="container-fluid page__container">

            <div class="card-group">
                <div class="card card-body text-center">

                    <div class="d-flex flex-row align-items-center">
                        <div class="card-header__title m-0"> <i class="material-icons icon-muted icon-30pt">assessment</i> Visits</div>
                        <div class="text-amount ml-auto">3,642</div>
                    </div>
                </div>
                <div class="card card-body text-center">
                    <div class="d-flex flex-row align-items-center">
                        <div class="card-header__title m-0"><i class="material-icons icon-muted icon-30pt">shopping_basket</i> Purchases</div>
                        <div class="text-amount ml-auto">&dollar;12,311</div>
                    </div>
                </div>
                <div class="card card-body text-center">
                    <div class="d-flex flex-row align-items-center">
                        <div class="card-header__title m-0"><i class="material-icons  icon-muted icon-30pt">perm_identity</i> Customers</div>
                        <div class="text-amount ml-auto">78</div>
                    </div>
                </div>
            </div>

            <div class="card card-form d-flex flex-column flex-sm-row">
                <form method="get">
                    <div class="ml-auto">
                        <button class="btn btn-primary" type="submit">
                            {{ __('Export Data') }} <i class="material-icons">file_download</i>
                        </button>
                    </div>

                    <div class="card-form__body card-body-form-group flex">
                        <div class="row">
                            <div class="col-sm-auto">
                                <div class="form-group">
                                    <label for="filter_name">{{ __('Search') }}</label>
                                    <input id="filter_name" value="{{ request()->get('search') }}" name="search" type="text" class="form-control" placeholder="{{ __('Search by name or Phone Number') }}" style="width: 250px;">
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <div class="form-group">
                                    <label for="filter_category">{{ __('Type') }}</label><br>
                                    <select id="filter_category" class="custom-select" style="width: 150px;" name="type">
                                        <option value="ticket">{{ __('Tickets') }}</option>
                                        <option value="supply">{{ __('Supplies') }}</option>
                                        <option value="request">{{ __('Requests') }}</option>
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
                            <div class="col-sm-auto">
                                <div class="form-group">
                                    <label for="filter_status">{{ __('Status') }}</label><br>
                                    <select id="filter_status" class="custom-select" style="width: 150px;" name="status">
                                        <option value="all">{{ __('All') }}</option>
                                        @foreach($status as $key => $state)
                                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}> {{ $state }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <div class="form-group" style="width: 200px;">
                                    <label for="filter_date">{{ __('From') }}</label>
                                    <input id="filter_date" type="text" name="from" class="form-control date_from" placeholder="{{ __('Select date') }}" value="{{ request()->get('from') ?? '01/01/2023' }}" data-toggle="flatpickr" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <div class="form-group" style="width: 200px;">
                                    <label for="filter_date_to">{{ __('To') }}</label>
                                    <input id="filter_date_to" type="text" name="to" class="form-control date_to" placeholder="{{ __('Select date') }}" value="{{ request()->get('to') ?? now() }}" data-toggle="flatpickr" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                                </div>
                            </div>
                            <button type="submit" class="btn bg-white border-left border-top border-top-sm-0 rounded-top-0 rounded-top-sm rounded-left-sm-0">
                                <i class="fa fa-arrow-alt-circle-{{ session('locale') == 'ar' ? 'left' : 'right' }} fa-2x"></i>
                            </button>

                        </div>
                    </div>
                </form>
            </div>


            <div class="card">

                <div class="table-responsive" data-toggle="lists" data-lists-values='["js-lists-values-employee-name"]'>

                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                        <tr>

                            <th style="width: 30px;" class="text-center">#ID</th>
                            <th>Company Name</th>
                            <th style="width: 120px;" class="text-center">Created</th>
                            <th class="text-center">Members</th>
                            <th class="text-center">Growth</th>
                            <th style="width: 50px;">#INV</th>
                            <th style="width: 120px;" class="text-right">Total Sales</th>
                            <th style="width: 50px;">
                                <div class="dropdown pull-right">
                                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Bulk</a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="javascript:void(0)" class="dropdown-item"><i class="material-icons  mr-1">work</i> Update Status</a>
                                        <a href="javascript:void(0)" class="dropdown-item"><i class="material-icons  mr-1">pin_drop</i> Add Location</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="javascript:void(0)" class="dropdown-item"><i class="material-icons  mr-1">archive</i> Archive</a>
                                    </div>
                                </div>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="list" id="companies">
                        <tr>
                            <td>
                                <div class="badge badge-soft-dark">#29178</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">


                                    <div class="d-flex align-items-center">
                                        <i class="material-icons icon-16pt mr-1 text-blue">business</i>
                                        <a href="#">Moon Ltd</a>
                                    </div>

                                    <div class="badge badge-warning ml-2">PRO</div>

                                </div>
                                <div class="d-flex align-items-center">
                                    <small class="text-muted"><i class="material-icons icon-16pt mr-1">pin_drop</i> Miami, Florida, USA</small>
                                </div>
                            </td>
                            <td style="width: 140px;"><i class="material-icons icon-16pt text-muted-light mr-1">today</i> 05-05-2019</td>
                            <td style="width:80px" class="text-center">


                                <i class="material-icons icon-16pt text-muted mr-1">account_circle</i> <a href="#">1</a>

                            </td>
                            <td class="text-center">3% <i class="material-icons icon-16pt text-success">arrow_upward</i></td>
                            <td class="text-center">1</td>
                            <td class="text-right"><strong>$32,124</strong></td>
                            <td><a href="#" class="btn btn-sm btn-link"><i class="material-icons icon-16pt">arrow_forward</i></a> </td>
                        </tr>


                        <tr>

                            <td>
                                <div class="badge badge-soft-dark">#29179</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">


                                    <div class="d-flex align-items-center">
                                        <i class="material-icons icon-16pt mr-1 text-blue">business</i>
                                        <a href="#">Blue Space Ltd</a>
                                    </div>

                                </div>
                                <div class="d-flex align-items-center">
                                    <small class="text-muted"><i class="material-icons icon-16pt mr-1">pin_drop</i> Miami, Florida, USA</small>
                                </div>
                            </td>
                            <td style="width: 140px;"><i class="material-icons icon-16pt text-muted-light mr-1">today</i> 05-05-2019</td>
                            <td style="width:80px" class="text-center">


                                <i class="material-icons icon-16pt text-muted mr-1">account_circle</i> <a href="#">2</a>

                            </td>
                            <td class="text-center">5% <i class="material-icons icon-16pt text-success">arrow_upward</i></td>
                            <td class="text-center">2</td>
                            <td class="text-right"><strong>$13,593</strong></td>
                            <td><a href="#" class="btn btn-sm btn-link"><i class="material-icons icon-16pt">arrow_forward</i></a> </td>
                        </tr>

                        </tbody>
                    </table>
                </div>

                <div class="card-body text-right">
                    15 <span class="text-muted">of 1,430</span> <a href="#" class="text-muted-light"><i class="material-icons ml-1">arrow_forward</i></a>
                </div>


            </div>

        </div>
        <!-- // END drawer-layout__content -->
    </div>
@endsection
@push('admin_scripts')
    <script>
        $(".date_from").flatpickr({
            locale: "{{ session()->get('locale') }}",
            defaultDate: '{{ request()->get('from') }}'
        });

        $(".date_to").flatpickr({
            locale: "{{ session()->get('locale') }}",
            defaultDate: '{{ request()->get('to') }}'
        });
    </script>
@endpush
