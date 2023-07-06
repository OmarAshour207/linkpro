<option> {{ __('Path Title') }} </option>
@forelse($paths as $path)
    <option value="{{ $path->id }}" {{ old('path_id', isset($pathId) ?? '') == $path->id ? 'selected' : '' }}>
        {{ $path->title }}
    </option>
@empty
    <option> {{ __('Empty Paths') }} </option>
@endforelse
