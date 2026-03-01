@props([
    'tool' => null,
    'title' => null,
    'label' => null,
    'placeholder' => __('tools.pasteTextHere'),
    'nl2br' => true,
    'results' => null,
    'mode' => 'code',
    'mode2' => 'code',
])
<x-tool-wrapper :tool="$tool">
    <x-form method="post" :route="route('tool.handle', $tool->slug)" enctype="multipart/form-data">
        <div class="row custom-textarea-wrapper">
            <div class="col-md-12 mb-4">
                <div class="custom-textarea p-4 shadow-sm border rounded bg-white">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <div class="input-actions-group d-flex align-items-center gap-2 flex-grow-1">
                            <div class="input-group input-group-sm" style="max-width: 300px;">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-link-45deg"></i></span>
                                <x-text-input type="url" name="url" id="urlUpload" class="form-control-sm border-start-0" :placeholder="__('tools.loadFromUrl')" />
                            </div>
                            <x-input-file-button file-id="fileInput" accept=".json" class="btn-sm" />
                        </div>
                        <x-input-label class="h5 fw-bold mb-0 ms-3">{{ $title }}</x-input-label>
                    </div>

                    <div class="form-group mb-3">
                        <div class="mh-400 rounded border" id="jsoneditor" style="height: 400px;"></div>
                    </div>
                    
                    <x-input-error :messages="$errors->get('json')" class="mt-2" />
                    
                    <hr class="my-3">
                    <div class="d-flex justify-content-end align-items-center">
                        <div class="tool-actions">
                            {{ $slot }}
                            <x-button type="button" class="btn btn-primary btn-lg rounded-pill px-5" id="json-tool-action">
                                {{ $label }}
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-form>
</x-tool-wrapper>

<div class="json-tool-result d-none mt-5">
    <x-page-wrapper :title="__('common.result')">
        <div class="result mt-4">
            <x-ad-slot :advertisement="get_advert_model('above-result')" />
            <div class="row">
                <div class="col-md-12">
                    <div id="json--results-container"></div>
                    <div class="json--results-actions result-copy mt-3 text-end">
                        <x-button class="btn btn-primary rounded-pill" type="button" id="saveToFile">
                            @lang('tools.saveAsTxt')
                        </x-button>
                        <x-copy-callback callback="copyJsonResults" :text="__('common.copyToClipboard')" />
                    </div>
                </div>
            </div>
        </div>
    </x-page-wrapper>
</div>
<x-ad-slot :advertisement="get_advert_model('below-result')" />
<x-tool-content :tool="$tool" />
@push('page_header')
    @vite(['resources/themes/canvas/assets/sass/jsoneditor.scss', 'resources/themes/canvas/assets/js/jsoneditor.js'])
@endpush
@push('page_scripts')
    <script>
        const JsonApp = function() {
            let editorInstance = null,
                editor = null;
            const jsonEditor = function() {
                    editorInstance = new JSONEditor(document.getElementById("jsoneditor"), {
                        mode: '{{ $mode }}',
                        onError: function(err) {
                            ArtisanApp.toastError(err.toString());
                        },
                    })
                },
                attachEvents = function() {
                    document.getElementById('fileInput').addEventListener('change', e => {
                        var file = document.getElementById("fileInput").files[0];
                        if (file.type != "application/json") {
                            ArtisanApp.toastError("{{ __('common.invalidFile') }}");
                            return;
                        }
                        JsonApp.setFileContent(file);
                    });
                    document.getElementById('urlUpload').addEventListener('input', e => {
                        urlContent(e.target);
                    })
                    @if ($mode2 !== false)
                        editor = new JSONEditor(document.getElementById("json--results-container"), {
                            mode: '{{ $mode2 }}',
                            modes: ['code', 'form', 'text', 'tree', 'view'],
                            onError: function(err) {
                                ArtisanApp.toastError(err.toString());
                            },
                        })
                        editor.frame.querySelector('.jsoneditor-outer').classList.add('mh-400')
                    @else
                        editor = editorInstance;
                    @endif
                    document.getElementById('json-tool-action').addEventListener('click', () => {
                        APP.updateContent()
                    });
                    document.getElementById('saveToFile').addEventListener('click', () => {
                        ArtisanApp.downloadAsTxt(editor.getText(), {
                            isElement: false,
                            filename: '{{ $tool->slug . '.txt' }}'
                        })
                    })
                },
                urlContent = function(element) {
                    var url = element.value;
                    var RegExp =
                        /^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/i;
                    if (RegExp.test(url)) {
                        fetch(url)
                            .then(res => res.json())
                            .then(out => {
                                editorInstance.set(out)
                            })
                            .catch(err => {
                                throw err
                            });
                    }
                };

            return {
                init: function() {
                    jsonEditor()
                    attachEvents()
                },
                initResult: function() {
                    document.querySelector('.json-tool-result').classList.remove('d-none')
                    document.querySelector(".json-tool-result").scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                },
                setFileContent: function(file) {
                    var reader = new FileReader();
                    reader.readAsText(file, "UTF-8");
                    reader.onload = function(evt) {
                        editorInstance.setText(evt.target.result);
                    }
                    reader.onerror = function(evt) {
                        ArtisanApp.toastError("error reading file");
                    }
                },
                validateEditor: async function() {
                    let state = false
                    await editorInstance.validate()
                        .then(res => {
                            if (res.length != 0) {
                                res.forEach(r => {
                                    ArtisanApp.toastError(r.message)
                                });
                            } else {
                                state = true
                            }
                        })

                    return state
                },
                editor: function() {
                    return editorInstance
                },
                editor2: function() {
                    return editor
                },
                getData: function() {
                    return editor.getText();
                }
            }
        }();
        document.addEventListener("DOMContentLoaded", function(event) {
            JsonApp.init();
            window.copyJsonResults = JsonApp.getData
        });
    </script>
@endpush
