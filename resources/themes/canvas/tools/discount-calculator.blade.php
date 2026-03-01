<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-form id="frmDiscount" method="get" :route="route('tool.handle', $tool->slug)" class="no-app-loader">
            <div class="row custom-textarea-wrapper">
                <div class="col-md-12 mb-4">
                    <div class="custom-textarea p-4 shadow-sm border rounded bg-white">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group position-relative">
                                    <x-input-label class="small text-muted text-uppercase fw-bold mb-2">@lang('tools.amount')</x-input-label>
                                    <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                                        <span class="input-group-text border-0 bg-light text-muted fw-bold">$</span>
                                        <x-text-input class="form-control border-0 bg-light py-3 fw-bold" name="amount" type="number" step="0.01" id="amount" required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group position-relative">
                                    <x-input-label class="small text-muted text-uppercase fw-bold mb-2">@lang('tools.discountPercentage')</x-input-label>
                                    <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                                        <x-text-input class="form-control border-0 bg-light py-3 fw-bold" name="discount" type="number" step="0.01" id="discount" required />
                                        <span class="input-group-text border-0 bg-light text-muted fw-bold">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="d-flex justify-content-end">
                            <x-button type="submit" id="calculateDiscount" class="btn btn-primary btn-lg rounded-pill px-5">
                                <i class="bi bi-tag-fill me-2"></i> @lang('tools.calculateDiscount')
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>

    <div class="tool-result-wrapper d-none mt-5">
        <x-page-wrapper :title="__('common.result')">
            <div class="result mt-4">
                <div class="row g-4">
                    <div class="col-md-12">
                        <div class="p-4 shadow-sm border rounded-4 bg-white">
                            <table class="table table-hover align-middle mb-0">
                                <tbody>
                                    <tr>
                                        <th class="ps-0 py-3" width="250">@lang('tools.discountedPrice')</th>
                                        <td class="py-3">
                                            <div class="h4 fw-bold text-success mb-0" id="discounted-price"></div>
                                        </td>
                                        <td class="text-end pe-0">
                                            <x-copy-target target="discounted-price" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0 py-3">@lang('tools.savings')</th>
                                        <td class="py-3">
                                            <div class="badge bg-light text-success border px-3 py-2 fs-6 fw-normal" id="saving-price"></div>
                                        </td>
                                        <td class="text-end pe-0">
                                            <x-copy-target target="saving-price" />
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
                const resultsElem = document.querySelector('.tool-result-wrapper');
                const calculation = function() {
                        var amount = document.getElementById('amount').value;
                        var discount = document.getElementById('discount').value;

                        if (amount < 0 || discount < 0 || amount == "" || discount == "") {
                            ArtisanApp.toastError('{{ __('tools.invalidInput') }}')
                            return;
                        }

                        amount = parseFloat(amount);
                        discount = parseFloat(discount);
                        var discounted_price = 0;
                        var saving_price = 0;

                        saving_price = (discount * amount) / 100;
                        discounted_price = amount - saving_price;

                        document.getElementById('discounted-price').innerHTML = discounted_price.round(2);
                        document.getElementById('saving-price').innerHTML = saving_price.round(2);

                        resultsElem.classList.remove('d-none')
                        resultsElem.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                        ArtisanApp.hideLoader();
                    },
                    attachEvents = function() {
                        document.getElementById('calculateDiscount').addEventListener('click', () => {
                            calculation()
                        })
                        document.getElementById('frmDiscount').addEventListener('submit', e => {
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
