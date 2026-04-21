<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DemoLoginController extends Controller
{
    public function login(string $type)
    {
        $email = match ($type) {
            'admin' => 'demo-admin@example.com',
            'user'  => 'demo-user@example.com',
            default => null,
        };

        if (!$email) {
            return redirect()->route('login');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'デモアカウントが見つかりません。管理者にお問い合わせください。');
        }

        Auth::login($user, remember: true);

        return redirect()->intended(route('animal_welfare.edit'));
    }
}
