<?php

namespace App\Http\Controllers;

use App\Models\Content;

class MyPageController extends Controller
{
    // 自分の内容一覧
    public function contents()
    {
        $contents = Content::where('user_id', auth()->id())
            ->with(['item.theme', 'sentences'])
            ->latest()
            ->get();

        return view('mypage.contents', ['contents' => $contents]);
    }
}
