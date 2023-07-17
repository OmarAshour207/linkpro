<div class="table-responsive border-bottom" data-toggle="lists" data-lists-values='["js-lists-values-employee-name"]'>
    <table class="table mb-0 thead-border-top-0">
        @if(count($contents))
            <thead>
                <tr>
                    <th style="width: 18px;">
                        {{ __('Select') }}
                    </th>
                    <th>{{ __('Content') }}</th>
                    <th> {{ __('Notes') }} </th>
                </tr>
            </thead>
        @endif
        <tbody class="list" id="staff">
        @forelse($contents as $index => $content)
            <tr class="selected">
                <input type="hidden" name="tickets[{{ $index }}][content_id]" value="{{ $content->id }}">
                <td style="width: 18px;">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="tickets[{{ $index }}][box]" class="form-check-input" id="selectbox{{ $index }}">
                        <label class="form-check-label" for="selectbox{{ $index }}"><span class="text-hide">Check</span></label>
                    </div>
                </td>

                <td style="width: 300px;">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <input id="content" type="text" class="form-control" placeholder="{{ __('Content') }}" value="{{ $content->content }}" disabled>
                        </div>
                    </div>
                </td>

                <td style="width: 400px;">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <input id="note" name="tickets[{{ $index }}][note]" type="text" class="form-control" placeholder="{{ __('Note') }}">
                        </div>
                    </div>
                </td>
            </tr>
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
        </tbody>
    </table>
</div>
