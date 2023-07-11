@push('admin_scripts')
    <script type="text/javascript">
        var path = 'users';
        Dropzone.autoDiscover = false;
        $(document).ready(function () {
            $('#mainphoto').dropzone({
                url: '{{ route('upload.image') }}',
                paramName:'image',
                autoDiscover: false,
                uploadMultiple: false,
                maxFiles: 1,
                acceptedFiles: 'image/*',
                dictDefaultMessage: '{{ __('Upload Image') }}',
                dictRemoveFile: '<button class="btn btn-danger"> <i class="fa fa-trash center"></i></button>',
                params: {
                    _token: '{{ csrf_token() }}',
                    path: path,
                    width: 500,
                    height: 600
                },
                addRemoveLinks: true,
                removedfile:function (file) {
                    var imageName = $('.image_name').val();
                    $.ajax({
                        dataType: 'json',
                        type: 'POST',
                        url: '{{ route('remove.image') }}',
                        data: {
                            _token: '{{ csrf_token() }}',
                            image: imageName,
                            path: path
                        }
                    });
                    var fmock;
                    return (fmock = file.previewElement) != null ? fmock.parentNode.removeChild(file.previewElement): void 0;
                },
                init: function () {
                    @if(!empty(old('image')))
                        @php $image = \App\Models\Media::where('id', old('image'))->first(); @endphp
                        var mock = { name: '{{ $image->name }}', size: 2};
                        this.emit('addedfile', mock);
                        this.emit('thumbnail', mock, '{{ $image->tempMediaImage }}');
                        this.emit('complete', mock);
                        $('.dz-progress').remove();
                    @else
                        var mock = { name: '{{ $company->name }}', size: 2};
                        this.emit('addedfile', mock);
                        this.emit('thumbnail', mock, '{{ $company->thumbImage }}');
                        this.emit('complete', mock);
                        $('.dz-progress').remove();
                    @endif

                        this.on("success", function (file, image) {
                        $('.image_name').val(image);
                    })
                }
            });
        });
    </script>
    <style>
        .dropzone {
            width: 200px;
            height: 90px;
            min-height: 0px !important;
            background-color: #1C2260;
            border: #1C2260;
        }
    </style>
