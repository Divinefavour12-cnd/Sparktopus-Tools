<x-canvas-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0">Tool History</h1>
                    <a href="{{ route('front.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Back to Home
                    </a>
                </div>

                <div class="history-container">
                    @if($toolUsages->count() > 0)
                        <div class="d-flex flex-column gap-3">
                            @foreach($toolUsages as $usage)
                                @php
                                    $tool = \App\Models\Tool::where('slug', $usage->tool_name)->with('translations')->first();
                                    $iconMap = [
                                        'ai-humanizer' => 'bi-robot',
                                        'reverse-image-search' => 'bi-search',
                                        'md5-generator' => 'bi-shield-lock-fill',
                                        'sha256-hash-generator' => 'bi-shield-lock-fill',
                                        'image-background-remover' => 'bi-image-fill',
                                        'html-viewer' => 'bi-code-slash',
                                        'pdf-converter' => 'bi-file-earmark-pdf-fill',
                                        'qr-code-generator' => 'bi-qr-code',
                                        'password-generator' => 'bi-key-fill',
                                        'youtube-thumbnail-downloader' => 'bi-youtube',
                                    ];
                                    $toolIcon = $iconMap[$usage->tool_name] ?? 'bi-tools';
                                @endphp
                                @if($tool)
                                    <a href="{{ route('tool.show', ['tool' => $tool->slug]) }}" class="history-card">
                                        <div class="history-icon-circle me-3">
                                            <i class="bi {{ $toolIcon }}" style="font-size: 1.2rem;"></i>
                                        </div>
                                        <div class="tool-info flex-grow-1">
                                            <h4 class="history-title">{{ $tool->name }}</h4>
                                            <div class="tool-desc small text-muted">{{ Str::limit($tool->meta_description, 60) }}</div>
                                        </div>
                                        <div class="tool-meta text-muted small ms-3">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ \Carbon\Carbon::parse($usage->last_used_at)->diffForHumans() }}
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                        
                        <div class="mt-4">
                            {{ $toolUsages->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-clock-history display-1 text-muted"></i>
                            <p class="mt-3 text-muted">You haven't used any tools yet.</p>
                            <a href="{{ route('front.tools') }}" class="btn btn-primary mt-2">Explore Tools</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-canvas-layout>
