<x-spark-admin-layout :title="'Edit Tool: ' . $tool->name">
    <form action="{{ route('spark-admin.tools.update', $tool) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h2 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #fff;">Tool Customization</h2>
                <p style="color: var(--spark-text-muted); margin: 5px 0 0 0;">Fine-tune your tool's behavior and appearance.</p>
            </div>
            <button type="submit" class="spark-btn spark-btn-primary" style="padding: 12px 40px; border-radius: 12px;">
                <i class="bi bi-save2-fill" style="margin-right: 8px;"></i> Save Changes
            </button>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
            <div style="display: flex; flex-direction: column; gap: 30px;">
                
                <!-- Translation Tabs -->
                <div class="spark-card">
                    <div style="display: flex; gap: 20px; border-bottom: 1px solid var(--spark-border); margin-bottom: 25px; padding-bottom: 10px;">
                        @foreach($locales as $locale)
                            <button type="button" 
                                    onclick="switchTab('{{ $locale->locale }}')"
                                    id="btn-{{ $locale->locale }}"
                                    class="locale-tab-btn {{ $loop->first ? 'active' : '' }}"
                                    style="background: none; border: none; color: var(--spark-text-muted); font-weight: 600; cursor: pointer; padding: 10px 0; position: relative;">
                                {{ strtoupper($locale->locale) }}
                                <div class="tab-indicator" style="display: {{ $loop->first ? 'block' : 'none' }}; position: absolute; bottom: -11px; left: 0; right: 0; height: 3px; background: var(--spark-accent);"></div>
                            </button>
                        @endforeach
                    </div>

                    @foreach($locales as $locale)
                        @php $trans = $tool->translate($locale->locale); @endphp
                        <div id="tab-{{ $locale->locale }}" class="locale-tab-content" style="display: {{ $loop->first ? 'block' : 'none' }};">
                            <div style="display: grid; gap: 20px;">
                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Display Name ({{ strtoupper($locale->locale) }})</label>
                                    <input type="text" name="{{ $locale->locale }}[name]" value="{{ $trans->name ?? '' }}" class="spark-input" style="width: 100%; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-border); padding: 12px; border-radius: 12px; color: #fff;">
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Meta Description</label>
                                    <textarea name="{{ $locale->locale }}[description]" rows="3" class="spark-input" style="width: 100%; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-border); padding: 12px; border-radius: 12px; color: #fff;">{{ $trans->description ?? '' }}</textarea>
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Page Content (Rich Text)</label>
                                    <textarea name="{{ $locale->locale }}[content]" id="editor-{{ $locale->locale }}" class="spark-editor">{{ $trans->content ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Technical Settings -->
                <div class="spark-card">
                    <h3 style="margin: 0 0 20px 0; font-size: 1.1rem;">Technical Configuration</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Tool Slug</label>
                            <input type="text" name="slug" value="{{ $tool->slug }}" class="spark-input" style="width: 100%; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-border); padding: 12px; border-radius: 12px; color: #fff;">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Category</label>
                            <select name="category" class="spark-input" style="width: 100%; background: rgba(0,0,0,0.5); border: 1px solid var(--spark-border); padding: 12px; border-radius: 12px; color: #fff;">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ in_array($cat->id, $tool->category->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Custom Integration Settings (Dynamic) -->
                @if(isset($form_fields['fields']) && count($form_fields['fields']) > 0)
                    <div class="spark-card">
                        <h3 style="margin: 0 0 20px 0; font-size: 1.1rem;">{{ $form_fields['title'] ?? 'Tool-Specific Settings' }}</h3>
                        <div style="display: grid; gap: 20px;">
                            @foreach($form_fields['fields'] as $field)
                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">{{ $field['title'] }}</label>
                                    @if($field['field'] === 'input')
                                        <input type="text" name="settings[{{ $field['id'] }}]" value="{{ $tool->settings[$field['id']] ?? '' }}" class="spark-input" style="width: 100%; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-border); padding: 12px; border-radius: 12px; color: #fff;">
                                    @elseif($field['field'] === 'textarea')
                                        <textarea name="settings[{{ $field['id'] }}]" rows="4" class="spark-input" style="width: 100%; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-border); padding: 12px; border-radius: 12px; color: #fff;">{{ $tool->settings[$field['id']] ?? '' }}</textarea>
                                    @elseif($field['field'] === 'select')
                                        <select name="settings[{{ $field['id'] }}]" class="spark-input" style="width: 100%; background: rgba(0,0,0,0.5); border: 1px solid var(--spark-border); padding: 12px; border-radius: 12px; color: #fff;">
                                            @foreach($field['options'] as $val => $label)
                                                <option value="{{ $val }}" {{ ($tool->settings[$field['id']] ?? '') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Sidebar: Identity -->
            <div style="display: flex; flex-direction: column; gap: 30px;">
                <div class="spark-card">
                    <h3 style="margin: 0 0 20px 0; font-size: 1.1rem;">Branding & Identity</h3>
                    <div style="text-align: center; margin-bottom: 25px;">
                        <div style="width: 100px; height: 100px; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-border); border-radius: 24px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: var(--spark-accent);">
                            @if($tool->icon_type === 'class')
                                <i class="{{ $tool->icon_class }}"></i>
                            @else
                                <img src="{{ asset($tool->icon) }}" style="width: 60%; height: 60%; object-fit: contain;">
                            @endif
                        </div>
                        <p style="font-size: 0.8rem; color: var(--spark-text-muted);">Current Tool Identifier</p>
                    </div>

                    <div style="display: grid; gap: 20px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Icon Type</label>
                            <select name="icon_type" id="icon_type_selector" class="spark-input" style="width: 100%; background: rgba(0,0,0,0.5); border: 1px solid var(--spark-border); padding: 12px; border-radius: 12px; color: #fff;">
                                <option value="class" {{ $tool->icon_type === 'class' ? 'selected' : '' }}>Icon Class (Bootstrap/FontAwesome)</option>
                                <option value="file" {{ $tool->icon_type === 'file' ? 'selected' : '' }}>Custom Upload (SVG/PNG)</option>
                            </select>
                        </div>

                        <div id="icon_class_group" style="display: {{ $tool->icon_type === 'class' ? 'block' : 'none' }};">
                            <label style="display: block; margin-bottom: 8px; font-weight: 500;">CSS Class</label>
                            <input type="text" name="icon_class" value="{{ $tool->icon_class }}" class="spark-input" placeholder="bi bi-rocket" style="width: 100%; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-border); padding: 12px; border-radius: 12px; color: #fff;">
                        </div>

                        <div id="icon_file_group" style="display: {{ $tool->icon_type === 'file' ? 'block' : 'none' }};">
                            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Upload Icon</label>
                            <input type="file" name="icon" class="spark-input" style="width: 100%; color: var(--spark-text-muted);">
                        </div>
                    </div>
                </div>

                <div class="spark-card" style="background: linear-gradient(135deg, rgba(var(--spark-accent), 0.1) 0%, rgba(var(--spark-accent), 0) 100%); border-color: rgba(var(--spark-accent), 0.2);">
                    <h3 style="margin: 0 0 15px 0; font-size: 1.1rem; color: var(--spark-accent);">Access Control</h3>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.85rem;">Required Subscription Plan</label>
                        <select name="required_plan" class="spark-input" style="width: 100%; background: var(--spark-base); border: 1px solid var(--spark-border); padding: 12px; border-radius: 12px; color: var(--spark-text);">
                            <option value="free" {{ $tool->required_plan === 'free' ? 'selected' : '' }}>Free (All Users)</option>
                            @foreach($plans as $plan)
                                <option value="{{ strtolower($plan->name) }}" {{ $tool->required_plan === strtolower($plan->name) ? 'selected' : '' }}>
                                    {{ $plan->name }} Only
                                </option>
                            @endforeach
                        </select>
                        <p style="font-size: 0.75rem; color: var(--spark-text-muted); margin-top: 8px;">Restricts usage to users with this plan or higher.</p>
                    </div>
                </div>

                <div class="spark-card">
                    <h3 style="margin: 0 0 15px 0; font-size: 1.1rem;">Visibility Settings</h3>
                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                            <input type="checkbox" name="display" value="1" {{ $tool->display ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--spark-accent);">
                            <span style="font-weight: 500;">Show in Navigation</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                            <input type="checkbox" name="is_home" value="1" {{ $tool->is_home ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--spark-accent);">
                            <span style="font-weight: 500;">Set as Homepage Tool</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <style>
        .locale-tab-btn.active { color: #fff !important; }
        .locale-tab-btn:hover { color: #fff !important; }
        .spark-input:focus { outline: none; border-color: var(--spark-voodoo-accent) !important; box-shadow: 0 0 10px var(--spark-accent-glow); }
        .ck-editor__main .ck-content { background: rgba(255,255,255,0.03) !important; color: #fff !important; border: 1px solid var(--spark-voodoo-border) !important; border-bottom-left-radius: 12px !important; border-bottom-right-radius: 12px !important; min-height: 200px; }
        .ck-toolbar { background: var(--spark-surface) !important; border: 1px solid var(--spark-voodoo-border) !important; border-top-left-radius: 12px !important; border-top-right-radius: 12px !important; }
        .ck.ck-button:hover { background: rgba(255,255,255,0.05) !important; }
        .ck.ck-list { background: var(--spark-surface) !important; border: 1px solid var(--spark-voodoo-border) !important; }
    </style>

    @push('scripts')
        <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
        <script>
            function switchTab(locale) {
                document.querySelectorAll('.locale-tab-content').forEach(el => el.style.display = 'none');
                document.querySelectorAll('.locale-tab-btn').forEach(el => {
                    el.classList.remove('active');
                    el.querySelector('.tab-indicator').style.display = 'none';
                });
                
                document.getElementById('tab-' + locale).style.display = 'block';
                const activeBtn = document.getElementById('btn-' + locale);
                activeBtn.classList.add('active');
                activeBtn.querySelector('.tab-indicator').style.display = 'block';
            }

            document.getElementById('icon_type_selector').addEventListener('change', function() {
                const isClass = this.value === 'class';
                document.getElementById('icon_class_group').style.display = isClass ? 'block' : 'none';
                document.getElementById('icon_file_group').style.display = isClass ? 'none' : 'block';
            });

            @foreach($locales as $locale)
                ClassicEditor
                    .create(document.querySelector('#editor-{{ $locale->locale }}'), {
                        toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ]
                    })
                    .catch(error => { console.error(error); });
            @endforeach
        </script>
    @endpush
</x-spark-admin-layout>
