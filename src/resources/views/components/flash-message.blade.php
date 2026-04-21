@php
    // success・status（Laravel標準）・error の優先順位で取得
    $successMessage = session('success') ?? session('status');
    $errorMessage   = session('error');
@endphp

@if ($successMessage)
<div id="flash-message"
    class="fixed bottom-6 right-6 z-[300] flex items-center gap-3 px-5 py-4 min-w-[280px] max-w-sm
           bg-white border border-emerald-200 text-emerald-800 rounded-2xl shadow-2xl
           translate-y-4 opacity-0 transition-all duration-500 ease-out"
    role="alert">
    <div class="shrink-0 w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <p class="text-sm font-bold flex-1">{{ $successMessage }}</p>
    <button onclick="dismissFlash()" class="shrink-0 text-emerald-400 hover:text-emerald-600 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>
@elseif ($errorMessage)
<div id="flash-message"
    class="fixed bottom-6 right-6 z-[300] flex items-center gap-3 px-5 py-4 min-w-[280px] max-w-sm
           bg-white border border-red-200 text-red-700 rounded-2xl shadow-2xl
           translate-y-4 opacity-0 transition-all duration-500 ease-out"
    role="alert">
    <div class="shrink-0 w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </div>
    <p class="text-sm font-bold flex-1">{{ $errorMessage }}</p>
    <button onclick="dismissFlash()" class="shrink-0 text-red-300 hover:text-red-500 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>
@endif

@if ($successMessage || $errorMessage)
<script>
    // ページ読み込み後にスライドインアニメーション
    window.addEventListener('DOMContentLoaded', () => {
        const msg = document.getElementById('flash-message');
        if (msg) {
            requestAnimationFrame(() => {
                msg.classList.remove('translate-y-4', 'opacity-0');
                msg.classList.add('translate-y-0', 'opacity-100');
            });
        }
    });

    // 4秒後に自動でスライドアウトして消える
    setTimeout(dismissFlash, 4000);

    function dismissFlash() {
        const msg = document.getElementById('flash-message');
        if (!msg) return;
        msg.classList.remove('translate-y-0', 'opacity-100');
        msg.classList.add('translate-y-4', 'opacity-0');
        setTimeout(() => msg.remove(), 500);
    }
</script>
@endif
