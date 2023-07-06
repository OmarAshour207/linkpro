<option> {{ __('Floor Title') }} </option>
@forelse($floors as $floor)
    <option value="{{ $floor->id }}" {{ old('floor_id', isset($floorId) ?? '') == $floor->id ? 'selected' : '' }}>
        {{ $floor->title }}
    </option>
@empty
    <option> {{ __('Empty Floors') }} </option>
@endforelse
