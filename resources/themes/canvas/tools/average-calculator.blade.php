<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="row custom-textarea-wrapper">
                <div class="col-md-12 mb-4">
                    <div class="custom-textarea p-4 shadow-sm border rounded bg-white text-center">
                        <div class="row g-3" id="extra_fields">
                            @if (isset($results))
                                @foreach ($results['numbers'] as $num)
                                    <div class="col-md-6" id="remove_div_{{ $loop->iteration }}">
                                        <div class="input-group mb-3 shadow-sm rounded-3 overflow-hidden">
                                            <span class="input-group-text border-0 bg-light text-muted">
                                                <i class="bi bi-hash"></i>
                                            </span>
                                            <input class="form-control border-0 bg-light py-2" name="number[]" type="number" step="0.01"
                                                required placeholder="@lang('tools.number'):" value="{{ $num }}" />
                                            <button class="btn btn-light border-0 text-danger" type="button" onclick="APP.removeField({{ $loop->iteration }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                @for ($i = 1; $i <= 4; $i++)
                                    <div class="col-md-6" id="remove_div_{{ $i }}">
                                        <div class="input-group mb-3 shadow-sm rounded-3 overflow-hidden">
                                            <span class="input-group-text border-0 bg-light text-muted">
                                                <i class="bi bi-hash"></i>
                                            </span>
                                            <input class="form-control border-0 bg-light py-2" name="number[]" type="number" step="0.01"
                                                required placeholder="@lang('tools.number'):" />
                                            <button class="btn btn-light border-0 text-danger" type="button" onclick="APP.removeField({{ $i }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endfor
                            @endif
                        </div>
                        
                        <div class="mt-4">
                            <x-button type="button" class="btn btn-outline-secondary rounded-pill px-4" id="add_more_fields">
                                <i class="bi bi-plus-circle me-2"></i> @lang('common.addMore')
                            </x-button>
                        </div>

                        <hr class="my-4">
                        <div class="d-flex justify-content-end">
                            <x-button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">
                                <i class="bi bi-gear-fill me-2"></i> @lang('common.generate')
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
                <div class="result mt-4">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="p-4 shadow-sm border rounded-4 bg-white">
                                <table class="table table-hover align-middle mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="ps-0 py-3" width="250">@lang('tools.count')</th>
                                            <td class="py-3">
                                                <div class="badge bg-light text-dark border px-3 py-2 fs-6 fw-normal">{{ $results['count'] }}</div>
                                            </td>
                                            <td class="text-end pe-0">
                                                <x-copy-text :text="$results['count']" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 py-3 text-primary h5">@lang('tools.average')</th>
                                            <td class="py-3">
                                                <div class="h4 fw-bold text-primary mb-0">{{ $results['average'] }}</div>
                                            </td>
                                            <td class="text-end pe-0">
                                                <x-copy-text :text="$results['average']" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 py-3">@lang('tools.geomatricSum')</th>
                                            <td class="py-3">
                                                <div class="text-muted">{{ $results['geomatric_sum'] }}</div>
                                            </td>
                                            <td class="text-end pe-0">
                                                <x-copy-text :text="$results['geomatric_sum']" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 py-3">@lang('tools.harmonicMean')</th>
                                            <td class="py-3">
                                                <div class="text-muted">{{ $results['harmonic_mean'] }}</div>
                                            </td>
                                            <td class="text-end pe-0">
                                                <x-copy-text :text="$results['harmonic_mean']" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 py-3">@lang('tools.median')</th>
                                            <td class="py-3">
                                                <div class="text-muted">{{ $results['median'] }}</div>
                                            </td>
                                            <td class="text-end pe-0">
                                                <x-copy-text :text="$results['median']" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 py-3 text-success">@lang('tools.largest')</th>
                                            <td class="py-3">
                                                <div class="fw-bold text-success">{{ $results['largest'] }}</div>
                                            </td>
                                            <td class="text-end pe-0">
                                                <x-copy-text :text="$results['largest']" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 py-3 text-danger">@lang('tools.smallest')</th>
                                            <td class="py-3">
                                                <div class="fw-bold text-danger">{{ $results['smallest'] }}</div>
                                            </td>
                                            <td class="text-end pe-0">
                                                <x-copy-text :text="$results['smallest']" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 py-3">@lang('tools.range')</th>
                                            <td class="py-3">
                                                <div class="text-muted">{{ $results['range'] }}</div>
                                            </td>
                                            <td class="text-end pe-0">
                                                <x-copy-text :text="$results['range']" />
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
    @endif
    <x-ad-slot :advertisement="get_advert_model('below-result')" />
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        <script>
            const APP = function() {
                const maxField = 50;
                const addButton = document.getElementById('add_more_fields');
                var cursor = {{ isset($results['numbers']) ? count($results['numbers']) + 1 : 5 }};
                const attachEvents = function() {
                        addButton.addEventListener("click", () => {
                            addField()
                        });
                    },
                    addField = function() {
                        if (cursor > maxField) return;
                        var wrapper = document.getElementById('extra_fields');
                        var fieldElement = `<div class="input-icon mb-3">
                                            <span class="icon"><i class="an an-long-arrow-up text-muted"></i></span>
                                            <input class="form-control" name="number[]" type="number" step="0.01" placeholder="#:" required />
                                            <i class="an an-times-circle text-danger remove-field-icon" onclick="APP.removeField(${cursor})" class="remove-field-icon"></i>
                                        </div>`;
                        var tempNode = document.createElement('div');
                        tempNode.className = 'col-md-6'
                        tempNode.id = `remove_div_${cursor}`
                        tempNode.innerHTML = fieldElement;
                        wrapper.appendChild(tempNode)
                        tempNode.querySelector('input').focus()
                        if (cursor == maxField) addButton.classList.add('d-none');
                        cursor++;
                    };

                return {
                    init: function() {
                        attachEvents();
                    },
                    removeField: function(id) {
                        if (3 < cursor) {
                            document.getElementById("remove_div_" + id).remove();
                            cursor--;
                        }
                    }
                };

            }();
            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endpush
</x-application-tools-wrapper>
