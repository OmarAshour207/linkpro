<div class="table-responsive border-bottom" data-toggle="lists" data-lists-values='["js-lists-values-employee-name"]'>
    <table class="table mb-0 thead-border-top-0">
        <thead>
        <tr>
            <th style="width: 18px;">
                {{ __('Select') }}
            </th>
            <th> {{ __('Content') }}</th>
            <th> {{ __('Quantity') }} </th>
            <th> {{ __('Unit') }} </th>
        </tr>
        </thead>
        <tbody class="list" id="staff">
        @forelse($supplies as $index => $supply)
            <tr class="selected">
                <input type="hidden" name="supplies[{{ $index }}][supply_id]" value="{{ $supply->id }}">
                <td style="width: 18px;">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="supplies[{{ $index }}][box]" class="form-check-input" id="checkbox{{ $index }}">
                        <label class="form-check-label" for="checkbox{{ $index }}"><span class="text-hide">Check</span></label>
                    </div>
                </td>

                <td style="width: 300px;">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <input type="text" class="form-control" placeholder="{{ __('Content') }}" value="{{ $supply->name }}" disabled>
                        </div>
                    </div>
                </td>

                <td style="width: 400px;">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <input id="quantity" name="supplies[{{ $index }}][quantity]" type="number" class="form-control" placeholder="{{ __('Quantity') }}">
                        </div>
                    </div>
                </td>

                <td style="width: 400px;">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <input id="unit" name="supplies[{{ $index }}][unit]" type="text" value="{{ $supply->unit }}" class="form-control" placeholder="{{ __('Unit') }}" disabled>
                        </div>
                    </div>
                </td>
            </tr>
        @empty
            <div class="row no-gutters contents">
                <div class="col card-form__body card-body">
                    <div class="row">
                        <div class="form-group col">
                            <label for="content">{{ __('Empty Supplies') }}</label>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
        </tbody>
    </table>
</div>
