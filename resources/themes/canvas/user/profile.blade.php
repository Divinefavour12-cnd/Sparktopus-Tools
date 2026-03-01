<x-user-profile :title="__('common.editProfile')">
    {{-- Dark Mode Toggle Section --}}
    <div class="form-group mb-4">
        <label class="form-label">Theme Preference</label>
        <div class="d-flex align-items-center gap-3">
            <button type="button" id="theme-toggle-profile" class="btn btn-outline-primary d-flex align-items-center gap-2">
                <i class="bi bi-moon" id="theme-icon-profile"></i>
                <span id="theme-text-profile">Dark Mode</span>
            </button>
            <small class="text-muted">Toggle between light and dark mode</small>
        </div>
    </div>

    <hr class="my-4">

    <x-form :route="route('user.profile.update')" enctype="multipart/form-data">
        <div class="form-group mb-3">
            <label class="form-label">@lang('tools.fullName')</label>
            <input class="form-control" type="text" name="name" required value="{{ $user->name }}" />
        </div>
        <div class="form-group mb-3">
            <label class="form-label">@lang('profile.username')</label>
            <input class="form-control" type="text" name="username" required value="{{ $user->username }}" />
        </div>
        <div class="form-group mb-3">
            <label class="form-label">@lang('profile.about')</label>
            <textarea aria-labelledby="{{ __('profile.about') }}" class="form-control" type="text" name="about">{{ $user->about }}</textarea>
        </div>
        <div class="form-group mb-3">
            <label class="form-label">@lang('profile.image')</label>
            <input class="form-control" type="file" name="image" accept=".png, .jpg, .jpeg, .gif" />
        </div>
        <div class="form-group mb-3">
            <input type="submit" value="Save" class="btn btn-primary" />
        </div>
    </x-form>
</x-user-profile>

{{-- Dark Mode Toggle Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle-profile');
    const themeIcon = document.getElementById('theme-icon-profile');
    const themeText = document.getElementById('theme-text-profile');
    const html = document.documentElement;
    
    if (themeToggle && themeIcon && themeText) {
        // Set initial state
        const currentTheme = html.getAttribute('theme-mode');
        if (currentTheme === 'dark') {
            themeIcon.className = 'bi bi-sun-fill';
            themeText.textContent = 'Light Mode';
            themeToggle.classList.remove('btn-outline-primary');
            themeToggle.classList.add('btn-outline-warning');
        }
        
        themeToggle.addEventListener('click', function() {
            const currentMode = html.getAttribute('theme-mode');
            const newMode = currentMode === 'dark' ? 'light' : 'dark';
            html.setAttribute('theme-mode', newMode);
            
            if (newMode === 'dark') {
                themeIcon.className = 'bi bi-sun-fill';
                themeText.textContent = 'Light Mode';
                themeToggle.classList.remove('btn-outline-primary');
                themeToggle.classList.add('btn-outline-warning');
            } else {
                themeIcon.className = 'bi bi-moon';
                themeText.textContent = 'Dark Mode';
                themeToggle.classList.remove('btn-outline-warning');
                themeToggle.classList.add('btn-outline-primary');
            }
            
            document.cookie = 'siteMode=' + newMode + ';path=/;max-age=31536000';
        });
    }
});
</script>
