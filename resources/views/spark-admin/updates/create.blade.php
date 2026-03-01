<x-spark-admin-layout title="Create Update">
    <div class="spark-card" style="max-width: 900px; margin: 0 auto;">
        <h3 style="margin-top: 0; margin-bottom: 30px; font-size: 1.25rem;">🆕 New System Update</h3>

        <form action="{{ route('spark-admin.updates.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="author_id" value="{{ Auth::guard('admin')->id() }}">

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
                <div style="display: flex; flex-direction: column; gap: 25px;">
                    <!-- Title (English Default) -->
                    <div class="input-group">
                        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Title (EN)</label>
                        <input type="text" name="en[title]" placeholder="Enter update title..." required style="width: 100%; height: 50px; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 0 15px;">
                    </div>

                    <!-- Content (English Default) - Simplified Textarea for now -->
                    <div class="input-group">
                        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Content (EN)</label>
                        <textarea name="en[content]" rows="10" placeholder="Write the update details here..." required style="width: 100%; background: rgba(255,255,255,0.03); border: 1px solid var(--spark-voodoo-border); border-radius: 12px; color: #fff; padding: 15px; font-family: inherit; line-height: 1.6;"></textarea>
                    </div>

                    <div style="display: flex; gap: 20px;">
                        <button type="submit" class="spark-btn spark-btn-primary" style="flex: 2;">Publish Update</button>
                        <a href="{{ route('spark-admin.updates.index') }}" class="spark-btn" style="flex: 1; text-align: center; text-decoration: none; background: rgba(255,255,255,0.05); color: #fff;">Cancel</a>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 25px;">
                    <!-- Options -->
                    <div class="spark-card" style="padding: 20px;">
                        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Status</label>
                        <select name="status" style="width: 100%; height: 45px; background: #1a1a1e; border: 1px solid var(--spark-voodoo-border); border-radius: 10px; color: #fff; padding: 0 10px;">
                            <option value="1">Published</option>
                            <option value="0">Draft</option>
                        </select>
                    </div>

                    <div class="spark-card" style="padding: 20px;">
                        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Category</label>
                        <select name="categories[]" style="width: 100%; height: 45px; background: #1a1a1e; border: 1px solid var(--spark-voodoo-border); border-radius: 10px; color: #fff; padding: 0 10px;">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="spark-card" style="padding: 20px;">
                        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-voodoo-text-muted);">Featured Image</label>
                        <div style="border: 2px dashed var(--spark-voodoo-border); border-radius: 12px; padding: 20px; text-align: center;">
                             <input type="file" name="featured_image" accept="image/*" style="display: none;" id="featured_image">
                             <label for="featured_image" style="cursor: pointer; color: var(--spark-voodoo-accent);">
                                <i class="bi bi-cloud-arrow-up" style="font-size: 2rem; display: block; margin-bottom: 5px;"></i>
                                <span style="font-size: 0.8rem;">Click to upload</span>
                             </label>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-spark-admin-layout>
