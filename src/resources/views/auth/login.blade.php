<x-guest-layout>
    <div class="min-h-screen w-full flex flex-col items-center justify-center bg-blue-50">

        <div class="w-full sm:max-w-md px-8 py-10 bg-white shadow-xl rounded-2xl text-center">
            <h1 class="text-xl font-bold text-slate-800 mb-2">ログイン</h1>
            <p class="text-sm text-slate-500 mb-8">Googleアカウントでログインしてください</p>

            @if(session('error'))
                <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <a href="{{ route('auth.google') }}"
                class="flex items-center justify-center gap-3 w-full border border-gray-300 rounded-lg py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Googleでログイン
            </a>

            <!-- 採用担当者向けデモアカウント -->
            <div class="mt-8 border border-gray-200 rounded-xl overflow-hidden text-left">
                <div class="bg-blue-50 px-4 py-3 border-b border-gray-200">
                    <h3 class="font-bold text-blue-800 text-sm">採用担当者様向けデモアカウント</h3>
                    <p class="text-xs text-blue-600 mt-0.5">ワンクリックでログインできます</p>
                </div>

                <!-- 管理者アカウント -->
                <div class="border-b border-gray-200">
                    <div class="bg-gray-50 px-4 py-2">
                        <span class="text-xs font-bold text-gray-600">【管理者】</span>
                    </div>
                    <div class="px-4 py-3 flex items-center justify-between">
                        <div class="text-xs text-gray-600 space-y-0.5">
                            <p>投稿の承認・ユーザー管理などが操作できます</p>
                        </div>
                        <a href="{{ route('demo.login', 'admin') }}"
                            class="ml-4 shrink-0 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold py-2 px-4 rounded-lg transition-colors">
                            ログイン
                        </a>
                    </div>
                </div>

                <!-- 一般ユーザーアカウント -->
                <div>
                    <div class="bg-gray-50 px-4 py-2">
                        <span class="text-xs font-bold text-gray-600">【一般ユーザー】</span>
                    </div>
                    <div class="px-4 py-3 flex items-center justify-between">
                        <div class="text-xs text-gray-600 space-y-0.5">
                            <p>投稿・編集・違反報告などが操作できます</p>
                        </div>
                        <a href="{{ route('demo.login', 'user') }}"
                            class="ml-4 shrink-0 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold py-2 px-4 rounded-lg transition-colors">
                            ログイン
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-guest-layout>
