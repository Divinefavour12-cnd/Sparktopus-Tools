    <x-tool-wrapper :tool="$tool">
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="row custom-textarea-wrapper">
                <div class="col-md-12 mb-4">
                    <div class="custom-textarea p-4 shadow-sm border rounded bg-white">
                        <x-input-label class="h4 fw-bold mb-3">@lang('tools.pasteTextToCount')</x-input-label>
                        <div class="form-group relative mb-3">
                            <x-textarea-input type="text" name="string" class="form-control border-0 shadow-none fs-5" rows="10"
                                :placeholder="__('common.someText')" id="textarea" required autofocus style="resize: none;">
                                {{ $results['string'] ?? old('string') }}
                            </x-textarea-input>
                        </div>
                        
                        <x-input-error :messages="$errors->get('string')" class="mt-2" />
                        
                        <hr class="my-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <x-input-file-button />
                            <div class="tool-actions">
                                <x-button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">
                                    @lang('tools.countWords')
                                </x-button>
                            </div>
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
                    <div class="row g-4 mb-5">
                        <div class="col-md-3 col-sm-6">
                            <div class="p-4 shadow-sm border rounded-4 text-center bg-white hover-elevate transition-all">
                                <div class="display-5 fw-bold text-success mb-2">{{ $results['words'] }}</div>
                                <div class="text-muted fw-bold text-uppercase small">@lang('common.words')</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="p-4 shadow-sm border rounded-4 text-center bg-white hover-elevate transition-all">
                                <div class="display-5 fw-bold text-primary mb-2">{{ $results['characters'] }}</div>
                                <div class="text-muted fw-bold text-uppercase small">@lang('common.characters')</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="p-4 shadow-sm border rounded-4 text-center bg-white hover-elevate transition-all">
                                <div class="display-5 fw-bold text-danger mb-2">{{ $results['syllables'] }}</div>
                                <div class="text-muted fw-bold text-uppercase small">@lang('common.syllables')</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="p-4 shadow-sm border rounded-4 text-center bg-white hover-elevate transition-all">
                                <div class="display-5 fw-bold text-info mb-2">{{ $results['sentences'] }}</div>
                                <div class="text-muted fw-bold text-uppercase small">@lang('common.sentences')</div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-4 shadow-sm border rounded-4 bg-white h-100">
                                <div class="mb-4">
                                    <h3 class="fw-bold mb-4 border-bottom pb-2">@lang('common.basicWordsCount')</h3>
                                    <table class="table table-borderless">
                                        <tr class="align-middle">
                                            <td class="ps-0">@lang('common.totalWords')</td>
                                            <td class="text-end pe-0">
                                                <span class="badge bg-success-subtle text-success border border-success rounded-pill px-3 py-2 fw-bold">
                                                    {{ $results['words'] }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="align-middle">
                                            <td class="ps-0">@lang('common.totalCharactersWS')</td>
                                            <td class="text-end pe-0">
                                                <span class="badge bg-primary-subtle text-primary border border-primary rounded-pill px-3 py-2 fw-bold">
                                                    {{ $results['characters'] }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="align-middle">
                                            <td class="ps-0">@lang('common.totalCharactersWOS')</td>
                                            <td class="text-end pe-0">
                                                <span class="badge bg-info-subtle text-info border border-info rounded-pill px-3 py-2 fw-bold">
                                                    {{ $results['characters_wos'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="mt-5">
                                    <h3 class="fw-bold mb-4 border-bottom pb-2">@lang('common.readingTime')</h3>
                                    <table class="table table-borderless">
                                        <tr class="align-middle">
                                            <td class="ps-0">@lang('common.estimatedReadingTime')</td>
                                            <td class="text-end pe-0">
                                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning rounded-pill px-3 py-2 fw-bold">
                                                    <i class="bi bi-eye me-1"></i> {{ $results['read_time'] }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="align-middle">
                                            <td class="ps-0">@lang('common.estimatedSpeakingTime')</td>
                                            <td class="text-end pe-0">
                                                <span class="badge bg-danger-subtle text-danger border border-danger rounded-pill px-3 py-2 fw-bold">
                                                    <i class="bi bi-mic me-1"></i> {{ $results['speaking_time'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 shadow-sm border rounded-4 bg-white h-100">
                                <h3 class="fw-bold mb-4 border-bottom pb-2">@lang('common.topWordsDensity')</h3>
                                <div class="tabs-wrapper mt-3">
                                    <ul class="nav nav-pills gap-2" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active rounded-pill px-4" id="oneWord-tab" data-bs-toggle="tab"
                                                data-bs-target="#oneWord" type="button" role="tab"
                                                aria-controls="oneWord" aria-selected="true">
                                                1 Word
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link rounded-pill px-4" id="twoWord-tab" data-bs-toggle="tab"
                                                data-bs-target="#twoWord" type="button" role="tab"
                                                aria-controls="twoWord" aria-selected="true">
                                                2 Words
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link rounded-pill px-4" id="threeWord-tab" data-bs-toggle="tab"
                                                data-bs-target="#threeWord" type="button" role="tab"
                                                aria-controls="threeWord" aria-selected="true">
                                                3 Words
                                            </button>
                                        </li>
                                    </ul>
                                    <div class="tab-content mt-4" id="myTabContent" style="max-height:300px; overflow-y:auto; scrollbar-width: thin;">
                                        <div class="tab-pane fade show active" id="oneWord" role="tabpanel" aria-labelledby="oneWord-tab">
                                            <table class="table table-hover align-middle">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="ps-3">Keyword</th>
                                                        <th class="text-end pe-3">Frequency</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($results['one'] as $word)
                                                        <tr>
                                                            <td class="ps-3 fw-bold">{{ $word['keyword'] }}</td>
                                                            <td class="text-end pe-3">
                                                                <span class="text-muted">{{ $word['frequency'] }}</span>
                                                                <span class="badge bg-light text-dark border ms-2">{{ $word['percentage'] }}%</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="twoWord" role="tabpanel" aria-labelledby="twoWord-tab">
                                            <table class="table table-hover align-middle">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="ps-3">Keyword</th>
                                                        <th class="text-end pe-3">Frequency</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($results['two'] as $word)
                                                        <tr>
                                                            <td class="ps-3 fw-bold">{{ $word['keyword'] }}</td>
                                                            <td class="text-end pe-3">
                                                                <span class="text-muted">{{ $word['frequency'] }}</span>
                                                                <span class="badge bg-light text-dark border ms-2">{{ $word['percentage'] }}%</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="threeWord" role="tabpanel" aria-labelledby="threeWord-tab">
                                            <table class="table table-hover align-middle">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="ps-3">Keyword</th>
                                                        <th class="text-end pe-3">Frequency</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($results['three'] as $word)
                                                        <tr>
                                                            <td class="ps-3 fw-bold">{{ $word['keyword'] }}</td>
                                                            <td class="text-end pe-3">
                                                                <span class="text-muted">{{ $word['frequency'] }}</span>
                                                                <span class="badge bg-light text-dark border ms-2">{{ $word['percentage'] }}%</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-4">
                            <div class="p-4 shadow-sm border rounded-4 bg-white">
                                <h3 class="fw-bold mb-3 border-bottom pb-2">@lang('common.longestSentence')</h3>
                                <div class="bg-light p-3 rounded border">
                                    <p class="mb-0 fs-5 lh-base italic">"{{ $results['paragraph']['string'] }}"</p>
                                    <div class="mt-2 text-muted small">
                                        <i class="bi bi-info-circle me-1"></i> @lang('common.longestSentenceStats', $results['paragraph'])
                                    </div>
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
                let editorInstance = document.getElementById('textarea');
                const attachEvents = function() {
                    document.getElementById('file').addEventListener('change', e => {
                        var file = document.getElementById("file").files[0];
                        if (file.type != "text/plain") {
                            ArtisanApp.toastError("{{ __('common.invalidFile') }}");
                            return;
                        }
                        APP.setFileContent(file);
                    });
                };

                return {
                    init: function() {
                        attachEvents()
                    },
                    setFileContent: function(file) {
                        var reader = new FileReader();
                        reader.readAsText(file, "UTF-8");
                        reader.onload = function(evt) {
                            editorInstance.value = evt.target.result;
                        }
                        reader.onerror = function(evt) {
                            ArtisanApp.toastError("error reading file");
                        }
                    },
                }
            }()
            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endpush
</x-application-tools-wrapper>
