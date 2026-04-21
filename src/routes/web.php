<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailUpdateController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\AnimalWelfareCsvImportController;
use App\Http\Controllers\Admin\AnimalWelfareCsvExportController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\DemoLoginController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\PostApprovalController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ReferenceNeededController;
use App\Http\Controllers\AccountDeletionController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\ContactController;
use App\Http\Middleware\AdminMiddleware;

// ==========================================
// 公開ページ（ログイン不要・誰でも見れる）
// ==========================================

// Googleログイン
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

// デモログイン（採用担当者向け）
Route::get('/demo-login/{type}', [DemoLoginController::class, 'login'])->name('demo.login');

// ホーム
Route::get('/', [ThemeController::class, 'home'])->name('home');

// テーマ詳細
Route::get('/themes/{theme}', [ThemeController::class, 'show'])->name('animal_welfare.show');


Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/terms', 'terms')->name('terms');
Route::view('/posting-guide', 'posting_guide')->name('posting_guide');

// お問い合わせ
Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact/confirm', [ContactController::class, 'confirm'])->name('contact.confirm');
Route::get('/contact/confirm', [ContactController::class, 'showConfirm'])->name('contact.show_confirm');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// ==========================================
// ログインユーザエリア
// ==========================================
Route::middleware(['auth'])->group(function () {

    // マイページ
    Route::get('/my/contents', [MyPageController::class, 'contents'])->name('my.contents');

    // 退会
    Route::get('/account/delete', [AccountDeletionController::class, 'confirm'])->name('account.confirm');
    Route::delete('/account', [AccountDeletionController::class, 'destroy'])->name('account.destroy');

    // テーマ・項目の違反報告
    Route::get('/themes/{theme}/report', [ReportController::class, 'createTheme'])->name('animal_welfare.report.create');
    Route::post('/themes/{theme}/report', [ReportController::class, 'storeTheme'])->name('animal_welfare.report');
    Route::get('/items/{item}/report', [ReportController::class, 'createItem'])->name('items.report.create');
    Route::post('/items/{item}/report', [ReportController::class, 'storeItem'])->name('items.report');

    // 編集画面
    Route::get('/editor', [ThemeController::class, 'edit'])->name('animal_welfare.edit');

    // 項目の新規追加（承認制）
    Route::get('/animal-welfare/create/item', [ItemController::class, 'createPage'])->name('animal_welfare.items.create');
    Route::post('/animal-welfare/items/confirm', [ItemController::class, 'confirm'])->name('animal_welfare.items.confirm');
    Route::get('/animal-welfare/items/confirm', [ItemController::class, 'confirmShow'])->name('animal_welfare.items.confirm.show');
    Route::post('/animal-welfare/items', [ItemController::class, 'store'])->name('animal_welfare.items.store');

    // 項目内容の追加・報告
    Route::get('/items/{item}/contents/create', [ContentController::class, 'create'])->name('items.contents.create');
    Route::post('/items/{item}/contents/confirm', [ContentController::class, 'confirm'])->name('items.contents.confirm');
    Route::get('/items/{item}/contents/confirm', [ContentController::class, 'confirmShow'])->name('items.contents.confirm.show');
    Route::post('/items/{item}/contents', [ContentController::class, 'store'])->name('items.contents.store');
    Route::get('/contents/{content}/edit', [ContentController::class, 'edit'])->name('items.contents.edit');
    Route::patch('/contents/{content}', [ContentController::class, 'update'])->name('items.contents.update');
    Route::delete('/contents/{content}', [ContentController::class, 'destroy'])->name('items.contents.destroy');
    Route::get('/contents/{content}/report', [ReportController::class, 'create'])->name('contents.report.create');
    Route::post('/contents/{content}/report', [ReportController::class, 'store'])->name('contents.report');
    Route::get('/fetch-title', [ContentController::class, 'fetchTitle'])->name('fetch_title');
});

