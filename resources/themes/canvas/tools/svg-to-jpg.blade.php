<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />

        <x-form method="post" :route="route('tool.handle', $tool->slug)" enctype="multipart/form-data" id="svg-upload-form">
            @csrf

            <div class="row">
                <div class="col-md-12 mt-3 mb-3">
                    <x-upload-wrapper 
                        :max-files="$tool->no_file_tool" 
                        :max-size="$tool->fs_tool" 
                        accept=".svg" 
                        input-name="images[]"
                        :file-title="__('tools.dropSvgHereTitle')" 
                        :file-label="__('tools.convertSvgToJpgDesc')">

                        <x-slot name="svg">
                            <i class="an an-attch-clip"></i>
                        </x-slot>

                    </x-upload-wrapper>

                    <x-input-error :messages="$errors->get('images.*')" class="mt-2" />
                </div>
            </div>

            <x-ad-slot :advertisement="get_advert_model('below-form')" />

            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill" id="convert-btn">
                        @lang('tools.convertToJpg')
                    </x-button>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>

    @if (!empty($results))
        <div class="tool-results-wrapper">
            <x-ad-slot :advertisement="get_advert_model('above-result')" />

            <x-page-wrapper :title="__('common.result')">
                <div class="row">
                    <div class="col-md-12">
                        <div class="progress" style="height: 3px;">
                            <div id="conversion-progress" class="progress-bar bg-success" role="progressbar"
                                 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <table class="table table-style">
                            <thead>
                                <tr>
                                    <th width="75">#</th>
                                    <th width="30%">@lang('common.fileName')</th>
                                    <th width="30%">JPG Output</th>
                                    <th width="100">@lang('common.size')</th>
                                    <th width="175"></th>
                                </tr>
                            </thead>
                            <tbody id="processing-files">
                                <!-- Processed files will appear here -->
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-12 text-end">
                        <x-form class="d-none download-all-btn d-inline-block" method="post" 
                            :route="route('tool.postAction', ['tool' => $tool->slug, 'action' => 'download-all'])">
                            @csrf
                            <input type="hidden" name="process_id" value="{{ $results['process_id'] }}">
                            <x-download-form-button :text="__('tools.downloadAll')" />
                        </x-form>
                        <x-reload-button :link="route('tool.show', ['tool' => $tool->slug])" />
                    </div>
                </div>
            </x-page-wrapper>

            <x-ad-slot :advertisement="get_advert_model('below-result')" />
        </div>
    @endif

    <x-tool-content :tool="$tool" />

    @push('page_styles')
        <style>
            #app-loader, .loading-overlay, .loading, .overlay, .modal-overlay, .swal2-container {
                display: none !important;
                visibility: hidden !important;
                opacity: 0 !important;
            }
        </style>
    @endpush

    @push('page_scripts')
        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Axios -->
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // Remove loaders
                const hideLoaders = () => {
                    document.querySelectorAll('#app-loader, .loading-overlay, .loading, .overlay, .modal-overlay').forEach(el => {
                        el.remove();
                    });
                };
                hideLoaders();

                const observer = new MutationObserver((mutations) => {
                    mutations.forEach(mutation => {
                        mutation.addedNodes.forEach(node => {
                            if (node.nodeType === 1 && node.matches('#app-loader, .loading-overlay, .loading, .overlay, .modal-overlay')) {
                                node.remove();
                            }
                        });
                    });
                });
                observer.observe(document.body, { childList: true, subtree: true });

                const form = document.getElementById('svg-upload-form');
                if (form) {
                    form.addEventListener('submit', (e) => {
                        const fileInput = form.querySelector('input[name="images[]"]');
                        const files = fileInput.files;
                        const validTypes = ['image/svg+xml'];

                        if (!files.length) {
                            e.preventDefault();
                            showAlert('No Files Selected', 'Please select at least one SVG file.', 'warning');
                            return;
                        }

                        for (const file of files) {
                            if (!validTypes.includes(file.type)) {
                                e.preventDefault();
                                showAlert('Invalid File Type', `File ${file.name} is not a valid SVG.`, 'warning');
                                return;
                            }
                        }
                    });
                }

                @if ($errors->any())
                    showAlert('Validation Error', '{!! implode(' ', $errors->all()) !!}', 'error');
                @endif
            });

            const showAlert = (title, message, icon = 'info') => {
                Swal.fire({
                    title, text: message, icon,
                    toast: true,
                    position: 'bottom-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            };

            @if (!empty($results))
                const APP = function () {
                    let processed = 0;
                    const process_id = '{{ $results['process_id'] }}';
                    const files = {!! json_encode($results['files'] ?? []) !!};
                    const max_files = {{ $tool->no_file_tool ?? 20 }};
                    const route = '{{ route('tool.postAction', ['tool' => $tool->slug, 'action' => 'process-file']) }}';

                    const attachEvents = () => {
                        document.querySelectorAll('.download-file-btn').forEach(button => {
                            button.addEventListener('click', (e) => {
                                const url = e.target.dataset.url;
                                const filename = e.target.dataset.filename;
                                const a = document.createElement('a');
                                a.href = url;
                                a.download = filename;
                                document.body.appendChild(a);
                                a.click();
                                document.body.removeChild(a);
                            });
                        });
                    };

                    const startConversion = async (files, cursor = 0) => {
                        if (!files || cursor >= files.length || cursor >= max_files) {
                            if (processed > 0) {
                                showAlert('Done', `Converted ${processed} file(s)`, 'success');
                                document.querySelector('.download-all-btn')?.classList.remove('d-none');
                            } else {
                                showAlert('No Files Processed', '', 'warning');
                            }
                            return;
                        }

                        const file = files[cursor];
                        processingNow(file, cursor);

                        try {
                            const response = await axios.post(route, {
                                process_id,
                                file: file.original_filename
                            });
                            updateProgress(cursor);
                            showDownload(response.data, cursor);
                        } catch (err) {
                            console.error(err);
                            showDownload({ success: false }, cursor);
                            showAlert('Error', `Failed to process ${file.original_filename}`, 'error');
                        }

                        startConversion(files, cursor + 1);
                    };

                    const processingNow = (file, index) => {
                        document.querySelector('#processing-files').innerHTML += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${file.original_filename}</td>
                                <td id="new-name-${index}">Loading...</td>
                                <td id="new-size-${index}">...</td>
                                <td id="file-cursor-${index}"><div class="spinner-border"></div></td>
                            </tr>`;
                    };

                    const updateProgress = (cursor) => {
                        const percent = ((cursor + 1) / files.length) * 100;
                        document.getElementById('conversion-progress').style.width = `${Math.round(percent)}%`;
                    };

                    const showDownload = (data, index) => {
                        const success = data.success;
                        const size = success ? `${(data.size / 1024).toFixed(2)} KB` : '-';
                        const name = success ? data.filename : '-';
                        const button = success ? `
                            <button class="btn btn-outline-primary rounded-pill download-file-btn" type="button"
                                data-url="${data.url}" data-filename="${data.filename}">
                                {{ __('common.download') }}
                            </button>` : '<span class="badge bg-danger">{{ __('common.failed') }}</span>';

                        if (success) processed++;

                        document.getElementById(`new-name-${index}`).innerHTML = name;
                        document.getElementById(`new-size-${index}`).innerHTML = size;
                        document.getElementById(`file-cursor-${index}`).innerHTML = button;
                        attachEvents();
                    };

                    return {
                        init: () => {
                            if (files.length) {
                                startConversion(files);
                            }
                        }
                    };
                }();
                document.addEventListener("DOMContentLoaded", () => APP.init());
            @endif
        </script>
    @endpush
</x-application-tools-wrapper>