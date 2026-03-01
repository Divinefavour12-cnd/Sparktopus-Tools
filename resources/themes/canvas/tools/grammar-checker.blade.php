<x-canvas-layout>
    @push('page_header')
        <link rel="stylesheet" href="{{ asset('css/ai-humanizer.css') }}">
        <style>
            /* Premium Header & Utilities Sync */
            .humanizer-page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; gap: 12px; max-width: 1100px; margin-left: auto; margin-right: auto; }
            .humanizer-page-header .header-left { display: flex; align-items: center; gap: 12px; }
            .humanizer-page-header .back-link { display: inline-flex; align-items: center; gap: 6px; color: #888; text-decoration: none; font-size: 13px; font-weight: 500; padding: 8px 14px; border-radius: 10px; border: 1px solid #eee; transition: all 0.2s; }
            .humanizer-page-header .back-link:hover { color: #6000C2; background: rgba(96, 0, 194, 0.04); border-color: #6000C2; }
            .humanizer-page-header .tool-title { font-size: 24px; font-weight: 800; color: #1a1a2e; margin: 0; line-height: 1.2; }
            [theme-mode="dark"] .humanizer-page-header .tool-title { color: #fff; }
            .btn-history { display: inline-flex; align-items: center; gap: 8px; background: #fff; border: 1px solid #eee; padding: 10px 18px; border-radius: 12px; font-size: 14px; font-weight: 600; color: #555; cursor: pointer; transition: all 0.2s; }
            [theme-mode="dark"] .btn-history { background: #1a1a2e; border-color: #333; color: #aaa; }
            .btn-history:hover { border-color: #6000C2; color: #6000C2; background: rgba(96,0,194,0.04); }

            /* Grammar Checker Specific Styles */
            .gc-container { max-width: 1100px; margin: 0 auto; padding-bottom: 60px; }
            .gc-editor-card { background: #fff; border-radius: 20px; border: 1px solid #eee; overflow: hidden; height: 100%; display: flex; flex-direction: column; transition: border-color 0.3s; }
            .gc-editor-card:focus-within { border-color: #6000C2; }
            [theme-mode="dark"] .gc-editor-card { background: #1a1a2e; border-color: #333; }
            .gc-editor-header { padding: 12px 20px; border-bottom: 1px solid #f5f5f5; background: #fafafa; display: flex; justify-content: space-between; align-items: center; }
            [theme-mode="dark"] .gc-editor-header { background: #151525; border-color: #333; }
            .gc-textarea { width: 100%; min-height: 400px; border: none; padding: 20px; font-size: 16px; line-height: 1.7; outline: none; background: transparent; color: inherit; resize: none; flex-grow: 1; }
            
            .btn-gc-main { background: #6000C2; color: white; padding: 16px 50px; border: none; border-radius: 50px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; transition: all 0.3s; box-shadow: 0 8px 25px rgba(96, 0, 194, 0.3); font-size: 15px; }
            .btn-gc-main:hover { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(96, 0, 194, 0.4); background: #4B0096; }

            /* Results Styling */
            .result-highlight { background: rgba(96, 0, 194, 0.05); padding: 20px; border-radius: 12px; border-left: 4px solid #6000C2; margin-top: 20px; }
            [theme-mode="dark"] .result-highlight { background: rgba(96, 0, 194, 0.1); }
        </style>
    @endpush

    <div class="gc-container container-fluid py-4 px-lg-5">
        {{-- Premium Header --}}
        <div class="humanizer-page-header mb-5">
            <div class="header-left">
                <a href="{{ route('front.index') }}" class="back-link">
                    <i class="bi bi-arrow-left"></i>
                    <span class="d-none d-md-inline">@lang('Back')</span>
                </a>
                <div class="header-divider d-none d-md-block"></div>
                <div class="title-group">
                    <h1 class="tool-title">{{ $tool->name }}</h1>
                    <div class="tool-description-mini d-none d-lg-block">@lang('Check your text for grammar, spelling, and punctuation errors with AI precision.')</div>
                </div>
            </div>
            <div class="header-right">
                <button class="btn-history" type="button" onclick="window.location.href='{{ route('tool.show', $tool->slug) }}'">
                    <i class="bi bi-arrow-clockwise"></i>
                    <span class="d-none d-md-inline">@lang('Reset Tool')</span>
                </button>
            </div>
        </div>

        {{-- Horizontal Ad After Header --}}
        <div class="mb-4 text-center">
            <x-ad-slot :advertisement="get_advert_model('above-tool')" />
        </div>

        <form action="{{ route('tool.handle', $tool->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- Text Editor --}}
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="gc-editor-card shadow-sm">
                        <div class="gc-editor-header">
                            <span class="fw-bold text-muted small uppercase tracking-wider">@lang('SOURCE TEXT')</span>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="document.getElementById('textarea').value = ''">@lang('Clear')</button>
                                <label for="fileInput" class="btn btn-sm btn-outline-primary rounded-pill px-3 mb-0 cursor-pointer">@lang('Upload File')</label>
                                <input type="file" id="fileInput" name="file" class="d-none" accept=".txt">
                            </div>
                        </div>
                        <textarea name="string" id="textarea" class="gc-textarea" placeholder="Paste your text here (min 10 words)..." required>{{ $results['original_text'] ?? old('string') }}</textarea>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="gc-editor-card shadow-sm {{ !isset($results) ? 'opacity-75' : '' }}">
                        <div class="gc-editor-header">
                            <span class="fw-bold text-muted small uppercase tracking-wider">@lang('CORRECTED VERSION')</span>
                            @if(isset($results))
                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" id="copyBtn">@lang('Copy Result')</button>
                            @endif
                        </div>
                        <div class="gc-textarea bg-light-subtle" id="resultArea" style="overflow-y: auto;">
                            @if(isset($results))
                                <div class="result-highlight">
                                    {!! nl2br(e($results['converted_text'])) !!}
                                </div>
                            @else
                                <div class="h-100 d-flex flex-column align-items-center justify-content-center text-muted text-center p-4">
                                    <i class="bi bi-check-circle display-4 mb-3 opacity-25"></i>
                                    <p>@lang('Corrected text will appear here after checking.')</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <button type="submit" class="btn-gc-main shadow">
                    <i class="bi bi-patch-check me-2"></i> @lang('CHECK GRAMMAR')
                </button>
            </div>
        </form>

        {{-- Horizontal Ad Bottom --}}
        <div class="mt-5 text-center">
            <x-ad-slot :advertisement="get_advert_model('below-result')" />
        </div>

        {{-- Related Tools --}}
        <div class="mt-5 pt-5">
            <x-related-tools :tool="$tool" />
        </div>

        <div class="mt-5 p-4 bg-light rounded-4 border" id="howToUseContent">
            <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>@lang('How to use Grammar Checker')</h5>
            <div class="how-to-use-list text-muted" style="font-size: 14px; line-height: 1.9;">
                {!! strip_tags($tool->content, '<p><a><strong><ul><ol><li>') !!}
            </div>
        </div>
    </div>

    @push('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('textarea');
            const fileInput = document.getElementById('fileInput');
            const copyBtn = document.getElementById('copyBtn');
            const resultArea = document.getElementById('resultArea');

            // File Upload Handling
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        textarea.value = e.target.result;
                    };
                    reader.readAsText(file);
                }
            });

            // Copy Result
            if(copyBtn) {
                copyBtn.addEventListener('click', function() {
                    const text = resultArea.innerText;
                    navigator.clipboard.writeText(text).then(() => {
                        this.innerHTML = '<i class="bi bi-check-lg"></i> Copied!';
                        setTimeout(() => this.innerHTML = 'Copy Result', 2000);
                    });
                });
            }
        });
    </script>
    @endpush
</x-canvas-layout>