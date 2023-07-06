<option> {{ __('Office Title') }} </option>
@forelse($offices as $office)
    <option value="{{ $office->id }}" {{ old('office_id') == $office->id ? 'selected' : '' }}>
        {{ $office->title }}
    </option>
@empty
    <option> {{ __('Empty Office') }} </option>
@endforelse