@endpush

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
                    <h1 class="m-0"> {{ __('Company Details') }} </h1>
                </div>
            </div>
        </div>

        <div class="container-fluid page__container">

            <div class="card-header card-header-tabs-basic nav" role="tablist">
                <a href="#company_tab" class="{{ request()->tab == 'company' ? 'active' : '' }}" data-toggle="tab" role="tab" aria-controls="company_tab" aria-selected="true"><i class="fa fa-building"></i> {{ __('Company Details') }}</a>
                <a href="#floors_tab" class="{{ request()->tab == 'floors' ? 'active' : '' }}" data-toggle="tab" role="tab" aria-controls="floors_tab" aria-selected="false"><i class="fa fa-chevron-up"></i> {{ __('Floors') }}</a>
                <a href="#paths_tab" class="{{ request()->tab == 'paths' ? 'active' : '' }}" data-toggle="tab" role="tab" aria-controls="paths_tab" aria-selected="false"><i class="fa fa-compass"></i> {{ __('Paths') }}</a>
                <a href="#offices_tab" class="{{ request()->tab == 'offices' ? 'active' : '' }}" data-toggle="tab" role="tab" aria-controls="offices_tab" aria-selected="false"><i class="fa fa-table"></i> {{ __('Offices') }}</a>
                <a href="#offices_contents_tab" class="{{ request()->tab == 'office_contents' ? 'active' : '' }}" data-toggle="tab" role="tab" aria-controls="offices_contents_tab" aria-selected="false"><i class="fa fa-tablet-alt"></i> {{ __('Offices Contents') }}</a>
            </div>
            <div class="card card-form__body card-body">
                @if(session('success'))
                    <div class="alert alert-success success-msg">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <p class="mb-0"> {{ __('Saved successfully') }} </p>
                    </div>
                @endif

                <div class="list-group tab-content list-group-flush">
                    <div class="tab-pane {{ request()->tab == 'company' ? 'active show fade' : '' }}" id="company_tab">
                        <form method="post" action="{{ route('companies.update', $company->id) }}" class="company_form">

                            @csrf
                            @method('put')

                            @include('dashboard.partials._errors')

                            <div class="row no-gutters">
                                <div class="col card-form__body card-body">
                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="name"> {{ __("Name") }}</label>
                                            <input id="name" name="name" dir="auto" type="text" class="form-control" placeholder="{{ __("Name") }}" value="{{ old("name", $company->name) }}">
                                        </div>

                                        <div class="form-group col">
                                            <label for="email"> {{ __("Email") }}</label>
                                            <input id="email" name="email" type="text" class="form-control" placeholder="{{ __("Email") }}" value="{{ old("email", $company->email) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row no-gutters">
                                <div class="col card-form__body card-body">
                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="npass">{{ __('Password') }}*</label>
                                            <input id="npass" name="password" type="password" class="form-control" placeholder="{{ __('Password') }}">
                                        </div>
                                        <div class="form-group col">
                                            <label for="cpass">{{ __('Confirm Password') }}*</label>
                                            <input id="cpass" name="password_confirmation" type="password" class="form-control" placeholder="{{ __('Confirm Password') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row no-gutters">
                                <div class="col card-body">
                                    <p><strong class="headings-color">{{ __('Location') }}</strong></p>
                                </div>
                                <div class="col card-form__body card-body">
                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="lat">{{ __('Lat') }}</label>
                                            <input id="lat" name="lat" type="text" dir="ltr" class="form-control" value="{{ old('lat', $company->lat) }}" placeholder="{{ __('Lat') }}">
                                        </div>
                                        <div class="form-group col">
                                            <label for="lng">{{ __('Lng') }}</label>
                                            <input id="lng" name="lng" type="text" dir="ltr" class="form-control" value="{{ old('lng', $company->lng) }}" placeholder="{{ __('Lng') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="address"> {{ __("Address") }}</label>
                                <input id="address" name="address" dir="auto" type="text" class="form-control" placeholder="{{ __("Address") }}" value="{{ old("address", $company->address) }}">
                            </div>


                            <div class="row no-gutters">
                                <div class="col card-form__body card-body">
                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="phonenumber"> {{ __("Phone Number") }}*</label>
                                            <input id="phonenumber" name="phonenumber" dir="auto" type="text" class="form-control" placeholder="{{ __("Phone Number") }}" value="{{ old("phonenumber", $company->phonenumber) }}" required>
                                        </div>

                                        <div class="form-group col">
                                            <label for="supervisor_id"> {{ __('Responsible Supervisor') }}</label> <br>
                                            <select id="supervisor_id" name="supervisor_id" data-toggle="select" class="form-control select2" required>
                                                <option value="" selected> {{ __('Responsible Supervisor') }} </option>
                                                @forelse($supervisors as $supervisor)
                                                    <option value="{{ $supervisor->id }}" {{ old('supervisor_id', $company->supervisor_id) == $supervisor->id ? 'selected' : '' }}>
                                                        {{ $supervisor->name }}
                                                    </option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <input class="image_name" type="hidden" name="image" value="">
                            </div>

                            <div class="form-group">
                                <label> {{ __('Image') }} </label>
                                <div class="dropzone" id="mainphoto"></div>
                            </div>

                            <div class="text-right mb-5">
                                <input type="submit" class="btn btn-success company-btn" value="{{ __('Update') }}" id="companyBtn">
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane {{ request()->tab == 'floors' ? 'active show fade' : '' }}" id="floors_tab">
                        <form method="post" action="{{ route('floors.store') }}">

                            @csrf
                            @include('dashboard.partials._errors')

                            <input type="hidden" name="user_id" value="{{ $company->id }}">

                            @forelse($floors as $floor)
                                <input type="hidden" name="floor_id[]" value="{{ $floor->id }}">

                            <div class="row">
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="title"> {{ __("Title") }}</label>
                                        <input id="title" name="title[]" dir="auto" type="text" class="form-control" placeholder="{{ __("Title") }}" value="{{ old("title", $floor->title) }}">
                                    </div>
                                </div>

                                <div class="col-sm">
                                    <div class="form-group">
                                        <label>{{ __('Action') }}</label>
                                        <button type="button"
                                                class="btn btn-danger btn-lg delete-btn form-control"
                                                data-action="{{ route('floors.destroy', $floor->id) }}"
                                                style="display: block; width: 40px;">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            @empty
                                <input type="hidden" name="floor_id[]" value="0">

                                <div class="form-group">
                                    <label for="title"> {{ __("Title") }}</label>
                                    <input id="title" name="title[]" dir="auto" type="text" class="form-control" placeholder="{{ __("Title") }}" value="{{ old("title") }}">
                                </div>
                            @endforelse

                            <button class="btn btn-info ml-3 new_floor">
                                {{ __('Create new Floor') }} <i class="material-icons">add</i>
                            </button>

                            <div class="text-right mb-5">
                                <input type="submit" class="btn btn-success" value="{{ __('Add') }}">
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane {{ request()->tab == 'paths' ? 'active show fade' : '' }}" id="paths_tab">
                        <form method="post" action="{{ route('paths.store') }}">

                            @csrf
                            @include('dashboard.partials._errors')

                            <div class="card card-form d-flex flex-column flex-sm-row">
                                <div class="card-form__body card-body-form-group flex">

                                    <input type="hidden" name="user_id" value="{{ $company->id }}">

                                    @forelse($paths as $path)
                                        <input type="hidden" name="path_id[]" value="{{ $path->id }}">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="floor_id">{{ __('Floors') }}</label><br>
                                                    <select id="floor_id" class="custom-select" name="floor_id[]" style="width: 300px;">
                                                        @forelse($floors as $floor)
                                                            <option value="{{ $floor->id }}" {{ $floor->id == $path->floor_id ? 'selected' : '' }}> {{ $floor->title }} </option>
                                                        @empty
                                                            <option> {{ __('No Floors') }} </option>
                                                        @endforelse
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="title">{{ __('Name') }}</label>
                                                    <input id="title" name="title[]" type="text" class="form-control" value="{{ $path->title }}" placeholder="{{ __('Title') }}">
                                                </div>
                                            </div>

                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label>{{ __('Action') }}</label>
                                                    <button type="button"
                                                            class="btn btn-danger btn-lg delete-btn form-control"
                                                            data-action="{{ route('paths.destroy', $path->id) }}"
                                                            style="display: block; width: 40px;">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    @empty
                                    @endforelse
                                </div>
                            </div>

                            <button class="btn btn-info ml-3 new_path">
                                {{ __('Create New Path') }} <i class="material-icons">add</i>
                            </button>

                            <div class="text-right mb-5">
                                <input type="submit" class="btn btn-success" value="{{ __('Add') }}">
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane {{ request()->tab == 'offices' ? 'active show fade' : '' }}" id="offices_tab">
                        <form method="post" action="{{ route('offices.store') }}">

                            @csrf
                            @include('dashboard.partials._errors')

                            <div class="card card-form d-flex flex-column flex-sm-row">
                                <div class="card-form__body card-body-form-group flex">

                                    <input type="hidden" name="user_id" value="{{ $company->id }}">

                                    @forelse($offices as $office)
                                        <input type="hidden" name="office_id[]" value="{{ $office->id }}">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="floor_id_office">{{ __('Floors') }}</label><br>
                                                    <select id="floor_id_office" class="custom-select floors" name="floor_id[]" style="width: 300px;">
                                                        <option value="0" selected> {{ __('Floors') }} </option>
                                                        @forelse($floors as $floor)
                                                            <option value="{{ $floor->id }}" {{ $floor->id == $path->floor_id ? 'selected' : '' }}> {{ $floor->title }} </option>
                                                        @empty
                                                            <option> {{ __('No Floors') }} </option>
                                                        @endforelse
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="path_id_office">{{ __('Paths') }}</label><br>
                                                    <select id="path_id_office" class="custom-select paths" name="path_id[]" style="width: 300px;">
                                                        @if($office->path_id)
                                                            @forelse($paths as $path)
                                                                @if($path->floor_id == $office->floor_id)
                                                                    <option value="{{ $path->id }}" {{ $path->id == $office->path_id ? 'selected' : '' }}> {{ $path->title }} </option>
                                                                @endif
                                                            @empty
                                                                <option> {{ __('No Floors') }} </option>
                                                            @endforelse
                                                        @else
                                                            <option value="0">
                                                                {{ __('Choose Floor First') }}
                                                            </option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="office_title">{{ __('Name') }}</label>
                                                    <input id="office_title" name="title[]" type="text" class="form-control" value="{{ $office->title }}" placeholder="{{ __('Title') }}">
                                                </div>
                                            </div>

                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label>{{ __('Action') }}</label>
                                                    <button type="button"
                                                            class="btn btn-danger btn-lg delete-btn form-control"
                                                            data-action="{{ route('offices.destroy', $office->id) }}"
                                                            style="display: block; width: 40px;">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    @empty
                                    @endforelse
                                </div>
                            </div>

                            <button class="btn btn-info ml-3 new_office">
                                {{ __('Create New Office') }} <i class="material-icons">add</i>
                            </button>

                            <div class="text-right mb-5">
                                <input type="submit" class="btn btn-success" value="{{ __('Add') }}">
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane {{ request()->tab == 'office_contents' ? 'active show fade' : '' }}" id="offices_contents_tab">
                        <form method="post" action="{{ route('contents.store') }}">

                            @csrf
                            @include('dashboard.partials._errors')

                            <div class="card card-form d-flex flex-column flex-sm-row">
                                <div class="card-form__body card-body-form-group flex">

                                    <input type="hidden" name="user_id" value="{{ $company->id }}">

                                    @forelse($contents as $content)
                                        <input type="hidden" name="content_id[]" value="{{ $content->id }}">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="floor_id_content">{{ __('Floors') }}</label><br>
                                                    <select id="floor_id_content" class="custom-select floors" name="floor_id[]" style="width: 200px;">
                                                        @forelse($floors as $floor)
                                                            <option value="{{ $floor->id }}" {{ $floor->id == $content->floor_id ? 'selected' : '' }}> {{ $floor->title }} </option>
                                                        @empty
                                                            <option> {{ __('No Floors') }} </option>
                                                        @endforelse
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="path_id_content">{{ __('Paths') }}</label><br>
                                                    <select id="path_id_content" class="custom-select paths" name="path_id[]" style="width: 200px;">
                                                        @forelse($paths as $path)
                                                            @if($path->floor_id == $content->floor_id)
                                                                <option value="{{ $path->id }}" {{ $path->id == $content->path_id ? 'selected' : '' }}> {{ $path->title }} </option>
                                                            @endif
                                                        @empty
                                                            <option> {{ __('No Paths') }} </option>
                                                        @endforelse
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="office_id_content">{{ __('Offices') }}</label><br>
                                                    <select id="office_id_content" class="custom-select offices" name="office_id[]" style="width: 200px;">
                                                        @forelse($offices as $office)
                                                            @if($office->path_id == $content->path_id)
                                                                <option value="{{ $office->id }}" {{ $office->id == $content->office_id ? 'selected' : '' }}> {{ $office->title }} </option>
                                                            @endif
                                                        @empty
                                                            <option> {{ __('No Offices') }} </option>
                                                        @endforelse
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm">
                                                <div class="form-group" style="width: 300px;">
                                                    <label for="content">{{ __('Name') }}</label>
                                                    <input id="content" name="content[]" type="text" class="form-control" value="{{ $content->content }}" placeholder="{{ __('Content') }}">
                                                </div>
                                            </div>

                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label>{{ __('Action') }}</label>
                                                    <button type="button"
                                                            class="btn btn-danger btn-lg delete-btn form-control"
                                                            data-action="{{ route('contents.destroy', $content->id) }}"
                                                            style="display: block; width: 40px;">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    @empty
                                    @endforelse
                                </div>
                            </div>

                            <button class="btn btn-info ml-3 new_content">
                                {{ __('Create New Content') }} <i class="material-icons">add</i>
                            </button>

                            <div class="text-right mb-5">
                                <input type="submit" class="btn btn-success" value="{{ __('Add') }}">
                            </div>
                        </form>
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
            // add new floor
            $(".new_floor").click( function (e) {
                e.preventDefault();
                $('.new_floor').before(
                    '<input type="hidden" name="floor_id[]" value="0">'+
                    '<div class="form-group titles">'+
                        '<label for="title"> {{ __("Title") }}</label>'+
                        '<input id="title" name="title[]" dir="auto" type="text" class="form-control" placeholder="{{ __("Title") }}" value="{{ old("title") }}">'+
                    '</div>'
                );
            });

            // add new path
            $(".new_path").click( function (e) {
                e.preventDefault();
                $('.new_path').before(
                    '<div class="row">'+
                    '<input type="hidden" name="path_id[]" value=0>'+
                    '<div class="col-sm-auto">'+
                            '<div class="form-group">'+
                                '<label for="floor_id">{{ __('Floors') }}</label><br>'+
                                '<select id="floor_id" class="custom-select" name="floor_id[]" style="width: 200px;">'+
                                    @forelse($floors as $floor)
                                    '<option value="{{ $floor->id }}">{{ $floor->title }}</option>'+
                                    @empty
                                    '<option value=0> {{ __('No Floors') }} </option>'+
                                    @endforelse
                                '</select>'+
                            '</div>'+
                        '</div>'+

                        '<div class="col-sm-auto" style="width: 60%;">'+
                            '<div class="form-group">'+
                                '<label for="title">{{ __('Name') }}</label>'+
                                '<input id="title" name="title[]" type="text" class="form-control" placeholder="{{ __('Title') }}">'+
                            '</div>'+
                        '</div>'+
                    '</div>'
                );
            });

            // click on delete Btn
            $('.delete-btn').click(function (e){
               let that = $(this);
               e.preventDefault();
                var n = new Noty({
                    theme: 'sunset',
                    text: "{{ __('Confirm deleting record') }}",
                    killer: true,
                    buttons: [
                        Noty.button("{{ __('Yes') }}", 'btn btn-success mr-2', function () {
                            let action = that.data('action');
                            console.log(action);
                            $.ajax({
                                method: 'DELETE',
                                url: action,
                                data_type: 'html',
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr('content'),
                                }, success: function () {
                                    location.reload();
                                }
                            })
                        }),
                        Noty.button("{{ __('No') }}", 'btn btn-danger', function () {
                            n.close();
                        })
                    ]
                });
                n.show();
            });

            // add new Office
            $(".new_office").click( function (e) {
                e.preventDefault();
                $('.new_office').before(
                    '<div class="row">'+
                        '<input type="hidden" name="office_id[]" value="0">'+
                        '<div class="col-sm-auto">'+
                            '<div class="form-group">'+
                                '<label for="floor_id_office">{{ __('Floors') }}</label><br>'+
                                '<select id="floor_id_office" class="custom-select floors" name="floor_id[]" style="width: 200px;">'+
                                    '<option value=0 selected> {{ __('Floor Title') }} </option>'+
                                    @forelse($floors as $floor)
                                        '<option value="{{ $floor->id }}">{{ $floor->title }}</option>'+
                                    @empty
                                        '<option value=0> {{ __('No Floors') }} </option>'+
                                    @endforelse
                                    '</select>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-sm-auto">'+
                            '<div class="form-group">'+
                                '<label for="path_id_office">{{ __('Paths') }}</label><br>'+
                                '<select id="path_id_office" class="custom-select paths" name="path_id[]" style="width: 200px;">'+
                                    '<option value=0> {{ __('No Paths') }} </option>'+
                                '</select>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-sm-auto" style="width: 60%;">'+
                            '<div class="form-group">'+
                                '<label for="title_office">{{ __('Name') }}</label>'+
                                '<input id="title_office" name="title[]" type="text" class="form-control" placeholder="{{ __('Title') }}">'+
                            '</div>'+
                        '</div>'+
                    '</div>'
                );
            });

            // add new Office Content
            $(".new_content").click( function (e) {
                e.preventDefault();
                $('.new_content').before(
                    '<div class="row">'+
                        '<input type="hidden" name="content_id[]" value=0>'+

                        '<div class="col-sm-auto">'+
                            '<div class="form-group">'+
                                '<label for="floor_id_office">{{ __('Floors') }}</label><br>'+
                                '<select id="floor_id_office" class="custom-select floors" name="floor_id[]" style="width: 200px;">'+
                                    '<option value=0> {{ __('Select Floor') }} </option>'+
                                    @forelse($floors as $floor)
                                        '<option value="{{ $floor->id }}">{{ $floor->title }}</option>'+
                                    @empty
                                        '<option value=0> {{ __('No Floors') }} </option>'+
                                    @endforelse
                                '</select>'+
                            '</div>'+
                        '</div>'+

                        '<div class="col-sm-auto">'+
                            '<div class="form-group">'+
                                '<label for="path_id_office">{{ __('Paths') }}</label><br>'+
                                '<select id="path_id_office" class="custom-select paths" name="path_id[]" style="width: 200px;">'+
                                    '<option value=0> {{ __('Choose Floor First') }} </option>'+
                                '</select>'+
                            '</div>'+
                        '</div>'+

                        '<div class="col-sm-auto">'+
                            '<div class="form-group">'+
                                '<label for="office_id">{{ __('Offices') }}</label><br>'+
                                '<select id="office_id" class="custom-select offices" name="office_id[]" style="width: 200px;">'+
                                    '<option value=0> {{ __('Choose Path First') }} </option>'+
                                '</select>'+
                            '</div>'+
                        '</div>'+

                        '<div class="col-sm-auto" style="width: 40%;">'+
                            '<div class="form-group">'+
                                '<label for="content_office">{{ __('Content') }}</label>'+
                                '<input id="content_office" name="content[]" type="text" class="form-control" placeholder="{{ __('Content') }}">'+
                            '</div>'+
                        '</div>'+
                    '</div>'
                );
            });

            $(document).on('change', '.floors', function () {
                var floorId = $(this).find(":selected").val();
                let paths = $(this).closest('.row').find('.paths');
                if(floorId) {
                    $.ajax({
                        url: "/dashboard/offices/paths",
                        data_type: 'html',
                        method: "POST",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            floor_id: floorId
                        }, success: function (data) {
                            paths.html(data);
                        }
                    });
                } else {
                    paths.html('');
                }
            });

            $(document).on('change', '.paths', function () {
                var pathId = $(this).find(":selected").val();
                let offices = $(this).closest('.row').find('.offices');
                if(pathId) {
                    $.ajax({
                        url: '/dashboard/contents/offices',
                        data_type: 'html',
                        method: "POST",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            path_id: pathId
                        }, success: function (data) {
                            offices.html(data);
                        }
                    });
                } else {
                    offices.html('');
                }
            });

        });
    </script>
@endpush
