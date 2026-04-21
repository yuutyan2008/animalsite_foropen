<template x-teleport="body">
    <div x-show="open"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">

        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>

        <div class="relative w-full max-w-lg bg-white dark:bg-slate-800 rounded-3xl shadow-2xl overflow-hidden" @click.stop>
            <div class="p-6 border-b bg-white">
                <h3 class="text-xl font-bold text-slate-800">口コミの編集</h3>
            </div>

            <form action="{{ route('reviews.update', $review) }}" method="POST" class="p-6 space-y-5"
                x-data="{ star_count: {{ $review->star_count }} }">
                @csrf
                @method('PUT')

                {{-- 星評価 --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">評価</label>
                    <div class="flex gap-2">
                        <template x-for="i in 5" :key="i">
                            <button type="button"
                                @click="star_count = i"
                                class="text-3xl transition-transform hover:scale-110"
                                :class="i <= star_count ? 'text-yellow-400' : 'text-slate-200'">
                                ★
                            </button>
                        </template>
                        <input type="hidden" name="star_count" :value="star_count">
                    </div>
                </div>

                {{-- コメント入力 --}}
                <div>
                    <textarea name="comment" rows="4" required
                        class="w-full rounded-2xl border-slate-200 bg-slate-50">{{ $review->comment }}</textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" @click="open = false" class="flex-1 px-4 py-3 bg-slate-100 rounded-xl">キャンセル</button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-primary text-white rounded-xl">保存する</button>
                </div>
            </form>
        </div>
    </div>
</template>
