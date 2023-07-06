@forelse($contents as $index => $content)
    <div class="row no-gutters contents">
        <div class="col card-form__body card-body">
            <div class="row">
                <input type="hidden" name="officecontents[{{ $index }}][office_content_id]" value="{{ $content->id }}">

                <div class="form-check col">
                    <input type="checkbox" name="officecontents[{{ $index }}][box]" class="form-check-input" id="selectbox{{ $index }}">
                    <label class="form-check-label" for="selectbox{{ $index }}"><span class="text-hide">Check</span></label>
                </div>

                <div class="form-group col">
                    <label for="content">{{ __('Content') }}</label>
                    <input id="content" name="officecontents[{{ $index }}][content]" type="text" class="form-control" placeholder="{{ __('Content') }}" value="{{ $content->content }}" disabled>
                </div>
                <div class="form-group col">
                    <label for="note">{{ __('Note') }}</label>
                    <input id="note" name="officecontents[{{ $index }}][notes]" type="text" class="form-control" placeholder="{{ __('Note') }}">
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
