{{-- Page Loader with Sparktopus Logo --}}
<div class="page-loader" id="page-loader">
    <div class="loader-content">
        <i class="bi bi-tools loader-icon"></i>
    </div>
</div>

<style>
.page-loader .loader-icon {
    font-size: 80px;
    color: #6000C2; /* Purple */
    animation: spark-loader-pulse 1.5s ease-in-out infinite;
    display: block;
}

@keyframes spark-loader-pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.15); opacity: 0.7; }
}
</style>

<script>
    // Hide loader as early as possible (DOMContentLoaded fires before load)
    document.addEventListener('DOMContentLoaded', function() {
        const loader = document.getElementById('page-loader');
        if (loader) {
            loader.classList.add('hidden');
        }
    });
    // Fallback: force hide after 1.5s no matter what
    setTimeout(function() {
        const loader = document.getElementById('page-loader');
        if (loader) loader.classList.add('hidden');
    }, 1500);
    
    // Show loader on navigation/form submissions
    document.addEventListener('DOMContentLoaded', function() {
        // Show loader on link clicks (except # links and target="_blank")
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && 
                link.href && 
                !link.href.startsWith('#') && 
                !link.href.includes('javascript:') &&
                link.target !== '_blank' &&
                !link.hasAttribute('data-no-loader') &&
                !link.classList.contains('dropdown-toggle') &&
                !link.closest('.dropdown-menu')) {
                const loader = document.getElementById('page-loader');
                if (loader) {
                    loader.classList.remove('hidden');
                }
            }
        });
        
        // Show loader on form submissions
        document.querySelectorAll('form').forEach(function(form) {
            form.addEventListener('submit', function() {
                const loader = document.getElementById('page-loader');
                if (loader && !form.hasAttribute('data-no-loader')) {
                    loader.classList.remove('hidden');
                }
            });
        });
    });
</script>
