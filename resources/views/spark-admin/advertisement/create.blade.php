<x-spark-admin-layout title="Create Ad Slot">
    <div class="spark-card" style="max-width: 800px; margin: 0 auto;">
        <h3 style="margin-top: 0; margin-bottom: 30px; font-size: 1.25rem;">🆕 New Ad Configuration</h3>

        <form action="{{ route('spark-admin.advertisement.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            <div style="display: flex; flex-direction: column; gap: 25px;">
                <div class="input-group">
                    <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Slot Name (Internal Reference)</label>
                    <input type="text" name="title" placeholder="e.g., Homepage Top Banner" required style="width: 100%; height: 50px; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 0 15px;">
                </div>

                <div class="input-group">
                    <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Placement ID / Unique Key</label>
                    <input type="text" name="name" placeholder="e.g., home_top_ad" required style="width: 100%; height: 50px; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 0 15px;">
                </div>

                @if($type == 1) {{-- Google Ads --}}
                    <div class="input-group">
                        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">AdSense Code / JS</label>
                        <textarea name="options[ad_code]" rows="8" placeholder="Paste your AdSense <ins> or <script> code here..." required style="width: 100%; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 15px; font-family: monospace; line-height: 1.6;"></textarea>
                    </div>
                @elseif($type == 2) {{-- Custom Content (Image/Video) --}}
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="input-group">
                            <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Target URL (Upgrade Link or External)</label>
                            <input type="url" name="options[url]" placeholder="https://..." required style="width: 100%; height: 50px; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 0 15px;">
                        </div>
                        <div class="input-group">
                            <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Banner Image</label>
                            <input type="file" name="options[image]" accept="image/*" style="width: 100%; height: 50px; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 10px 15px;">
                        </div>
                        <div class="input-group">
                            <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Video File (Optional)</label>
                            <input type="file" name="options[video]" accept="video/*" style="width: 100%; height: 50px; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 10px 15px;">
                        </div>
                        <div class="input-group">
                            <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Close Countdown (Seconds)</label>
                            <input type="number" name="options[countdown]" placeholder="e.g., 5" value="5" style="width: 100%; height: 50px; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 0 15px;">
                        </div>
                    </div>
                @else {{-- Custom Script --}}
                    <div class="input-group">
                        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Raw Script / HTML</label>
                        <textarea name="options[script]" rows="8" placeholder="Paste your raw HTML/JS script here..." required style="width: 100%; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 15px; font-family: monospace; line-height: 1.6;"></textarea>
                    </div>
                @endif

                <div style="display: flex; gap: 20px; margin-top: 10px;">
                    <button type="submit" class="spark-btn spark-btn-primary" style="flex: 2;">Activate Ad Slot</button>
                    <a href="{{ route('spark-admin.advertisement.index') }}" class="spark-btn" style="flex: 1; text-align: center; text-decoration: none; background: rgba(255,255,255,0.05); color: #fff;">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</x-spark-admin-layout>
