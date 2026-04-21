<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    // お問い合わせフォームを表示
    public function create()
    {
        return view('contact.create');
    }

    // バリデーション → セッションに保存 → 確認画面へ
    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:200'],
            'body'    => ['required', 'string', 'max:2000'],
        ], [
            'name.required'    => 'お名前を入力してください',
            'email.required'   => 'メールアドレスを入力してください',
            'email.email'      => '正しいメールアドレスを入力してください',
            'subject.required' => '件名を入力してください',
            'body.required'    => 'お問い合わせ内容を入力してください',
            'body.max'         => 'お問い合わせ内容は2000文字以内で入力してください',
        ]);

        // セッションに保存して確認画面へリダイレクト
        session()->put('contact', $validated);

        return redirect()->route('contact.confirm');
    }

    // 確認画面を表示
    public function showConfirm()
    {
        // セッションにデータがなければ入力画面へ戻す
        $data = session()->get('contact');
        if (!$data) {
            return redirect()->route('contact.create');
        }

        return view('contact.confirm', compact('data'));
    }

    // セッションのデータを取り出してメール送信
    public function store(Request $request)
    {
        // pull() = 取り出すと同時にセッションから削除（二重送信防止）
        $data = session()->pull('contact');

        // セッションが空なら入力画面へ（二重送信・直接アクセス対策）
        if (!$data) {
            return redirect()->route('contact.create')
                ->with('error', 'セッションが切れました。もう一度入力してください。');
        }

        Mail::to(config('mail.from.address'))
            ->send(new ContactMail($data));

        return redirect()->route('contact.create')
            ->with('success', 'お問い合わせを受け付けました。内容を確認後、ご返信いたします。');
    }
}
