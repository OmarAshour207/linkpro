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
                        this.emit('thumbnail', mock, '{{ $company->thumbUserImage }}');
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
                    <h1 class="m-0"> {{ __('Companies') }} </h1>
                </div>
            </div>
        </div>

        <div class="container-fluid page__container">

            <div class="card card-form__body card-body">
                <form method="post" action="{{ route('companies.update', $company->id) }}">

                    @csrf
                    @method('put')

                    @include('dashboard.partials._errors')

                    <div class="form-group">
                        <label for="name"> {{ __("Name") }}</label>
                        <input id="name" name="name" dir="auto" type="text" class="form-control" placeholder="{{ __("Name") }}" value="{{ old("name", $company->name) }}">
                    </div>

                    <div class="form-group">
                        <label for="email"> {{ __("Email") }}</label>
                        <input id="email" name="email" type="text" class="form-control" placeholder="{{ __("Email") }}" value="{{ old("email", $company->email) }}">
                    </div>

                    <div class="row no-gutters">
                        <div class="col card-form__body card-body">
                            <div class="row">
                                <div class="form-group col">
                                    <label for="npass">{{ __('Password') }}</label>
                                    <input id="npass" name="password" type="password" class="form-control" placeholder="{{ __('Password') }}">
                                </div>
                                <div class="form-group col">
                                    <label for="cpass">{{ __('Confirm Password') }}</label>
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

                    <div class="form-group">
                        <label for="phonenumber"> {{ __("Phone Number") }}</label>
                        <input id="phonenumber" name="phonenumber" dir="auto" type="text" class="form-control" placeholder="{{ __("Phone Number") }}" value="{{ old("phonenumber", $company->phonenumber) }}">
                    </div>

                    <div class="form-group">
                        <label for="supervisor_id"> {{ __('Responsible Supervisor') }}</label> <br>
                        <select id="supervisor_id" name="supervisor_id" data-toggle="select" class="form-control select2">
                            <option value="" selected> {{ __('Responsible Supervisor') }} </option>
                            @forelse($supervisors as $supervisor)
                                <option value="{{ $supervisor->id }}" {{ old('supervisor_id', $company->supervisor_id) == $supervisor->id ? 'selected' : '' }}>
                                    {{ $supervisor->name }}
                                </option>
                            @empty
                            @endforelse
                        </select>
                    </div>

                    <div class="form-group">
                        <input class="image_name" type="hidden" name="image" value="">
                    </div>

                    <div class="form-group">
                        <label> {{ __('Image') }} </label>
                        <div class="dropzone" id="mainphoto"></div>
                    </div>

                    <div class="text-right mb-5">
                        <input type="submit" class="btn btn-success" value="{{ __('Update') }}">
                    </div>
                </form>
            </div>
        </div>
        <!-- // END drawer-layout__content -->
    </div>
@endsection
