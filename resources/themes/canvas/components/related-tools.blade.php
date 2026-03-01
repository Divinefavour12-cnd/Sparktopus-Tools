@props(['tool'])

@php
    // Get related tools using smarter matching
    $tagIds = $tool->tags->pluck('id');
    $categoryIds = $tool->category->pluck('id');

    $relatedTools = \App\Models\Tool::active()
        ->where('id', '!=', $tool->id)
        ->where(function ($query) use ($tagIds, $categoryIds) {
            $query->whereHas('tags', function ($q) use ($tagIds) {
                $q->whereIn('tags.id', $tagIds);
            })->orWhereHas('category', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        })
        ->with('translations')
        ->withCount(['tags' => function ($query) use ($tagIds) {
            $query->whereIn('tags.id', $tagIds);
        }])
        ->orderBy('tags_count', 'desc')
        ->inRandomOrder() // Still add some variety within ranked results
        ->limit(4)
        ->get();
    
    // Icon mapping for tools
    $iconMap = config('tool-icons');
    
    $planOrder = ['free' => 0, 'classic' => 1, 'plus' => 2, 'pro' => 3];
    $userPlan = auth()->check() ? auth()->user()->planLevel() : 'free';
@endphp

@if($relatedTools->count() > 0)
<div class="related-tools-section">
    <div class="related-tools-header">
        <h3 class="related-tools-title">
            <i class="bi bi-grid-3x3-gap-fill"></i>
            @lang('Related Tools')
        </h3>
        <a href="{{ route('front.tools') }}" class="related-tools-more">
            @lang('View All') <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="related-tools-grid">
        @foreach($relatedTools as $relatedTool)
            @php
                $toolIcon = $relatedTool->icon_class ?: ($iconMap[$relatedTool->slug] ?? 'bi-tools');
                $requiredPlan = $relatedTool->required_plan ?? 'free';
                $hasAccess = ($planOrder[$userPlan] ?? 0) >= ($planOrder[$requiredPlan] ?? 0);
                $planBadgeClass = match($requiredPlan) {
                    'classic' => 'badge-classic',
                    'plus' => 'badge-plus',
                    'pro' => 'badge-pro',
                    default => 'badge-free'
                };
            @endphp
            <a href="{{ route('tool.show', ['tool' => $relatedTool->slug]) }}" 
               class="related-tool-card {{ !$hasAccess ? 'locked' : '' }}">
                <div class="related-tool-icon">
                    <i class="bi {{ $toolIcon }}"></i>
                </div>
                <div class="related-tool-content">
                    <h4 class="related-tool-name">{{ $relatedTool->name }}</h4>
                    <p class="related-tool-desc">{{ Str::limit($relatedTool->description ?? $relatedTool->meta_description, 50) }}</p>
                </div>
                <div class="related-tool-badge {{ $planBadgeClass }}">
                    @if($requiredPlan === 'free')
                        <i class="bi bi-star"></i>
                    @elseif($requiredPlan === 'classic')
                        <i class="bi bi-shield"></i>
                    @elseif($requiredPlan === 'plus')
                        <i class="bi bi-rocket"></i>
                    @else
                        <i class="bi bi-gem"></i>
                    @endif
                    {{ ucfirst($requiredPlan) }}
                </div>
                @if(!$hasAccess)
                    <div class="related-tool-lock">
                        <i class="bi bi-lock-fill"></i>
                    </div>
                @endif
            </a>
        @endforeach
    </div>
</div>
@endif
