@props(['tool'])
    <div class="tool-how-to-use mt-5 pt-5 border-top">
        <x-page-wrapper>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">@lang('tools.howToUse')</h3>
                @if (setting('display_socialshare_icon', 1) == 1)
                    <x-page-social-share element-classes="mb-0" style="style3" :url="route('tool.show', ['tool' => $tool->slug])"
                        :title="$tool->meta_title ?? $tool->name" />
                @endif
            </div>
            
            <div class="tool-article-content lead">
                {!! $tool->content !!}
            </div>
        </x-page-wrapper>
    </div>

    {{-- Related Tools Section --}}
    <div class="related-tools-section mt-5">
        <x-related-tools :tool="$tool" />
    </div>

    <x-ad-slot :advertisement="get_advert_model('below-result')" />

