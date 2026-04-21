<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Solvedience')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('site.webmanifest') }}" />
    <meta name="description" content="@yield('description', '横浜市内の動物病院・保護猫カフェ・譲渡施設を検索できるサイトです。犬・猫・小動物など診療動物別に検索できます。')">
    <link rel="canonical" href="{{ url()->current() }}" />
    {{-- Google Fonts & Material Icons --}}
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    {{-- Tailwind CSS (CDN) --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#0ea5e9",
                        "fairytale-blue": "#e0f2fe"
                    },
                    fontFamily: {
                        display: ["Noto Sans JP", "sans-serif"]
                    },
                    borderRadius: {
                        DEFAULT: "0.75rem",
                        '3xl': '2.5rem'
                    },
                },
            },
        };
    </script>
    <style type="text/tailwindcss">
        body { font-family: 'Noto Sans JP', sans-serif; }
    </style>
    {{-- Alpine.js が読み込まれるまでの間に一瞬モーダルが見えてしまうのを防ぐ --}}
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-white dark:bg-slate-900 min-h-screen">
    {{-- x-flash-message という書き方は components フォルダにある場合に使えます --}}
    <x-flash-message />

    {{-- ナビゲーションバー --}}
    <nav class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 shadow-sm">
        <div x-data="{ mobileOpen: false }" class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="h-16 flex items-center justify-between">

                {{-- ロゴ部分 --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-xl text-primary shrink-0">
                    <ruby>Solvedience<rt class="text-xs font-normal">ソルベディエンス</rt></ruby>
                </a>

                {{-- ユーザーアクション --}}
                <div class="flex items-center gap-4">
                    @auth

                    {{-- ログイン中：ドロップダウンメニュー --}}
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        {{-- クリックする部分（名前） --}}
                        <button @click="open = !open" class="flex items-center gap-1 text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-primary transition focus:outline-none">
                            {{ auth()->user()->name }}
                            <svg class="w-4 h-4 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        {{-- ドロップダウンの中身 --}}
                        <div x-show="open"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            class="absolute right-0 mt-2 w-56 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-xl z-50 py-2"
                            style="display: none;">
                            {{-- ★管理者専用メニュー --}}
                            @if(auth()->user()->role === \App\Models\User::ROLE_ADMIN)
                            <div class="px-4 py-2 text-xs font-bold text-slate-400 uppercase tracking-wider">管理者メニュー</div>

                            {{-- 動物愛護 --}}
                            <div x-data="{ subOpen: true }">
                                <button @click="subOpen = !subOpen"
                                    class="w-full flex items-center justify-between px-4 py-2 text-sm text-green-600 font-bold hover:bg-slate-50 dark:hover:bg-slate-700">
                                    <span>動物愛護</span>
                                    <svg class="w-3 h-3 transition-transform" :class="{'rotate-90': subOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                                <div x-show="subOpen" x-cloak class="bg-slate-50 border-l-2 border-green-500 ml-4">
                                    <a href="{{ route('admin.animal_welfare.import_csv') }}" class="block px-4 py-2 text-xs text-slate-700 hover:text-green-600 hover:bg-white">CSVインポート</a>
                                    <a href="{{ route('admin.social_issues.reports.index') }}" class="block px-4 py-2 text-xs text-slate-700 hover:text-green-600 hover:bg-white">違反報告一覧</a>
                                    <a href="{{ route('admin.pending_posts.index') }}" class="block px-4 py-2 text-xs text-slate-700 hover:text-green-600 hover:bg-white">投稿内容 承認管理</a>
                                    <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-xs text-slate-700 hover:text-green-600 hover:bg-white">全てのユーザー</a>
                                    <a href="{{ route('admin.reference_needed.index') }}" class="block px-4 py-2 text-xs text-slate-700 hover:text-green-600 hover:bg-white">要出典リスト</a>
                                </div>
                            </div>

                            <div class="border-t border-slate-100 dark:border-slate-700 my-1"></div>
                            @endif
                            <div class="border-t border-slate-100 dark:border-slate-700 my-1"></div>

                            {{-- マイページ --}}
                            <a href="{{ route('my.contents') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700">投稿した内容</a>
                            <a href="{{ route('animal_welfare.edit') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700">編集画面</a>

                            <div class="border-t border-slate-100 dark:border-slate-700 my-1"></div>

                            {{-- ログアウト --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-500 font-bold hover:bg-red-50 dark:hover:bg-slate-700">
                                    ログアウト
                                </button>
                            </form>

                            <div class="border-t border-slate-100 dark:border-slate-700 my-1"></div>

                            {{-- 退会（目立たないよう黒文字で最下部に配置） --}}
                            <a href="{{ route('account.confirm') }}" class="block px-4 py-2 text-sm text-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700">
                                退会する
                            </a>
                        </div>
                    </div>
                    @endauth

                    @guest
                    {{-- 未ログイン：ログインボタンのみ（新規登録はGoogleログインに統一） --}}
                    <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-primary">
                        ログイン
                    </a>
                    @endguest

                    {{-- モバイル用ハンバーガー --}}
                    <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 text-slate-500 hover:text-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- モバイルメニュー --}}
            <div x-show="mobileOpen" x-cloak class="md:hidden border-t border-slate-100 py-3 space-y-1">
                <a href="{{ route('home') }}" class="block px-4 py-2 text-sm font-bold text-slate-700 dark:text-slate-300 hover:text-primary">愛玩動物の社会問題</a>
            </div>
        </div>
    </nav>

    {{-- メインコンテンツ --}}
    <main class="p-4 md:p-8 max-w-7xl mx-auto">
        {{-- 各ページの中身 --}}
        @yield('content')
    </main>
    {{-- フッター --}}
    <footer class="border-t border-slate-200 dark:border-slate-700 mt-16 py-8 text-center text-xs text-slate-400 space-y-2">
        <div class="flex justify-center gap-6">
            <a href="{{ route('privacy') }}" class="hover:text-primary transition">プライバシーポリシー</a>
            <a href="{{ route('terms') }}" class="hover:text-primary transition">利用規約</a>
            <a href="{{ route('contact.create') }}" class="hover:text-primary transition">お問い合わせ</a>
        </div>
        <p>© {{ date('Y') }} Solvedience. All rights reserved.</p>
    </footer>

    {{-- 処理中オーバーレイ --}}
    <div id="loadingOverlay" class="fixed inset-0 z-[200] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm hidden">
        <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-2xl flex flex-col items-center gap-4">
            {{-- ぐるぐる回るアニメーションアイコン --}}
            <div class="w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-lg font-bold text-slate-700 dark:text-slate-200">送信中です...</p>
            <p class="text-sm text-slate-500 italic">そのまま少々お待ちください</p>
        </div>
    </div>

    <script>
        // すべてのformのsubmitでローディングオーバーレイを表示する
        // ここでsubmitイベントを受け取り、オーバーレイを表示するかどうかを判断している
        document.addEventListener('submit', function(e) {
            const form = e.target;

            // onsubmit="return confirm(...)" でキャンセルした場合、
            // return false が e.preventDefault() と同義になり defaultPrevented が true になる。
            // submit イベント自体はキャンセル後もバブリングで document まで届くため、
            // ここで確認しないとキャンセル後もオーバーレイが表示されてしまう。
            if (e.defaultPrevented) return;

            // data-no-loading 属性を持つフォームはオーバーレイを表示しない
            if (form.hasAttribute('data-no-loading')) return;

            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.classList.remove('hidden');
            }
        });
    </script>
</body>


</html>