// ==========================================
//  管理者専用エリア (role=1 の人のみ)
// ==========================================
Route::middleware(['auth', AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // 全てのユーザー
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/ban', [AdminUserController::class, 'ban'])->name('users.ban');
        Route::patch('/users/{user}/unban', [AdminUserController::class, 'unban'])->name('users.unban');

        // 投稿内容 承認管理
        Route::get('/contents', [PostApprovalController::class, 'index'])->name('pending_posts.index');
        // 内容（Content）の承認管理
        Route::patch('/contents/{content}/approve', [PostApprovalController::class, 'approve'])->name('pending_posts.approve');
        Route::patch('/contents/{content}/reject', [PostApprovalController::class, 'reject'])->name('pending_posts.reject');
        Route::patch('/contents/{content}/undo-reject', [PostApprovalController::class, 'undoReject'])->name('pending_posts.undo_reject');
        // 復元（ソフトデリート済みレコードを対象）→ 違反報告からの削除に使用
        Route::post('/contents/{id}/restore', [PostApprovalController::class, 'restore'])->name('pending_posts.restore');

        // 項目（Item）の承認管理
        Route::patch('/items/{item}/approve', [PostApprovalController::class, 'approveItem'])->name('pending_posts.items.approve');
        Route::patch('/items/{item}/reject', [PostApprovalController::class, 'rejectItem'])->name('pending_posts.items.reject');
        Route::patch('/items/{item}/undo-reject', [PostApprovalController::class, 'undoRejectItem'])->name('pending_posts.items.undo_reject');

        // テーマ・項目の内容作成・修正・並び替え（管理者のみ）
        Route::get('/animal-welfare/create/theme', [ThemeController::class, 'createThemePage'])->name('animal_welfare.create.theme');
        Route::post('/themes', [ThemeController::class, 'store'])->name('animal_welfare.themes.store');
        Route::patch('/themes/{theme}', [ThemeController::class, 'update'])->name('animal_welfare.themes.update');
        Route::delete('/themes/{theme}', [ThemeController::class, 'destroy'])->name('animal_welfare.themes.destroy');
        Route::post('/themes/reorder', [ThemeController::class, 'reorder'])->name('animal_welfare.themes.reorder');
        Route::get('/animal-welfare/reorder', [ThemeController::class, 'reorderPage'])->name('animal_welfare.reorder');
        Route::post('/items/quick-store', [ItemController::class, 'quickStore'])->name('items.quick_store');
        Route::post('/items/reorder', [ItemController::class, 'reorder'])->name('items.reorder');
        Route::patch('/items/update-all', [ItemController::class, 'updateAll'])->name('items.updateAll');
        Route::patch('/items/{item}', [ItemController::class, 'update'])->name('items.update');
        Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
        Route::get('/animal-welfare/edit', [ThemeController::class, 'editPage'])->name('animal_welfare.edit');

        // カテゴリ管理（管理者のみ）
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::post('/categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::get('/animal-welfare/edit/theme', [ThemeController::class, 'editThemePage'])->name('animal_welfare.edit.theme');
        Route::get('/animal-welfare/edit/item', [ThemeController::class, 'editItemPage'])->name('animal_welfare.items.edit');
        Route::get('/animal-welfare/edit/content', [ThemeController::class, 'editContentSelectPage'])->name('animal_welfare.edit.content');

        // 削除済み一覧
        Route::get('/history/deleted', [HistoryController::class, 'deleted'])->name('history.deleted');

        // 履歴・ロールバック・復元
        Route::get('/contents/{id}/history', [ContentController::class, 'history'])->name('contents.history');
        Route::post('/contents/{content}/history/{history}/rollback', [ContentController::class, 'rollback'])->name('contents.rollback');
        Route::get('/items/{id}/history', [ItemController::class, 'history'])->name('items.history');
        Route::post('/items/{item}/history/{history}/rollback', [ItemController::class, 'rollback'])->name('items.rollback');
        Route::post('/items/{id}/restore', [ItemController::class, 'restore'])->name('items.restore');

        // 動物愛護管理
        Route::get('/social-issues/reports', [AdminReportController::class, 'index'])->name('social_issues.reports.index');
        // 掲載継続：報告ユーザーに理由をメール通知し対応済みにする
        Route::post('/social-issues/reports/{report}/keep', [AdminReportController::class, 'keep'])->name('social_issues.reports.keep');
        // 削除：投稿ユーザー・報告ユーザー両方にメール通知してソフトデリート
        Route::post('/social-issues/reports/{report}/delete', [AdminReportController::class, 'deleteContent'])->name('social_issues.reports.delete');
        // 誤操作時の対応済み取り消し：未対応に戻す
        Route::patch('/social-issues/reports/{report}/unresolve', [AdminReportController::class, 'unresolve'])->name('social_issues.reports.unresolve');
        Route::get('/animal-welfare/import-csv', [AnimalWelfareCsvImportController::class, 'create'])->name('animal_welfare.import_csv');
        Route::post('/animal-welfare/import-csv', [AnimalWelfareCsvImportController::class, 'store'])->name('animal_welfare.import_csv.store');
        Route::get('/animal-welfare/export-csv', [AnimalWelfareCsvExportController::class, 'export'])->name('animal_welfare.export_csv');

        // 要出典リスト
        Route::get('/reference-needed', [ReferenceNeededController::class, 'index'])->name('reference_needed.index');
        Route::post('/reference-needed/{content}', [ReferenceNeededController::class, 'store'])->name('reference_needed.store');
        Route::delete('/reference-needed/{referenceNeeded}', [ReferenceNeededController::class, 'destroy'])->name('reference_needed.destroy');
        Route::post('/reference-needed/{referenceNeeded}/send-mail', [ReferenceNeededController::class, 'sendMail'])->name('reference_needed.send_mail');
        Route::post('/reference-needed/{referenceNeeded}/append-question-mark', [ReferenceNeededController::class, 'appendQuestionMark'])->name('reference_needed.append_question_mark');
    });

// 認証関連のルート（ログイン・登録など）
require __DIR__ . '/auth.php';
