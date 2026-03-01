<x-spark-admin-layout title="Site Settings">
    @php $me = auth('admin')->user(); @endphp
    <div class="spark-card" style="max-width: 900px; margin: 0 auto;">
        <h3 style="margin-top: 0; margin-bottom: 30px; font-size: 1.25rem;"><i class="bi bi-sliders"></i> Core Configuration</h3>

        <form action="{{ route('spark-admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Simple Tab System -->
            <div style="display: flex; gap: 30px; border-bottom: 1px solid var(--spark-voodoo-border); margin-bottom: 35px; padding-bottom: 10px;">
                <button type="button" class="tab-btn active" onclick="showTab('general', this)" style="background: none; border: none; color: #fff; font-weight: 700; font-size: 0.95rem; cursor: pointer; padding: 10px 0; border-bottom: 2px solid var(--spark-voodoo-accent);">General</button>
                <button type="button" class="tab-btn" onclick="showTab('branding', this)" style="background: none; border: none; color: var(--spark-voodoo-text-muted); font-weight: 600; font-size: 0.95rem; cursor: pointer; padding: 10px 0;">Branding</button>
                <button type="button" class="tab-btn" onclick="showTab('contact', this)" style="background: none; border: none; color: var(--spark-voodoo-text-muted); font-weight: 600; font-size: 0.95rem; cursor: pointer; padding: 10px 0;">Contact</button>
                <button type="button" class="tab-btn" onclick="showTab('seo', this)" style="background: none; border: none; color: var(--spark-voodoo-text-muted); font-weight: 600; font-size: 0.95rem; cursor: pointer; padding: 10px 0;">Metadata (SEO)</button>
            </div>

            <!-- Tab Contents -->
            <div id="settings-tabs">
                {{-- General Tab --}}
                <div id="tab-general" class="tab-content" style="display: flex; flex-direction: column; gap: 25px;">
                    <div class="input-group">
                        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Website Name (APP_NAME)</label>
                        <input type="text" name="settings[app_name]" value="{{ setting('app_name') }}" required style="width: 100%; height: 50px; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 0 15px;">
                    </div>
                    <div class="input-group">
                        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Website URL (APP_URL)</label>
                        <input type="url" name="settings[app_url]" value="{{ setting('app_url') }}" required style="width: 100%; height: 50px; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 0 15px;">
                    </div>
                </div>

                {{-- Branding Tab --}}
                <div id="tab-branding" class="tab-content" style="display: none; flex-direction: column; gap: 25px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                        <div class="input-group">
                            <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Light Logo</label>
                            @if(setting('website_logo'))
                                <img src="{{ asset('uploads/'.setting('website_logo')) }}" style="max-height: 40px; margin-bottom: 10px; display: block;">
                            @endif
                            <input type="file" name="settings[website_logo]" style="width: 100%; color: #fff; font-size: 0.8rem;">
                        </div>
                        <div class="input-group">
                            <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Dark Logo</label>
                            @if(setting('website_logo_dark'))
                                <img src="{{ asset('uploads/'.setting('website_logo_dark')) }}" style="max-height: 40px; margin-bottom: 10px; display: block;">
                            @endif
                            <input type="file" name="settings[website_logo_dark]" style="width: 100%; color: #fff; font-size: 0.8rem;">
                        </div>
                    </div>
                </div>

                {{-- Contact Tab --}}
                <div id="tab-contact" class="tab-content" style="display: none; flex-direction: column; gap: 25px;">
                    <div class="input-group">
                        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Support Email</label>
                        <input type="email" name="settings[website_email]" value="{{ setting('website_email') }}" style="width: 100%; height: 50px; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 0 15px;">
                    </div>
                </div>

                {{-- SEO Tab --}}
                <div id="tab-seo" class="tab-content" style="display: none; flex-direction: column; gap: 25px;">
                    <div class="input-group">
                        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Meta Title</label>
                        <input type="text" name="settings[meta_title]" value="{{ setting('meta_title') }}" style="width: 100%; height: 50px; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 0 15px;">
                    </div>
                    <div class="input-group">
                        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Meta Description</label>
                        <textarea name="settings[meta_description]" rows="5" style="width: 100%; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 15px; font-family: inherit; line-height: 1.6;">{{ setting('meta_description') }}</textarea>
                    </div>
                </div>
            </div>

            <div style="margin-top: 40px; border-top: 1px solid var(--spark-voodoo-border); padding-top: 30px; display: flex; justify-content: flex-end;">
                @if($me->hasRole('Super Admin') || $me->hasPermissionTo('system.settings'))
                <button type="submit" class="spark-btn spark-btn-primary" style="padding: 12px 40px;"><i class="bi bi-save"></i> Save Site Configuration</button>
                @endif
            </div>
        </form>
    </div>

    <script>
        function showTab(tabId, btn) {
            // Hide all contents
            document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
            // Show selected
            document.getElementById('tab-' + tabId).style.display = 'flex';
            
            // Update buttons
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.style.color = 'var(--spark-voodoo-text-muted)';
                b.style.fontWeight = '600';
                b.style.borderBottom = 'none';
            });
            btn.style.color = '#fff';
            btn.style.fontWeight = '700';
            btn.style.borderBottom = '2px solid var(--spark-voodoo-accent)';
        }
    </script>
</x-spark-admin-layout>
