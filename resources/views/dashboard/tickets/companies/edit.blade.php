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
                    <h1 class="m-0"> {{ __('Companies Ticket') }} </h1>
                </div>
            </div>
        </div>

        <div class="container-fluid page__container">

            <div class="card card-form__body card-body">
                <form method="post" action="{{ route('tickets.update', ['ticket' => $ticket->id, 'mode' => 'companies']) }}">

                    @csrf
                    @method('put')

                    @include('dashboard.partials._errors')

                    <input type="hidden" name="user_id" value="{{ $ticket->user_id }}">

                    <div class="form-group col-lg-6">
                        <label for="user"> {{ __('Companies') }}</label>
                        <select id="user" name="user_id" data-toggle="select" class="form-control company_id select2">
                            <option value="" selected> {{ __('Companies') }} </option>
                            @forelse($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $ticket->user_id) == $user->id ? 'selected' : '' }}> {{ $user->name . ' -- ' . $user->email }} </option>
                            @empty
                                <option value="" selected> {{ __('No Records') }} </option>
                            @endforelse
                        </select>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="floor_id"> {{ __('Floors') }}</label> <br>
                        <select id="floor_id" name="floor_id" data-toggle="select" class="form-control select2 floors">
                            <option value=""> {{ __('Floor Title') }} </option>
                            @forelse($floors as $floor)
                                <option value="{{ $floor->id }}" {{ old('floor_id', $officeContent->floor_id) == $floor->id ? 'selected' : '' }}> {{ $floor->title }} </option>
                            @empty
                            @endforelse
                        </select>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label for="path_id"> {{ __('Paths') }}</label> <br>
                            <select id="path_id" name="path_id" data-toggle="select" class="form-control select2 paths">
                                <option value=""> {{ __('Path Title') }} </option>
                                @forelse($paths as $path)
                                    <option value="{{ $path->id }}" {{ old('path_id', $officeContent->path_id) == $path->id ? 'selected' : '' }}> {{ $path->title }} </option>
                                @empty
                                @endforelse

                            </select>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="office_id"> {{ __('Offices') }}</label> <br>
                            <select id="office_id" name="office_id" data-toggle="select" class="form-control select2 offices">
                                <option value=""> {{ __('Office Title') }} </option>
                                @forelse($offices as $office)
                                    <option value="{{ $office->id }}" {{ old('office_id', $officeContent->office_id) == $office->id ? 'selected' : '' }}> {{ $office->title }} </option>
                                @empty
                                @endforelse

                            </select>
                        </div>
                    </div>

                    <div class="contents">
                        @forelse($officeContents as $index => $content)
                            <div class="row no-gutters contents">
                                <div class="col card-form__body card-body">
                                    <div class="row">
                                        <input type="hidden" name="officecontents[{{ $index }}][officecontent_id]" value="{{ $content->id }}">
                                        @if(key_exists($content->id, $contentsIds))
                                            <input type="hidden" name="officecontents[{{ $index }}][id]" value="{{ $contentsIds[$content->id]['id'] }}">
                                        @else
                                        @endif

                                        <div class="form-check col">
                                            <input type="checkbox" name="officecontents[{{ $index }}][box]" {{ key_exists($content->id, $contentsIds) ? 'checked' : '' }} class="form-check-input" id="selectbox{{ $index }}">
                                            <label class="form-check-label" for="selectbox{{ $index }}"><span class="text-hide">Check</span></label>
                                        </div>

                                        <div class="form-group col">
                                            <label for="content">{{ __('Content') }}</label>
                                            <input id="content" name="officecontents[{{ $index }}][content]" type="text" class="form-control" placeholder="{{ __('Content') }}" value="{{ $content->content }}" disabled>
                                        </div>
                                        <div class="form-group col">
                                            <label for="note">{{ __('Note') }}</label>
                                            <input id="note" name="officecontents[{{ $index }}][notes]" type="text" class="form-control" placeholder="{{ __('Note') }}" value="{{ key_exists($content->id, $contentsIds) ? $contentsIds[$content->id]['notes'] : '' }}">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="row no-gutters contents">
                                <div class="col card-form__body card-body">
                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="content">{{ __('Empty Contents') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
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
