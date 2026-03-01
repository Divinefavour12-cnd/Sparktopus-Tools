@props([
    'accept' => '.jpg,.jpeg,.gif,.png',
    'maxSize' => null,
    'maxFiles' => 1,
    'fileTitle' => null,
    'fileLabel' => __('admin.selectFileOrDrag'),
    'buttonLabel' => __('admin.selectAFile'),
    'id' => 'file-uploader',
    'dropOnBody' => true,
    'inputName' => 'files',
    'onSelectFile' => null,
])
<div class="artisan-uploader bg-white border border-dashed rounded-4 p-5 text-center position-relative transition-all hover-bg-light uploader-{{ $id }}">
    <input id="{{ $id }}" type="file" name="{{ $inputName }}"
        accept="{{ $accept }}"{{ $maxFiles > 1 ? ' multiple' : '' }} class="d-none" />
    
    <div class="bg-primary p-2 add-more d-none position-absolute top-0 end-0 m-3 shadow-sm rounded-circle">
        <label class="btn btn-primary btn-pill p-0 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;" for="{{ $id }}"><i class="bi bi-plus"></i></label>
    </div>

    <label for="{{ $id }}" class="file-drag cursor-pointer w-100 h-100 d-block m-0">
        <div class="uploader-wrapper py-4">
            <div class="uploader-icon mb-4">
                @if (!empty($svg))
                    {{ $svg }}
                @else
                    <i class="bi bi-cloud-arrow-up display-2 text-primary"></i>
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
            ArtisanApp.initUpload(document.querySelector('.uploader-{{ $id }}'), {
                dropOnBody: {{ $dropOnBody ? 'true' : 'false' }},
                maxFiles: {{ $maxFiles }},
                fileExtensions: "{{ Str::replace(',', '|', $accept) }}",
                @if (!empty($maxSize))
                    maxSize: {{ $maxSize }},
                @endif
                @if (!empty($onSelectFile))
                    fileSelectedCallback: '{{ $onSelectFile }}',
                @endif
            }, {
                extensionsError: "{{ __('admin.fileTypeNotSupported') }}",
                sizeError: "{{ __('admin.maxFileSizeError', ['size' => $maxSize]) }}",
                filesError: "{{ __('admin.maxFileLimitError') }}",
            });
        });
    </script>
@endpush
