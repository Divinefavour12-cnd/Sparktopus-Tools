<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="row custom-textarea-wrapper">
                <div class="col-md-12 mb-4">
                    <div class="custom-textarea p-4 shadow-sm border rounded bg-white">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <x-input-label class="h5 fw-bold mb-3">@lang('tools.formula')</x-input-label>
                                    <select name="formula" id="formula" required
                                        class="form-control form-select border-light bg-light shadow-none py-3 fs-5">
                                        <option value="1" @if (isset($results) && $results['formula'] == '1') selected @endif>@lang('tools.whatIsPOfwhat')</option>
                                        <option value="2" @if (isset($results) && $results['formula'] == '2') selected @endif>@lang('tools.yIsWhatPercentage')</option>
                                        <option value="3" @if (isset($results) && $results['formula'] == '3') selected @endif>@lang('tools.yisPofWhat')</option>
                                        <option value="4" @if (isset($results) && $results['formula'] == '4') selected @endif>@lang('tools.whatOfXisY')</option>
                                        <option value="5" @if (isset($results) && $results['formula'] == '5') selected @endif>@lang('tools.pOfWhatIsY')</option>
                                        <option value="6" @if (isset($results) && $results['formula'] == '6') selected @endif>@lang('tools.pOfXisWhat')</option>
                                        <option value="7" @if (isset($results) && $results['formula'] == '7') selected @endif>@lang('tools.yOfOutWhat')</option>
                                        <option value="8" @if (isset($results) && $results['formula'] == '8') selected @endif>@lang('tools.whatOutOfX')</option>
                                        <option value="9" @if (isset($results) && $results['formula'] == '9') selected @endif>@lang('tools.yOutOfXis')</option>
                                        <option value="10" @if (isset($results) && $results['formula'] == '10') selected @endif>@lang('tools.xPlusPis')</option>
                                        <option value="11" @if (isset($results) && $results['formula'] == '11') selected @endif>@lang('tools.xPlusWhatIs')</option>
                                        <option value="12" @if (isset($results) && $results['formula'] == '12') selected @endif>@lang('tools.whatPlusPisY')</option>
                                        <option value="13" @if (isset($results) && $results['formula'] == '13') selected @endif>@lang('tools.xMinusPisWhat')</option>
                                        <option value="14" @if (isset($results) && $results['formula'] == '14') selected @endif>@lang('tools.XminusWhatisP')</option>
                                        <option value="15" @if (isset($results) && $results['formula'] == '15') selected @endif>@lang('tools.whatMinusPisY')</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('formula')" class="mt-2" />
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="d-flex flex-wrap align-items-center gap-3 p-3 bg-light rounded-4 border border-dashed">
                                    <div class="form-group flex-grow-1">
                                        <label class="form-label fw-bold small text-muted text-uppercase" id="beforeLabel"></label>
                                        <input class="form-control border-0 bg-white shadow-sm py-3 px-4 rounded-3 fs-3 fw-bold" name="first" id="number_1" placeholder="0" type="number"
                                            min="0" step="0.01" required="required">
                                    </div>
                                    <div class="form-group flex-grow-1">
                                        <label class="form-label fw-bold small text-muted text-uppercase" id="afterLabel"></label>
                                        <input class="form-control border-0 bg-white shadow-sm py-3 px-4 rounded-3 fs-3 fw-bold" name="second" id="number_2" placeholder="0" type="number"
                                            min="0" step="0.01" required="required">
                                    </div>
                                    <div class="form-group pt-4">
                                        <label class="form-label h3 mb-0" id="lastLabel"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        <div class="d-flex justify-content-end">
                            <x-button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">
                                <i class="bi bi-calculate me-2"></i> @lang('tools.calculate')
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>

    @if (isset($results))
        <div class="tool-results-wrapper mt-5">
            <x-page-wrapper :title="__('common.result')">
                <div class="result mt-4 result-printable">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-4 shadow-sm border rounded-4 bg-white h-100">
                                <h3 class="fw-bold mb-4 border-bottom pb-2">@lang('common.calculationDetails')</h3>
                                <table class="table table-borderless align-middle">
                                    <tbody>
                                        <tr>
                                            <th class="ps-0 py-3">@lang('tools.firstNo')</th>
                                            <td class="py-3">
                                                <div class="badge bg-light text-dark border px-3 py-2 fs-6 fw-normal" id="first">{{ $results['first'] }}</div>
                                            </td>
                                            <td class="text-end pe-0 d-print-none">
                                                <x-copy-target target="first" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 py-3">@lang('tools.secondNo')</th>
                                            <td class="py-3">
                                                <div class="badge bg-light text-dark border px-3 py-2 fs-6 fw-normal" id="second">{{ $results['second'] }}</div>
                                            </td>
                                            <td class="text-end pe-0 d-print-none">
                                                <x-copy-target target="second" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 py-3 text-primary h4">@lang('tools.result')</th>
                                            <td class="py-3">
                                                <div class="h2 fw-bold text-primary mb-0" id="calculation">
                                                    {{ round($results['solution']['calculation'], 3) }}
                                                </div>
                                            </td>
                                            <td class="text-end pe-0 d-print-none">
                                                <x-copy-target target="calculation" />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 shadow-sm border rounded-4 bg-white h-100">
                                <h3 class="fw-bold mb-4 border-bottom pb-2">@lang('tools.howWeGet')</h3>
                                <div class="bg-light p-3 rounded-4 border">
                                    <ul class="list-unstyled mb-0">
                                        @foreach (preg_split('/\R/', $results['solution']['equation']) as $string)
                                            <li class="mb-2 d-flex align-items-center">
                                                <i class="bi bi-arrow-right-short text-success me-2"></i>
                                                <span class="font-monospace">{{ $string }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="mt-4 d-flex justify-content-end gap-2 d-print-none">
                                    <x-print-button
                                        onclick="ArtisanApp.printResult(document.querySelector('.result-printable'), {title: '{{ $tool->name }}'})"
                                        :text="__('tools.printResult')" />
                                    <x-reload-button :tooltip="__('tools.calculateAnother')" :link="route('tool.show', ['tool' => $tool->slug])" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-page-wrapper>
        </div>
    @endif
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        <script>
            const APP = function() {
                const beforeText = new Array("{{ __('tools.whatIs') }}", "", "", "{{ __('tools.whatPercentOf') }}", "", "",
                    "", "{{ __('tools.whatoutOf') }}", "", "", "", "{{ __('tools.whatPlus') }}", "", "",
                    "{{ __('tools.whatMinus') }}");
                const afterText = new Array("{{ __('tools.percentOf') }}", "{{ __('tools.isWhatPercentOf') }}",
                    "{{ __('tools.is') }}", "{{ __('tools.is') }}", "{{ __('tools.percentOfWhatIs') }}",
                    "{{ __('tools.percentOf') }}", "{{ __('tools.outOfWhatIs') }}", "{{ __('tools.is') }}",
                    "{{ __('tools.outOf') }}", "{{ __('tools.plus') }}", "{{ __('tools.plusWhatPercentIs') }}",
                    "{{ __('tools.percentIs') }}", "{{ __('tools.minus') }}", "{{ __('tools.minusWhatPercentIs') }}",
                    "{{ __('tools.percentIs') }}");
                const lastText = new Array("?", "?", "{{ __('tools.percentofWhatQ') }}", "?", "?",
                    "{{ __('tools.isWhatQ') }}", "% ?", "% ?", "{{ __('tools.isWhatPercentQ') }}",
                    "{{ __('tools.percentIsWhatQ') }}", "?", "?", "{{ __('tools.percentIsWhatQ') }}", "?", "?");
                const firstPlaceholder = new Array("P", "Y", "Y", "X", "P", "P", "Y", "X", "Y", "X", "X", "P", "X", "X",
                    "P");
                const secondPlaceholder = new Array("X", "X", "P", "Y", "Y", "X", "P", "P", "X", "P", "Y", "Y", "P", "Y",
                    "Y");

                const attachEvents = function() {
                        document.querySelector('#formula').addEventListener('change', () => {
                            setText();
                        });
                    },
                    setText = function() {
                        var selectedIndex = document.getElementById("formula").selectedIndex
                        const firstLabel = document.getElementById("beforeLabel");
                        if (beforeText[selectedIndex] == '') {
                            firstLabel.classList.add('d-none')
                        } else {
                            firstLabel.classList.remove('d-none')
                        }
                        firstLabel.innerHTML = beforeText[selectedIndex];
                        document.getElementById("afterLabel").innerHTML = afterText[selectedIndex];
                        document.getElementById("lastLabel").innerHTML = lastText[selectedIndex];
                        document.getElementById("number_1").placeholder = firstPlaceholder[selectedIndex];
                        document.getElementById("number_2").placeholder = secondPlaceholder[selectedIndex];
                    };
                return {
                    init: function() {
                        attachEvents()
                        setText()
                    }
                }
            }()
            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endpush
</x-application-tools-wrapper>
