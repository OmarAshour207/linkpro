@forelse($supplies as $index => $supply)
    <div class="row">
        <input type="hidden" name="supplies[{{ $index }}][supply_id]" value="{{ $supply->id }}">
        <div class="form-check col-lg">
            <input type="checkbox" name="supplies[{{ $index }}][box]" class="form-check-input" id="selectbox">
            <label class="form-check-label" for="selectbox"><span class="text-hide">Check</span></label>
        </div>

        <div class="form-group col-lg">
            <input id="name" type="text" disabled class="form-control" placeholder="{{ __("Name") }}" value="{{ $supply->name }}">
        </div>

        <div class="form-group col-lg">
            <input id="content" name="supplies[{{ $index }}][quantity]" type="number" class="form-control" placeholder="{{ __("Quantity") }}">
        </div>

        <div class="form-group col-lg">
            <input id="content" name="supplies[{{ $index }}][unit]" dir="auto" type="text" class="form-control" placeholder="{{ __("Quantity Unit") }}">
        </div>
    </div>
@empty
<div class="row">
    <div class="form-group">
        <label> {{ __('No Supplies') }} </label>
    </div>
</div>
@endforelse
