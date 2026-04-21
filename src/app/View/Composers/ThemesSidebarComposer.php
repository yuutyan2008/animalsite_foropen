<?php

namespace App\View\Composers;

use App\Models\Theme;
use Illuminate\View\View;

// 【View Composer】
// サイドバーパーシャルが @include で読み込まれるたびに、
// Laravel が自動でこのクラスの compose() メソッドを呼ぶ。
// コントローラー側でデータを渡す必要がなくなる。
class ThemesSidebarComposer
{
    // $view はこれから描画しようとしているビュー（サイドバーパーシャル）を表す。
    // $view->with(...) でそのビューに変数を渡せる。
    public function compose(View $view): void
    {
        $view->with('sidebarThemes', Theme::with([
            'items' => fn($q) => $q->orderBy('sort_order'),
        ])->orderBy('sort_order')->get());
    }
}
