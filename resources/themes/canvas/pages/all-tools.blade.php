<x-canvas-layout>
    {{-- All Tools Page Header --}}
    <div class="all-tools-header">
        <div class="all-tools-title">
            <div class="d-flex align-items-center gap-3">
                <button class="btn p-0 border-0 bg-transparent text-secondary fs-4" id="all-tools-collapse-toggle" onclick="document.querySelector('#sidebar-collapse-toggle').click()" title="Toggle Sidebar">
                    <i class="bi bi-layout-sidebar-inset"></i>
                </button>
                <h1>All Tools</h1>
            </div>
            <p>{{ $totalTools ?? 'Discover' }} tools to accelerate your workflow</p>
        </div>
        <div class="all-tools-search">
            <i class="bi bi-search"></i>
            <input type="text" id="tools-search" placeholder="Search tools..." autocomplete="off">
        </div>
    </div>

    {{-- Category Filter Pills --}}
    <div class="category-filter">
        <button class="category-pill active" data-category="all">
            <i class="bi bi-grid-3x3-gap"></i> All
        </button>
        @foreach($tools as $category)
            <button class="category-pill" data-category="{{ Str::slug($category->name) }}">
                {{ $category->name }}
            </button>
        @endforeach
    </div>

    {{-- Tools Grid by Category --}}
    <div class="tools-container" id="tools-container">
        @foreach($tools as $category)
            <div class="category-section" data-category="{{ Str::slug($category->name) }}">
                <h2 class="category-title">
                    <span>{{ $category->name }}</span>
                    <span class="category-count">{{ $category->tools->count() }} tools</span>
                </h2>
                <div class="tools-grid">
                   @foreach($category->tools as $tool)
    @php
        $iconMap = config('tool-icons');
        
        // Get icon from database OR fallback to icon map
        $toolIcon = $tool->icon_class ?: ($iconMap[$tool->slug] ?? 'bi-tools');
        
        // Plan badge logic
        $requiredPlan = $tool->required_plan ?? 'free';
        $planBadgeClass = match($requiredPlan) {
            'classic' => 'badge-classic',
            'plus' => 'badge-plus',
            'pro' => 'badge-pro',
            default => 'badge-free'
        };
        $planLabel = ucfirst($requiredPlan);
        
        // Check access
        $userPlan = auth()->check() ? auth()->user()->planLevel() : 'free';
        $planOrder = ['free' => 0, 'classic' => 1, 'plus' => 2, 'pro' => 3];
        $hasAccess = ($planOrder[$userPlan] ?? 0) >= ($planOrder[$requiredPlan] ?? 0);
    @endphp
    
    <a href="{{ route('tool.show', ['tool' => $tool->slug]) }}" 
       class="tool-card {{ !$hasAccess ? 'locked' : '' }}" 
       data-name="{{ strtolower($tool->name) }}"
       data-description="{{ strtolower($tool->description ?? '') }}">
        
        {{-- UPDATED ICON CIRCLE - Same as homepage --}}
        <div class="tool-icon-circle">
            <i class="{{ $toolIcon }}"></i>
        </div>
        
        <div class="tool-card-content">
            <h3 class="tool-card-title">{{ $tool->name }}</h3>
            <p class="tool-card-desc">{{ Str::limit($tool->description ?? $tool->meta_description, 60) }}</p>
        </div>
        
        <div class="tool-card-badge {{ $planBadgeClass }}">
            @if($requiredPlan === 'free')
                <i class="bi bi-star"></i>
            @elseif($requiredPlan === 'classic')
                <i class="bi bi-shield"></i>
            @elseif($requiredPlan === 'plus')
                <i class="bi bi-rocket"></i>
            @else
                <i class="bi bi-gem"></i>
            @endif
            {{ $planLabel }}
        </div>
        
        @if(!$hasAccess)
            <div class="tool-lock-overlay">
                <i class="bi bi-lock-fill"></i>
            </div>
        @endif
    </a>
@endforeach
                </div>
            </div>
        @endforeach
    </div>

    {{-- No Results Message --}}
    <div class="no-results" id="no-results" style="display: none;">
        <i class="bi bi-search"></i>
        <h3>No tools found</h3>
        <p>Try a different search term</p>
    </div>

    {{-- Search Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('tools-search');
            const toolsContainer = document.getElementById('tools-container');
            const noResults = document.getElementById('no-results');
            const categoryPills = document.querySelectorAll('.category-pill');
            const categorySections = document.querySelectorAll('.category-section');
            
            let activeCategory = 'all';
            
            // Category filter
            categoryPills.forEach(pill => {
                pill.addEventListener('click', function() {
                    categoryPills.forEach(p => p.classList.remove('active'));
                    this.classList.add('active');
                    activeCategory = this.dataset.category;
                    filterTools();
                });
            });
            
            // Search filter
            searchInput.addEventListener('input', function() {
                filterTools();
            });
            
            function filterTools() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                let visibleTools = 0;
                
                categorySections.forEach(section => {
                    const sectionCategory = section.dataset.category;
                    const showSection = activeCategory === 'all' || activeCategory === sectionCategory;
                    
                    if (!showSection) {
                        section.style.display = 'none';
                        return;
                    }
                    
                    section.style.display = 'block';
                    const tools = section.querySelectorAll('.tool-card');
                    let sectionHasVisible = false;
                    
                    tools.forEach(tool => {
                        const name = tool.dataset.name || '';
                        const desc = tool.dataset.description || '';
                        const matches = searchTerm === '' || 
                                       name.includes(searchTerm) || 
                                       desc.includes(searchTerm);
                        
                        if (matches) {
                            tool.style.display = 'flex';
                            visibleTools++;
                            sectionHasVisible = true;
                        } else {
                            tool.style.display = 'none';
                        }
                    });
                    
                    // Hide section if no tools visible
                    if (!sectionHasVisible && searchTerm !== '') {
                        section.style.display = 'none';
                    }
                });
                
                // Show/hide no results
                noResults.style.display = visibleTools === 0 ? 'flex' : 'none';
                toolsContainer.style.display = visibleTools === 0 ? 'none' : 'block';
            }
        });
    </script>
</x-canvas-layout>
