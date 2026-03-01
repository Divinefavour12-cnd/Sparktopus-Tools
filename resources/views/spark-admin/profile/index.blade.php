<x-spark-admin-layout title="Admin Profile">
    <div style="max-width: 800px; margin: 0 auto;">
        
        @if(session('success'))
        <div style="padding: 15px; background: rgba(16, 185, 129, 0.1); color: #10b981; border-radius: 12px; margin-bottom: 25px; border: 1px solid rgba(16, 185, 129, 0.2);">
            <i class="bi bi-check-circle-fill" style="margin-right: 8px;"></i> {{ session('success') }}
        </div>
        @endif

        <div class="spark-card" style="padding: 40px;">
            <div style="display: flex; align-items: center; gap: 24px; margin-bottom: 40px; padding-bottom: 30px; border-bottom: 1px solid var(--spark-border);">
                <div style="width: 80px; height: 80px; border-radius: 20px; background: var(--spark-accent); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 2rem; font-weight: 800; box-shadow: 0 10px 20px var(--spark-accent-glow);">
                    {{ substr($admin->name, 0, 1) }}
                </div>
                <div>
                    <h2 style="margin: 0; font-size: 1.5rem; font-weight: 800;">Master Identity</h2>
                    <p style="margin: 5px 0 0 0; color: var(--spark-text-muted);">Manage your administrative credentials and preferences.</p>
                </div>
            </div>

            <form action="{{ route('spark-admin.profile.update') }}" method="POST">
                @csrf
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-text-muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Admin Name</label>
                        <input type="text" name="name" value="{{ old('name', $admin->name) }}" style="width: 100%; padding: 14px 18px; background: rgba(0,0,0,0.02); border: 1px solid var(--spark-border); border-radius: 16px; color: var(--spark-text); font-family: inherit; font-size: 1rem; outline: none; transition: all 0.3s;" onfocus="this.style.borderColor='var(--spark-accent)'; this.style.boxShadow='0 0 0 4px var(--spark-accent-glow)'" onblur="this.style.borderColor='var(--spark-border)'; this.style.boxShadow='none'">
                        @error('name') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 5px; display: block;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-text-muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $admin->email) }}" style="width: 100%; padding: 14px 18px; background: rgba(0,0,0,0.02); border: 1px solid var(--spark-border); border-radius: 16px; color: var(--spark-text); font-family: inherit; font-size: 1rem; outline: none; transition: all 0.3s;" onfocus="this.style.borderColor='var(--spark-accent)'; this.style.boxShadow='0 0 0 4px var(--spark-accent-glow)'" onblur="this.style.borderColor='var(--spark-border)'; this.style.boxShadow='none'">
                        @error('email') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 5px; display: block;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div style="padding: 24px; background: rgba(var(--spark-accent), 0.02); border: 1px solid var(--spark-border); border-radius: 20px; margin-bottom: 30px;">
                    <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 1.1rem; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                        <i class="bi bi-shield-lock-fill" style="color: var(--spark-accent);"></i> Security Update
                    </h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                        <div>
                            <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-text-muted); font-size: 0.85rem;">New Password</label>
                            <input type="password" name="password" placeholder="Leave blank to keep current" style="width: 100%; padding: 12px 18px; background: var(--spark-base); border: 1px solid var(--spark-border); border-radius: 14px; color: var(--spark-text); outline: none;">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--spark-text-muted); font-size: 0.85rem;">Confirm Password</label>
                            <input type="password" name="password_confirmation" style="width: 100%; padding: 12px 18px; background: var(--spark-base); border: 1px solid var(--spark-border); border-radius: 14px; color: var(--spark-text); outline: none;">
                        </div>
                    </div>
                    @error('password') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 10px; display: block;">{{ $message }}</span> @enderror
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="spark-btn spark-btn-primary" style="padding: 14px 40px;">
                        <i class="bi bi-cloud-arrow-up-fill"></i> Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-spark-admin-layout>
