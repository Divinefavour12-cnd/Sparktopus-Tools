<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-form id="frmAgeCalc" class="no-app-loader" method="post" :route="route('tool.handle', $tool->slug)">
            <div class="row custom-textarea-wrapper">
                <div class="col-md-12 mb-4">
                    <div class="custom-textarea p-4 shadow-sm border rounded bg-white">
                        <div class="row g-4 mb-4 pb-4 border-bottom">
                            <div class="col-md-12">
                                <h3 class="fw-bold mb-0">@lang('tools.birthDate')</h3>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <x-input-label class="small text-muted text-uppercase fw-bold mb-2">@lang('tools.year')</x-input-label>
                                    <select name="birth_year" id="year" required class="form-control form-select border-light bg-light shadow-none py-2"></select>
                                </div>
                                <x-input-error :messages="$errors->get('birth_year')" class="mt-2" />
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <x-input-label class="small text-muted text-uppercase fw-bold mb-2">@lang('tools.month')</x-input-label>
                                    <select name="birth_month" id="month" required class="form-control form-select border-light bg-light shadow-none py-2"></select>
                                </div>
                                <x-input-error :messages="$errors->get('birth_month')" class="mt-2" />
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <x-input-label class="small text-muted text-uppercase fw-bold mb-2">@lang('tools.day')</x-input-label>
                                    <select name="birth_day" id="day" required class="form-control form-select border-light bg-light shadow-none py-2"></select>
                                </div>
                                <x-input-error :messages="$errors->get('birth_day')" class="mt-2" />
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-12">
                                <h3 class="fw-bold mb-0">@lang('tools.dateFrom')</h3>
                            </div>
                            <div class="col-md-4 mb-0">
                                <div class="form-group">
                                    <x-input-label class="small text-muted text-uppercase fw-bold mb-2">@lang('tools.year')</x-input-label>
                                    <select name="from_year" id="from_year" required class="form-control form-select border-light bg-light shadow-none py-2"></select>
                                </div>
                                <x-input-error :messages="$errors->get('from_year')" class="mt-2" />
                            </div>
                            <div class="col-md-4 mb-0">
                                <div class="form-group">
                                    <x-input-label class="small text-muted text-uppercase fw-bold mb-2">@lang('tools.month')</x-input-label>
                                    <select name="from_month" id="from_month" required class="form-control form-select border-light bg-light shadow-none py-2"></select>
                                </div>
                                <x-input-error :messages="$errors->get('from_month')" class="mt-2" />
                            </div>
                            <div class="col-md-4 mb-0">
                                <div class="form-group">
                                    <x-input-label class="small text-muted text-uppercase fw-bold mb-2">@lang('tools.day')</x-input-label>
                                    <select name="from_day" id="from_day" required class="form-control form-select border-light bg-light shadow-none py-2"></select>
                                </div>
                                <x-input-error :messages="$errors->get('from_day')" class="mt-2" />
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        <div class="d-flex justify-content-end">
                            <x-button type="submit" id="btnSubmit" class="btn btn-primary btn-lg rounded-pill px-5">
                                <i class="bi bi-calculate me-2"></i> @lang('tools.calculateAge')
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>

    @if (isset($results))
        <div class="tool-results-wrapper mt-5">
            <x-page-wrapper :title="__('common.result')" class="tool-age-calculator-results">
                <div class="result mt-4">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="p-5 shadow-sm border rounded-4 text-center bg-white hover-elevate transition-all border-primary-subtle border-w-2">
                                <div class="text-muted fw-bold text-uppercase small mb-3">@lang('tools.currentAge')</div>
                                <div class="display-3 fw-bold text-primary mb-2" id="add_in_years">{{ $results['years'] }}</div>
                                <div class="h4 text-muted" id="age">{{ $results['current'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-12 result-printable">
                            <div class="p-4 shadow-sm border rounded-4 bg-white">
                                <h3 class="fw-bold mb-4 border-bottom pb-2">@lang('common.ageBreakdown')</h3>
                                <table class="table table-hover align-middle mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="ps-0 py-3">@lang('tools.ageInMonths')</th>
                                            <td class="py-3">
                                                <div class="fw-bold text-dark fs-5" id="age_months">{{ $results['months'] }}</div>
                                            </td>
                                            <td class="text-end pe-0 d-print-none">
                                                <x-copy-target target="age_months" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 py-3">@lang('tools.ageInWeeks')</th>
                                            <td class="py-3">
                                                <div class="fw-bold text-dark fs-5" id="age_weeks">{{ $results['weeks'] }}</div>
                                            </td>
                                            <td class="text-end pe-0 d-print-none">
                                                <x-copy-target target="age_weeks" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 py-3">@lang('tools.ageInDays')</th>
                                            <td class="py-3">
                                                <div class="fw-bold text-dark fs-5" id="age_days">{{ $results['days'] }}</div>
                                            </td>
                                            <td class="text-end pe-0 d-print-none">
                                                <x-copy-target target="age_days" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 py-3">@lang('tools.ageInHours')</th>
                                            <td class="py-3">
                                                <div class="fw-bold text-dark fs-5" id="age_hours">{{ $results['hours'] }}</div>
                                            </td>
                                            <td class="text-end pe-0 d-print-none">
                                                <x-copy-target target="age_hours" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 py-3">@lang('tools.ageInMin')</th>
                                            <td class="py-3">
                                                <div class="fw-bold text-dark fs-5" id="age_min">{{ $results['minutes'] }}</div>
                                            </td>
                                            <td class="text-end pe-0 d-print-none">
                                                <x-copy-target target="age_min" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 py-3">@lang('tools.ageInSec')</th>
                                            <td class="py-3">
                                                <div class="fw-bold text-dark fs-5" id="age_sec">{{ $results['seconds'] }}</div>
                                            </td>
                                            <td class="text-end pe-0 d-print-none">
                                                <x-copy-target target="age_sec" />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12 text-end d-print-none">
                            <x-print-button
                                onclick="ArtisanApp.printResult(document.querySelector('.result-printable'), {title: '{{ $tool->name }}'})"
                                :text="__('tools.printResult')" />
                        </div>
                    </div>
                </div>
            </x-page-wrapper>
        </div>
    @endif
    <x-ad-slot :advertisement="get_advert_model('below-result')" />
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        <script>
            const APP = function() {
                const monthNumbers = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September",
                    "October", "November", "December"
                ];
                const totalYears = 200;
                const selectYear = document.getElementById("year");
                const selectMonth = document.getElementById("month");
                const selectDay = document.getElementById("day");
                const selectYear2 = document.getElementById("from_year");
                const selectMonth2 = document.getElementById("from_month");
                const selectDay2 = document.getElementById("from_day");

                const initDate = function() {
                        var currentYear = new Date().getFullYear();
                        for (var y = 0; y < totalYears; y++) {
                            let date = new Date(currentYear);
                            var yearElem = document.createElement("option");
                            yearElem.value = currentYear
                            yearElem.textContent = currentYear;
                            selectYear.append(yearElem)
                            currentYear--;
                        }

                        for (var m = 0; m < 12; m++) {
                            let month = months[m];
                            var monthElem = document.createElement("option");
                            monthElem.value = m;
                            monthElem.textContent = month;
                            selectMonth.append(monthElem)
                        }

                        var d = new Date();
                        var year = {{ isset($year) ? $year : 'd.getFullYear()' }};
                        var month = {{ isset($month) ? $month : 'd.getMonth()' }};
                        var day = {{ isset($day) ? $day : 'd.getDate()' }};
                        selectYear.addEventListener("change", AdjustDays);
                        selectMonth.addEventListener("change", AdjustDays);
                        selectYear.value = year;
                        selectMonth.value = month;
                        AdjustDays();
                        selectDay.value = day

                        function AdjustDays() {
                            var year = selectYear.value;
                            var month = parseInt(selectMonth.value) + 1;
                            var currentVal = selectDay.value;
                            removeAll(selectDay)
                            var days = new Date(year, month, 0).getDate();
                            for (var d = 1; d <= days; d++) {
                                var dayElem = document.createElement("option");
                                dayElem.value = d;
                                if (currentVal == d) {
                                    dayElem.setAttribute('selected', 'selected');
                                } else if (d < currentVal) {
                                    dayElem.setAttribute('selected', 'selected');
                                }
                                dayElem.textContent = d;
                                selectDay.append(dayElem);
                            }
                        }
                    },
                    initDate2 = function() {
                        var currentYear = new Date().getFullYear();
                        for (var y = 0; y < totalYears; y++) {
                            let date = new Date(currentYear);
                            var yearElem = document.createElement("option");
                            yearElem.value = currentYear
                            yearElem.textContent = currentYear;
                            selectYear2.append(yearElem)
                            currentYear--;
                        }

                        for (var m = 0; m < 12; m++) {
                            let month = months[m];
                            var monthElem = document.createElement("option");
                            monthElem.value = m;
                            monthElem.textContent = month;
                            selectMonth2.append(monthElem)
                        }

                        var d = new Date();
                        var year = {{ isset($year2) ? $year2 : 'd.getFullYear()' }};
                        var month = {{ isset($month2) ? $month2 : 'd.getMonth()' }};
                        var day = {{ isset($day2) ? $day2 : 'd.getDate()' }};
                        selectYear2.value = year;
                        selectYear2.addEventListener("change", AdjustDays);
                        selectMonth2.value = month;
                        selectMonth2.addEventListener("change", AdjustDays);
                        AdjustDays();
                        selectDay2.value = day

                        function AdjustDays() {
                            var year = selectYear2.value;
                            var month = parseInt(selectMonth2.value) + 1;
                            var currentVal = selectDay2.value;
                            removeAll(selectDay2)
                            var days = new Date(year, month, 0).getDate();
                            for (var d = 1; d <= days; d++) {
                                var dayElem = document.createElement("option");
                                dayElem.value = d;
                                if (currentVal == d) {
                                    dayElem.setAttribute('selected', 'selected');
                                } else if (d < currentVal) {
                                    dayElem.setAttribute('selected', 'selected');
                                }
                                dayElem.textContent = d;
                                selectDay2.append(dayElem);
                            }
                        }
                    },
                    removeAll = function(selectBox) {
                        while (selectBox.options.length > 0) {
                            selectBox.remove(0);
                        }
                    };

                return {
                    init: function() {
                        initDate()
                        initDate2()
                        const form = document.getElementById('frmAgeCalc')
                        form.addEventListener('submit', function(e) {
                            if (selectYear.value > selectYear2.value) {
                                ArtisanApp.toastError('{{ __('tools.AgeCalcGreaterError') }}');
                                e.preventDefault();
                                return false
                            }

                            if (selectYear.value == selectYear2.value) {
                                if (selectMonth.value > selectMonth2.value) {
                                    ArtisanApp.toastError('{{ __('tools.AgeCalcGreaterError') }}');
                                    e.preventDefault();
                                    return false
                                } else if (selectDay.value > selectDay2.value) {
                                    ArtisanApp.toastError('{{ __('tools.AgeCalcGreaterError') }}');
                                    e.preventDefault();
                                    return false
                                }
                            }
                            ArtisanApp.showLoader()
                        });
                    }
                }
            }();

            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endpush
</x-application-tools-wrapper>
