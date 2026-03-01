<x-spark-admin-layout title="Edit Ad Slot">
    <div class="spark-card" style="max-width: 800px; margin: 0 auto;">
        <h3 style="margin-top: 0; margin-bottom: 30px; font-size: 1.25rem;"><i class="bi bi-pencil-square"></i> Edit Ad Configuration</h3>

        <form action="{{ route('spark-admin.advertisement.update', $advertisement) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="{{ $advertisement->type }}">

            <div style="display: flex; flex-direction: column; gap: 25px;">
                <div class="input-group">
                    <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Slot Name (Internal Reference)</label>
                    <input type="text" name="title" value="{{ $advertisement->title }}" placeholder="e.g., Homepage Top Banner" required style="width: 100%; height: 50px; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 0 15px;">
                </div>

                <div class="input-group">
                    <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Placement ID / Unique Key</label>
                    <input type="text" name="name" value="{{ $advertisement->name }}" placeholder="e.g., home_top_ad" required style="width: 100%; height: 50px; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 0 15px;">
                </div>

                @if($advertisement->type == 1) {{-- Google Ads --}}
                    <div class="input-group">
                        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">AdSense Code / JS</label>
                        <textarea name="options[ad_code]" rows="8" placeholder="Paste your AdSense <ins> or <script> code here..." required style="width: 100%; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 15px; font-family: monospace; line-height: 1.6;">{{ $advertisement->options['ad_code'] ?? '' }}</textarea>
                    </div>
                @elseif($advertisement->type == 2) {{-- Custom Content (Image/Video) --}}
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <input type="hidden" name="options[image]" value="{{ $advertisement->options['image'] ?? '' }}">
                        <input type="hidden" name="options[video]" value="{{ $advertisement->options['video'] ?? '' }}">
                        
                        <div class="input-group">
                            <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Target URL (Upgrade Link or External)</label>
                            <input type="url" name="options[url]" value="{{ $advertisement->options['url'] ?? '' }}" placeholder="https://..." required style="width: 100%; height: 50px; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 0 15px;">
                        </div>
                        <div class="input-group">
                            <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Banner Image</label>
                             @if(isset($advertisement->options['image']) && $advertisement->options['image'])
                                <div style="margin-bottom: 5px; font-size: 0.75rem; color: var(--spark-voodoo-accent);">File: {{ basename($advertisement->options['image']) }}</div>
                             @endif
                            <input type="file" name="options[image]" accept="image/*" style="width: 100%; height: 50px; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 10px 15px;">
                        </div>
                        <div class="input-group">
                            <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Video File (Optional)</label>
                             @if(isset($advertisement->options['video']) && $advertisement->options['video'])
                                <div style="margin-bottom: 5px; font-size: 0.75rem; color: var(--spark-voodoo-accent);">File: {{ basename($advertisement->options['video']) }}</div>
                             @endif
                            <input type="file" name="options[video]" accept="video/*" style="width: 100%; height: 50px; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 10px 15px;">
                        </div>
                        <div class="input-group">
                            <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Close Countdown (Seconds)</label>
                            <input type="number" name="options[countdown]" value="{{ $advertisement->options['countdown'] ?? 5 }}" placeholder="e.g., 5" style="width: 100%; height: 50px; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 0 15px;">
                        </div>
                    </div>
                @else {{-- Custom Script --}}
                    <div class="input-group">
                        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Raw Script / HTML</label>
                        <textarea name="options[script]" rows="8" placeholder="Paste your raw HTML/JS script here..." required style="width: 100%; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 15px; font-family: monospace; line-height: 1.6;">{{ $advertisement->options['script'] ?? '' }}</textarea>
                    </div>
                @endif

                <div style="display: flex; gap: 20px; margin-top: 10px;">
                    <button type="submit" class="spark-btn spark-btn-primary" style="flex: 2;">Save Changes</button>
                    <a href="{{ route('spark-admin.advertisement.index') }}" class="spark-btn" style="flex: 1; text-align: center; text-decoration: none; background: rgba(255,255,255,0.05); color: #fff;">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</x-spark-admin-layout>
