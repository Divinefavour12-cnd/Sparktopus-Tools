<div id="app-loader" class="loading-overlay d-none">
    <div class="loading">
        <i class="bi bi-tools loader-icon"></i>
    </div>
</div>

<style>
.loading-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 100000;
}
.loading-overlay.d-none { display: none; }
.loading-overlay .loader-icon {
    font-size: 80px;
    color: #6000C2;
    animation: spark-loader-pulse 1.5s ease-in-out infinite;
}
@keyframes spark-loader-pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.15); opacity: 0.7; }
}
</style>
