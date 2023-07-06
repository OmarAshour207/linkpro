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
                    <h1 class="m-0"> {{ __('Supplies Ticket') }} </h1>
                </div>
            </div>
        </div>

        <div class="container-fluid page__container">

            <div class="card card-form__body card-body">
                <form method="post" action="{{ route('tickets.update', ['ticket' => $ticket->id, 'mode' => 'supplies']) }}">

                    @csrf
                    @method('put')

                    @include('dashboard.partials._errors')

                    <input type="hidden" name="user_id" value="{{ $ticket->user_id }}">
                    <div class="form-group col-lg-6">
                        <label for="user"> {{ __('Companies') }}</label>
                        <select id="user" name="user_id" data-toggle="select" class="form-control companies select2" disabled>
                            @forelse($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $ticket->user_id) == $user->id ? 'selected' : '' }}> {{ $user->name . ' -- ' . $user->email }} </option>
                            @empty
                                <option value="" selected> {{ __('No Records') }} </option>
                            @endforelse
                        </select>
                    </div>

                    @forelse($supplies as $index => $supply)
                        <div class="row">
                            <input type="hidden" name="supplies[{{ $index }}][supply_id]" value="{{ $supply->id }}">

                            @if(key_exists($supply->id, $suppliesIds))
                                <input type="hidden" name="supplies[{{ $index }}][id]" value="{{ $suppliesIds[$supply->id]['id'] }}">
                            @else
                            @endif

                            <div class="form-check col-lg">
                                <input type="checkbox" id="selectbox{{ $index }}" name="supplies[{{ $index }}][box]" {{ key_exists($supply->id, $suppliesIds) ? 'checked' : '' }} class="form-check-input">
                                <label class="form-check-label" for="selectbox{{ $index }}"><span class="text-hide">Check</span></label>
                            </div>

                            <div class="form-group col-lg">
                                <input id="name" type="text" disabled class="form-control" placeholder="{{ __("Name") }}" value="{{ $supply->name }}">
                            </div>

                            <div class="form-group col-lg">
                                <input id="content" name="supplies[{{ $index }}][quantity]" type="number" class="form-control" placeholder="{{ __("Quantity") }}" value="{{ key_exists($supply->id, $suppliesIds) ? $suppliesIds[$supply->id]['quantity'] : '' }}">
                            </div>

                            <div class="form-group col-lg">
                                <input id="content" name="supplies[{{ $index }}][unit]" dir="auto" type="text" class="form-control" placeholder="{{ __("Quantity Unit") }}" value="{{ key_exists($supply->id, $suppliesIds) ? $suppliesIds[$supply->id]['unit'] : '' }}">
                            </div>
                        </div>
                    @empty
                    @endforelse

                    <div class="form-group">
                        <label for="content"> {{ __("Content") }}</label>
                        <input id="content" name="content" dir="auto" type="text" class="form-control" placeholder="{{ __("Content") }}" value="{{ old("content", $ticket->content) }}">
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
