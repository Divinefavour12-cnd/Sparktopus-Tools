@props([
    'tool' => false,
])
<div {!! $attributes->merge(['class' => 'wrap-content', 'id']) !!}>
    <div class="hero-title text-center mb-5">
        @if (setting('disable_favorite_tools', '1') == 1)
            <div class="tool-favorite-btn">
                <button aria-label="@lang('tools.addToFavorite')"
                    class="btn btn-outline-primary rounded-circle add-fav  @if (Auth::check()) add-favorite-btn @endif @if (Auth::check() && $tool->hasBeenFavoritedBy(Auth::user())) active @endif"
                    data-id="{{ $tool->id }}" type="button" id="button"
                    data-url="{{ route('tool.favouriteAction') }}"
                    @if (!Auth::check()) onclick="window.location.href=`{{ route('login') }}`;" @endif>
                    <i class="an an-heart"></i>
                </button>
            </div>
        @endif
        @if (!empty($tool->name))
            <h1 class="display-4 fw-bold mb-3">{{ $tool->name }}</h1>
        @endif
        @if (!empty($tool->description))
            <p class="lead text-muted mb-4 mx-auto" style="max-width: 800px;">{{ $tool->description ?? '' }}</p>
        @endif

        @if (isset($actions))
            <div class="tool-actions mb-4 d-flex justify-content-center gap-2">
                {{ $actions }}
            </div>
        @endif

        @if (Auth::check() && $tool->isAiTool())
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <x-usage-showcase :tool="$tool" />
                </div>
            </div>
        @endif

        {{-- Horizontal Ad Section --}}
        @if (setting('display_ads', 1) == 1)
            <div class="ad-section mb-4">
                <x-ad-slot :advertisement="get_advert_model('above-form')" />
            </div>
        @endif
    </div>
    <div class="tool-main-content">
        {{ $slot }}
    </div>
</div>
