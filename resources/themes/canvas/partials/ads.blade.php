{{-- Standard ads partial - renders ads for eligible users --}}
@guest
    <x-ad-slot :advertisement="get_advert_model('sidebar')" />
@endguest

@auth
    @if(auth()->user()->is_ads_allowed)
        <x-ad-slot :advertisement="get_advert_model('sidebar')" />
    @endif
@endauth
