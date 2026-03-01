<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-form id="frmTax" class="no-app-loader" method="get" :route="route('tool.handle', $tool->slug)">
            <div class="row custom-textarea-wrapper">
                <div class="col-md-12 mb-4">
                    <div class="custom-textarea p-4 shadow-sm border rounded bg-white">
                        <div class="row g-4 mb-4">
                            <div class="col-md-12">
                                <div class="d-flex flex-wrap gap-2 justify-content-center p-2 bg-light rounded-4 border border-dashed">
                                    <label class="btn-check-wrapper flex-grow-1">
                                        <input type="radio" id="inclusive" class="btn-check" name="type" value="1" @if (isset($type) && $type == '1') checked @endif>
                                        <label class="btn btn-outline-primary rounded-pill w-100 py-3 fw-bold border-2 transition-all" for="inclusive">
                                            <i class="bi bi-plus-circle me-2"></i> @lang('tools.inclusive')
                                        </label>
                                    </label>
                                    <label class="btn-check-wrapper flex-grow-1">
                                        <input type="radio" id="exclusive" class="btn-check" name="type" value="2" @if (isset($type) && $type == '2') checked @endif>
                                        <label class="btn btn-outline-primary rounded-pill w-100 py-3 fw-bold border-2 transition-all" for="exclusive">
                                            <i class="bi bi-dash-circle me-2"></i> @lang('tools.exclusive')
                                        </label>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group position-relative">
                                    <x-input-label class="small text-muted text-uppercase fw-bold mb-2">@lang('tools.amount')</x-input-label>
                                    <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                                        <span class="input-group-text border-0 bg-light text-muted fw-bold">$</span>
                                        <x-text-input class="form-control border-0 bg-light py-3 fw-bold" name="amount" type="number" id="amount"
                                            required step="0.01" :placeholder="__('tools.amount')" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group position-relative">
                                    <x-input-label class="small text-muted text-uppercase fw-bold mb-2">@lang('tools.taxPercentage')</x-input-label>
                                    <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                                        <x-text-input class="form-control border-0 bg-light py-3 fw-bold" name="tax" type="number" id="tax"
                                            required step="0.01" :placeholder="__('tools.taxPercentage')" />
                                        <span class="input-group-text border-0 bg-light text-muted fw-bold">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        <div class="d-flex justify-content-end">
                            <x-button type="submit" class="btn btn-primary btn-lg rounded-pill px-5" id="calculateTax">
                                <i class="bi bi-calculator-fill me-2"></i> @lang('tools.calculateTax')
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>

    <div class="d-none sales-tax-results mt-5">
        <x-page-wrapper :title="__('common.result')">
            <div class="result mt-4">
                <div class="row g-4">
                    <div class="col-md-12">
                        <div class="p-4 shadow-sm border rounded-4 bg-white">
                            <table class="table table-hover align-middle mb-0">
                                <tbody>
                                    <tr>
                                        <th class="ps-0 py-3" width="250">@lang('tools.netAmount')</th>
                                        <td class="py-3">
                                            <div class="h4 fw-bold text-dark mb-0" id="net-amount"></div>
                                        </td>
                                        <td class="text-end pe-0">
                                            <x-copy-target target="net-amount" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0 py-3">@lang('tools.taxRate')</th>
                                        <td class="py-3">
                                            <div class="badge bg-light text-primary border px-3 py-2 fs-6 fw-normal" id="tax-rate"></div>
                                        </td>
                                        <td class="text-end pe-0">
                                            <x-copy-target target="tax-rate" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0 py-3 text-primary h4">@lang('tools.grossAmount')</th>
                                        <td class="py-3">
                                            <div class="display-6 fw-bold text-primary mb-0" id="gross-amount"></div>
                                        </td>
                                        <td class="text-end pe-0">
                                            <x-copy-target target="gross-amount" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </x-page-wrapper>
    </div>
    <x-ad-slot :advertisement="get_advert_model('below-result')" />
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        <script>
            const APP = function() {
                const resultsElem = document.querySelector('.sales-tax-results');
                const calculation = function() {
                        var amount = document.getElementById('amount').value;
                        var tax = document.getElementById('tax').value;

                        if (amount < 0 || amount == null || tax < 0 || tax == null || amount == "" || tax == "") {
                            ArtisanApp.toastError('{{ __('tools.invalidInput') }}')
                            return;
                        }

                        amount = parseFloat(amount);
                        tax = parseFloat(tax);
                        var tax_amount = 0;
                        var net_amount = 0;
                        var gross_amount = 0;

                        if (document.getElementById('inclusive').checked == true) {
                            gross_amount = amount;
                            net_amount = amount * 100 / (100 + tax);
                            tax_amount = parseFloat(gross_amount) - parseFloat(net_amount);
                        }
                        if (document.getElementById('exclusive').checked == true) {
                            net_amount = amount;
                            tax_amount = (tax / 100) * amount;
                            gross_amount = parseFloat(net_amount) + parseFloat(tax_amount);
                        }

                        document.getElementById('gross-amount').innerHTML = gross_amount.round(2);
                        document.getElementById('net-amount').innerHTML = net_amount.round(2);
                        document.getElementById('tax-rate').innerHTML = tax + ' % tax i.e.' + tax_amount.round(2);

                        resultsElem.classList.remove('d-none')
                        resultsElem.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    },
                    attachEvents = function() {
                        document.getElementById('calculateTax').addEventListener('click', () => {
                            calculation()
                        })
                        document.getElementById('frmTax').addEventListener('submit', e => {
                            e.preventDefault();
                            calculation()
                        })
                    };

                return {
                    init: function() {
                        attachEvents();
                    }
                }
            }()
            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endpush
</x-application-tools-wrapper>
