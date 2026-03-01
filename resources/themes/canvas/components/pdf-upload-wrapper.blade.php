@props([
    'accept' => '.pdf',
    'maxSize' => null,
    'maxFiles' => 1,
    'fileTitle' => __('tools.dropPDFHereTitle'),
    'fileLabel' => __('admin.selectFileOrDrag'),
    'buttonLabel' => __('admin.selectAFile'),
    'id' => 'file-uploader',
    'dropOnBody' => true,
    'inputName' => 'files',
    'onSelectFile' => null,
    'pages' => 'false',
    'preview' => 'true',
    'rotate' => 'true',
    'sortable' => 'false',
    'callbacks' => '{}',
    'equalHeight' => false,
    'allowProtectedFiles' => 'true',
])
<div class="artisan-uploader bg-white border border-dashed rounded-4 p-5 text-center position-relative transition-all hover-bg-light uploader-{{ $id }}{{ !$equalHeight ? '' : ' h-100' }}">
    <input id="{{ $id }}" type="file" name="{{ $inputName }}"
        accept="{{ $accept }}"{{ $maxFiles > 1 ? ' multiple' : '' }} class="d-none" />
    <input type="hidden" class="pdf_file__data" name="fileData">
    
    <div class="bg-primary p-2 add-more d-none position-absolute top-0 end-0 m-3 shadow-sm rounded-circle">
        <div class="d-flex justify-content-between align-items-center">
            <label class="btn btn-primary btn-pill p-0 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;" for="{{ $id }}"><i class="bi bi-plus"></i></label>
            <div class="ml-auto pdf___more-actions"></div>
        </div>
    </div>

    <label for="{{ $id }}" class="position-relative file-drag cursor-pointer w-100 h-100 d-block m-0">
        <div class="file-loader position-absolute top-50 start-50 translate-middle d-none" style="z-index: 10;">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">@lang('common.loading')</span>
            </div>
        </div>
        
        <div class="uploader-wrapper py-4">
            <div class="uploader-icon mb-4">
                @if (!empty($svg))
                    {{ $svg }}
                @else
                    <i class="bi bi-file-earmark-pdf display-2 text-danger"></i>
                @endif
            </div>

            @if ($fileTitle)
                <h3 class="fw-bold mb-3">{{ $fileTitle }}</h3>
            @endif

            @if (!empty($accept))
                <div class="uploader-extensions mb-4 d-flex flex-wrap justify-content-center gap-2">
                    @foreach (explode(',', $accept) as $ext)
                        <span class="badge bg-light text-dark border rounded-pill px-3 py-2 text-uppercase fw-normal small">{{ trim($ext, '.') }}</span>
                    @endforeach
                </div>
            @endif

            <p class="text-muted mb-4 fs-5">{{ $fileLabel }}</p>
            <span class="btn btn-primary btn-lg rounded-pill px-5 py-3 shadow-sm hover-elevate transition-all">{{ $buttonLabel }}</span>
        </div>
        <div class="files-grid mt-4"></div>
    </label>

    @if (!$slot->isEmpty())
        <div class="uploader-after p-4 mt-4 border-top">
            {{ $slot }}
        </div>
    @endif
</div>
@push('page_scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            PDFApp.initPDFUpload(document.querySelector('.uploader-{{ $id }}'), {
                    dropOnBody: {{ $dropOnBody ? 'true' : 'false' }},
                    maxFiles: {{ $maxFiles }},
                    previewPages: {{ $pages }},
                    allowPreview: {{ $preview }},
                    allowRotate: {{ $rotate }},
                    isSortable: {{ $sortable }},
                    allowProtectedFiles: {{ $allowProtectedFiles }},
                    fileExtensions: "{{ Str::replace(',', '|', $accept) }}",
                    @if (!empty($maxSize))
                        maxSize: {{ $maxSize }},
                    @endif
                }, {
                    extensionsError: "{{ __('admin.fileTypeNotSupported') }}",
                    sizeError: "{{ __('admin.maxFileSizeError', ['size' => $maxSize]) }}",
                    filesError: "{{ __('admin.maxFileLimitError') }}",
                    tooManyInvalidAttempts: "{{ __('tools.tooManyInvalidAttempts') }}",
                    fileNotSupported: "{{ __('tools.fileNotSupported') }}",
                },
                @if ($callbacks)
                    {{ $callbacks }}
                @endif
            );
        });
    </script>
@endpush
