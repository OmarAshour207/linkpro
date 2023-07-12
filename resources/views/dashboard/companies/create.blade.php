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
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Create') }}</li>
                        </ol>
                    </nav>
                    <h1 class="m-0"> {{ __('Companies') }} </h1>
                </div>
            </div>
        </div>

        <div class="container-fluid page__container">

            <div class="card card-form__body card-body">
                <div class="alert alert-success success-msg" style="display: none;">
                    <p class="mb-0"> {{ __('Saved successfully') }} </p>
                </div>
                <div class="alert alert-danger danger-msg" style="display: none;"></div>

                <div class="card-header card-header-tabs-basic nav" role="tablist">
                    <a href="#activity_all" class="active" data-toggle="tab" role="tab" aria-controls="activity_all" aria-selected="true"><i class="fa fa-building"></i> {{ __('Company Details') }}</a>
                    <a href="#floors_tab" data-toggle="tab" role="tab" aria-controls="floors_tab" aria-selected="false"><i class="fa fa-chevron-up"></i> {{ __('Floors') }}</a>
                    <a href="#activity_emails" data-toggle="tab" role="tab" aria-controls="activity_emails" aria-selected="false"><i class="fa fa-compass"></i> {{ __('Paths') }}</a>
                    <a href="#activity_quotes" data-toggle="tab" role="tab" aria-controls="activity_quotes" aria-selected="false"><i class="fa fa-table"></i> {{ __('Offices') }}</a>
                    <a href="#offices_contents" data-toggle="tab" role="tab" aria-controls="offices_contents" aria-selected="false"><i class="fa fa-tablet-alt"></i> {{ __('Offices Contents') }}</a>
                </div>
                <div class="list-group tab-content list-group-flush">
                    <div class="tab-pane active show fade" id="activity_all">
                        <form method="post" action="{{ route('companies.store') }}">

                            @csrf
                            @include('dashboard.partials._errors')

                            <div class="row no-gutters">
                                <div class="col card-form__body card-body">
                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="name"> {{ __("Name") }}</label>
                                            <input id="name" name="name" dir="auto" type="text" class="form-control" placeholder="{{ __("Name") }}" value="{{ old("name") }}">
                                        </div>

                                        <div class="form-group col">
                                            <label for="email"> {{ __("Email") }}</label>
                                            <input id="email" name="email" type="text" class="form-control" placeholder="{{ __("Email") }}" value="{{ old("email") }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row no-gutters">
                                <div class="col card-form__body card-body">
                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="npass">{{ __('Password') }}*</label>
                                            <input id="npass" name="password" type="password" class="form-control" placeholder="{{ __('Password') }}" required>
                                        </div>
                                        <div class="form-group col">
                                            <label for="cpass">{{ __('Confirm Password') }}*</label>
                                            <input id="cpass" name="password_confirmation" type="password" class="form-control" placeholder="{{ __('Confirm Password') }}" required>
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
                                            <input id="lat" name="lat" type="text" dir="ltr" class="form-control" value="{{ old('lat') }}" placeholder="{{ __('Lat') }}">
                                        </div>
                                        <div class="form-group col">
                                            <label for="lng">{{ __('Lng') }}</label>
                                            <input id="lng" name="lng" type="text" dir="ltr" class="form-control" value="{{ old('lng') }}" placeholder="{{ __('Lng') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="address"> {{ __("Address") }}</label>
                                <input id="address" name="address" dir="auto" type="text" class="form-control" placeholder="{{ __("Address") }}" value="{{ old("address") }}">
                            </div>


                            <div class="row no-gutters">
                                <div class="col card-form__body card-body">
                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="phonenumber"> {{ __("Phone Number") }}*</label>
                                            <input id="phonenumber" name="phonenumber" dir="auto" type="text" class="form-control" placeholder="{{ __("Phone Number") }}" value="{{ old("phonenumber") }}" required>
                                        </div>

                                        <div class="form-group col">
                                            <label for="supervisor_id"> {{ __('Responsible Supervisor') }}</label> <br>
                                            <select id="supervisor_id" name="supervisor_id" data-toggle="select" class="form-control select2">
                                                <option value="" selected> {{ __('Responsible Supervisor') }} </option>
                                                @forelse($supervisors as $supervisor)
                                                    <option value="{{ $supervisor->id }}" {{ old('supervisor_id') == $supervisor->id ? 'selected' : '' }}>
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
                                <input type="submit" class="btn btn-success" value="{{ __('Add') }}">
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="floors_tab">
                        <form method="post" action="{{ route('floors.store') }}">
                            @csrf

                            <input type="hidden" name="user_id" class="user_id_hidden_input">

                            <div class="form-group">
                                <label for="title"> {{ __("Title") }}</label>
                                <input id="title" name="title" dir="auto" type="text" class="form-control" placeholder="{{ __("Title") }}" value="{{ old("title") }}">
                            </div>

                            <div class="text-right mb-5">
                                <input type="submit" class="btn btn-success" value="{{ __('Add') }}">
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="activity_emails">

                    </div>
                    <div class="tab-pane" id="activity_quotes">

                    </div>
                    <div class="tab-pane" id="offices_contents">

                    </div>
                </div>
            </div>
        </div>
        <!-- // END drawer-layout__content -->
    </div>
@endsection

