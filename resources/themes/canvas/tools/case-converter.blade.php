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

            /* Case Converter Specific Styles */
            .cc-container { max-width: 1100px; margin: 0 auto; padding-bottom: 60px; }
            
            /* CASE TYPE PILLS */
            .cc-mode-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 12px; margin-top: 20px; }
            .cc-pill { padding: 12px 14px; background: #fff; border: 1.5px solid #eee; border-radius: 14px; cursor: pointer; font-size: 13px; font-weight: 700; text-align: center; transition: all 0.2s; color: #555; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
            .cc-pill:hover { border-color: #6000C2; background: rgba(96, 0, 194, 0.04); color: #6000C2; transform: translateY(-2px); }
            .cc-pill.active { background: #6000C2; color: white; border-color: #6000C2; box-shadow: 0 5px 15px rgba(96, 0, 194, 0.2); }
            [theme-mode="dark"] .cc-pill { background: #1a1a2e; border-color: #333; color: #aaa; }
            [theme-mode="dark"] .cc-pill.active { background: #6000C2; color: #fff; }

            /* TEXTAREA WRAPPERS */
            .cc-editor-card { background: #fff; border-radius: 20px; border: 1px solid #eee; overflow: hidden; height: 100%; display: flex; flex-direction: column; transition: border-color 0.3s; }
            .cc-editor-card:focus-within { border-color: #6000C2; }
            [theme-mode="dark"] .cc-editor-card { background: #1a1a2e; border-color: #333; }
            .cc-editor-header { padding: 12px 20px; border-bottom: 1px solid #f5f5f5; background: #fafafa; display: flex; justify-content: space-between; align-items: center; }
            [theme-mode="dark"] .cc-editor-header { background: #151525; border-color: #333; }
            .cc-textarea { width: 100%; min-height: 350px; border: none; padding: 20px; font-size: 16px; line-height: 1.7; outline: none; background: transparent; color: inherit; resize: none; flex-grow: 1; }
            
            /* CONVERT BUTTON */
            .cc-btn-main { background: #6000C2; color: white; padding: 16px 50px; border: none; border-radius: 50px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; transition: all 0.3s; box-shadow: 0 8px 25px rgba(96, 0, 194, 0.3); font-size: 15px; }
            .cc-btn-main:hover { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(96, 0, 194, 0.4); background: #4B0096; }

            /* QUICK ACTIONS TOOLBAR */
            .quick-actions { display: flex; gap: 10px; margin-bottom: 15px; }
            .action-btn-mini { padding: 6px 12px; border-radius: 8px; border: 1px solid #eee; background: #fff; font-size: 12px; font-weight: 600; color: #666; transition: all 0.2s; }
            .action-btn-mini:hover { border-color: #6000C2; color: #6000C2; background: rgba(96,0,194,0.04); }
            [theme-mode="dark"] .action-btn-mini { background: #1a1a2e; border-color: #333; color: #aaa; }

            /* How it works */
            .how-it-works-steps { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 25px; margin-top: 40px; }
            .step-card { background: #fff; padding: 30px; border-radius: 18px; border: 1px solid rgba(0,0,0,0.04); transition: all 0.3s ease; position: relative; text-align: center; }
            [theme-mode="dark"] .step-card { background: #1a1a2e; border-color: rgba(255,255,255,0.05); }
            .step-card:hover { transform: translateY(-8px); box-shadow: 0 15px 40px rgba(0,0,0,0.06); border-color: rgba(96, 0, 194, 0.1); }
            .step-number { position: absolute; top: -15px; left: 50%; transform: translateX(-50%); width: 35px; height: 35px; background: #6000C2; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; box-shadow: 0 5px 15px rgba(96, 0, 194, 0.3); }
            .step-icon { font-size: 40px; color: #6000C2; margin-bottom: 20px; display: block; }
            .step-title { font-size: 18px; font-weight: 700; margin-bottom: 12px; color: #333; }
            [theme-mode="dark"] .step-title { color: #eee; }
            .step-desc { font-size: 14px; color: #777; line-height: 1.6; margin: 0; }
            [theme-mode="dark"] .step-desc { color: #999; }
        </style>
    @endpush

    @php
        $caseTypes = [
            ['id' => '2', 'label' => 'Sentence case', 'icon' => 'bi-type-strikethrough'],
            ['id' => '3', 'label' => 'lower case', 'icon' => 'bi-type-lowercase'],
            ['id' => '4', 'label' => 'UPPER CASE', 'icon' => 'bi-type-uppercase'],
            ['id' => '5', 'label' => 'Capitalize Word', 'icon' => 'bi-type-h1'],
            ['id' => '1', 'label' => 'tOGGLE cASE', 'icon' => 'bi-arrow-left-right'],
            ['id' => '6', 'label' => 'camelCase', 'icon' => 'bi-braces'],
            ['id' => '7', 'label' => 'snake_case', 'icon' => 'bi-dash-lg'],
            ['id' => '8', 'label' => 'kebab-case', 'icon' => 'bi-reception-0'],
            ['id' => '9', 'label' => 'PascalCase', 'icon' => 'bi-code-slash'],
            ['id' => '10', 'label' => 'CONSTANT_CASE', 'icon' => 'bi-exclamation-triangle'],
            ['id' => '11', 'label' => 'aLtErNaTiNg', 'icon' => 'bi-reception-4']
        ];
    @endphp

    <div class="cc-container container-fluid py-4 px-lg-5">
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
                    <div class="tool-description-mini d-none d-lg-block">@lang('Effortlessly convert text between 11 different case formats.')</div>
                </div>
            </div>
            <div class="header-right">
                <button class="btn-history" type="button" onclick="window.location.href='{{ route('tool.show', $tool->slug) }}'">
                    <i class="bi bi-arrow-clockwise"></i>
                    <span class="d-none d-md-inline">@lang('Clear All')</span>
                </button>
            </div>
        </div>

        {{-- Horizontal Ad after tool header --}}
        <div class="mb-5 text-center">
            <x-ad-slot :advertisement="get_advert_model('above-tool')" />
        </div>

        <form action="{{ route('tool.handle', $tool->slug) }}" method="POST" id="ccForm">
            @csrf
            {{-- Case Type Selection --}}
            <div class="mb-5">
                <h5 class="fw-bold mb-3 d-flex align-items-center">
                    <span class="badge bg-primary rounded-circle me-2" style="width:24px; height:24px; display:inline-flex; align-items:center; justify-content:center; font-size:12px">1</span>
                    @lang('Select Transformation Type')
                </h5>
                <input type="hidden" name="type" id="caseTypeInput" value="{{ $type ?? '2' }}">
                <div class="cc-mode-grid">
                    @foreach($caseTypes as $ct)
                        <div class="cc-pill {{ ($type ?? '2') == $ct['id'] ? 'active' : '' }} case-pill" 
                             data-value="{{ $ct['id'] }}">
                            <i class="bi {{ $ct['icon'] }}"></i> {{ $ct['label'] }}
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Text Editors --}}
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-6">
                    <div class="cc-editor-card shadow-sm">
                        <div class="cc-editor-header">
                            <span class="fw-bold text-muted small uppercase tracking-wider">@lang('INPUT TEXT')</span>
                            <div class="quick-actions mb-0">
                                <button type="button" class="action-btn-mini" onclick="document.getElementById('textarea').value = ''">@lang('Clear')</button>
                                <button type="button" class="action-btn-mini" id="pasteBtn">@lang('Paste')</button>
                            </div>
                        </div>
                        <textarea name="string" id="textarea" class="cc-textarea" placeholder="Type or paste your text here..." required>{{ $results['original_text'] ?? old('string') }}</textarea>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="cc-editor-card shadow-sm">
                        <div class="cc-editor-header">
                            <span class="fw-bold text-muted small uppercase tracking-wider">@lang('CONVERTED RESULT')</span>
                            <div class="quick-actions mb-0">
                                <button type="button" class="action-btn-mini" id="copyBtn">@lang('Copy')</button>
                                <button type="button" class="action-btn-mini" id="downloadBtn">@lang('Download')</button>
                            </div>
                        </div>
                        <textarea id="resultTextarea" class="cc-textarea" readonly placeholder="Result will appear here...">{{ $results['converted_text'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <button type="submit" class="cc-btn-main shadow">
                    <i class="bi bi-arrow-repeat me-2"></i> @lang('CONVERT NOW')
                </button>
            </div>
        </form>

        {{-- Horizontal Ad Bottom --}}
        <div class="mt-5 text-center">
            <x-ad-slot :advertisement="get_advert_model('below-tool')" />
        </div>

        {{-- How it Works --}}
        <div class="mt-5 pt-5 border-top">
            <div class="text-center mb-5">
                <h2 class="fw-bold">@lang('Multipurpose Case Transformation')</h2>
                <p class="text-muted">@lang('Fast, reliable, and unique case conversion for every scenario.')</p>
            </div>
            <div class="how-it-works-steps">
                <div class="step-card">
                    <span class="step-number">1</span>
                    <i class="bi bi-textarea-t step-icon"></i>
                    <h4 class="step-title">@lang('Provide Text')</h4>
                    <p class="step-desc">@lang('Enter your sentences, titles, or code identifiers into the input area.')</p>
                </div>
                <div class="step-card">
                    <span class="step-number">2</span>
                    <i class="bi bi-grid-3x3-gap step-icon"></i>
                    <h4 class="step-title">@lang('Choose Case')</h4>
                    <p class="step-desc">@lang('Select from 11 different modes including CamelCase, Snake_Case, and more.')</p>
                </div>
                <div class="step-card">
                    <span class="step-number">3</span>
                    <i class="bi bi-lightning-auto step-icon"></i>
                    <h4 class="step-title">@lang('Instant Action')</h4>
                    <p class="step-desc">@lang('Click generate to instantly transform your text with perfect precision.')</p>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <x-related-tools :tool="$tool" />
        </div>

        <div class="mt-5 p-4 bg-light rounded-4 border" id="howToUseContent">
            <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>@lang('About Case Converter Tool')</h5>
            <div class="how-to-use-list text-muted" style="font-size: 14px; line-height: 1.9;">
                {!! strip_tags($tool->content, '<p><a><strong><ul><ol><li>') !!}
            </div>
        </div>
    </div>

    @push('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const caseTypeInput = document.getElementById('caseTypeInput');
            const pills = document.querySelectorAll('.case-pill');
            const textarea = document.getElementById('textarea');
            const resultTextarea = document.getElementById('resultTextarea');

            // Switch Case Type
            pills.forEach(pill => {
                pill.addEventListener('click', function() {
                    pills.forEach(p => p.classList.remove('active'));
                    this.classList.add('active');
                    caseTypeInput.value = this.dataset.value;
                });
            });

            // Paste Functionality
            document.getElementById('pasteBtn').addEventListener('click', async () => {
                try {
                    const text = await navigator.clipboard.readText();
                    textarea.value = text;
                } catch (err) {
                    ArtisanApp.toastError("Could not access clipboard");
                }
            });

            // Copy Result
            document.getElementById('copyBtn').addEventListener('click', function() {
                const text = resultTextarea.value.trim();
                if(!text) return;
                navigator.clipboard.writeText(text).then(() => {
                    this.innerHTML = '<i class="bi bi-check-lg"></i> Copied!';
                    this.classList.add('text-success');
                    setTimeout(() => {
                        this.innerHTML = 'Copy';
                        this.classList.remove('text-success');
                    }, 2000);
                });
            });

            // Download Result
            document.getElementById('downloadBtn').addEventListener('click', function() {
                const text = resultTextarea.value.trim();
                if(!text) return;
                const blob = new Blob([text], { type: 'text/plain' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'converted-case.txt';
                a.click();
            });
        });
    </script>
    @endpush
</x-canvas-layout>
