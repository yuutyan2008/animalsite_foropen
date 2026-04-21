<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnimalWelfareSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // 既存データを削除（外部キー制約のため子テーブルから順に）
        // content_sentences / reference_needed は contents の cascade で削除される
        // item_histories は items の cascade で削除される
        // content_histories は contents の cascade で削除される
        // ============================================================
        DB::table('contents')->delete();
        DB::table('items')->delete();
        DB::table('categories')->delete();
        DB::table('themes')->delete();

        $now = now();

        // ============================================================
        // テーマ
        // ============================================================
        DB::table('themes')->insert([
            ['id' => 1,  'user_id' => 1, 'name' => '地域猫活動、TNR',                                        'sort_order' => 2,  'created_at' => $now, 'updated_at' => $now],
            ['id' => 2,  'user_id' => 1, 'name' => '犬猫の殺処分、団体による引き出しや譲渡活動',            'sort_order' => 1,  'created_at' => $now, 'updated_at' => $now],
            ['id' => 3,  'user_id' => 1, 'name' => '捨て犬・捨て猫',                                        'sort_order' => 3,  'created_at' => $now, 'updated_at' => $now],
            ['id' => 4,  'user_id' => 1, 'name' => '外猫、外犬への餌やり',                                  'sort_order' => 4,  'created_at' => $now, 'updated_at' => $now],
            ['id' => 5,  'user_id' => 1, 'name' => '動物愛護法違反',                                        'sort_order' => 5,  'created_at' => $now, 'updated_at' => $now],
            ['id' => 6,  'user_id' => 1, 'name' => '多頭飼育問題',                                          'sort_order' => 6,  'created_at' => $now, 'updated_at' => $now],
            ['id' => 7,  'user_id' => 1, 'name' => '外犬、外猫への虐待',                                    'sort_order' => 7,  'created_at' => $now, 'updated_at' => $now],
            ['id' => 8,  'user_id' => 1, 'name' => '法令違反ブリーダー',                                    'sort_order' => 8,  'created_at' => $now, 'updated_at' => $now],
            ['id' => 9,  'user_id' => 1, 'name' => '生後56日までの犬猫販売',                                'sort_order' => 9,  'created_at' => $now, 'updated_at' => $now],
            ['id' => 10, 'user_id' => 1, 'name' => '動物を虐待する人は人に対しても残虐な行為をするようになる？', 'sort_order' => 10, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 11, 'user_id' => 1, 'name' => '保護犬、保護猫の里親詐欺',                              'sort_order' => 11, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 12, 'user_id' => 1, 'name' => 'youtubeなどの偽の動物救助動画',                         'sort_order' => 12, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 13, 'user_id' => 1, 'name' => '動物を愛する人の心を理解し共感する',                    'sort_order' => 13, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 14, 'user_id' => 1, 'name' => '動物が苦手な人の心を理解する',                          'sort_order' => 14, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // ============================================================
        // カテゴリ（CSV実データに合わせたID）
        // ============================================================
        DB::table('categories')->insert([
            // テーマ1
            ['id' => 1,  'theme_id' => 1,  'name' => '概念・定義',    'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2,  'theme_id' => 1,  'name' => '背景・歴史',    'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3,  'theme_id' => 1,  'name' => '現状・データ',  'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4,  'theme_id' => 1,  'name' => '課題・対策',    'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6,  'theme_id' => 1,  'name' => '各立場の意見',  'sort_order' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7,  'theme_id' => 1,  'name' => '考察・疑問',    'sort_order' => 7, 'created_at' => $now, 'updated_at' => $now],
            // テーマ2
            ['id' => 9,  'theme_id' => 2,  'name' => '背景・歴史',    'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 10, 'theme_id' => 2,  'name' => '現状・データ',  'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 11, 'theme_id' => 2,  'name' => '課題・対策',    'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 13, 'theme_id' => 2,  'name' => '各立場の意見',  'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 14, 'theme_id' => 2,  'name' => '考察・疑問',    'sort_order' => 5, 'created_at' => $now, 'updated_at' => $now],
            // テーマ3
            ['id' => 15, 'theme_id' => 3,  'name' => '概念・定義',    'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 16, 'theme_id' => 3,  'name' => '背景・歴史',    'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 17, 'theme_id' => 3,  'name' => '現状・データ',  'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 18, 'theme_id' => 3,  'name' => '課題・対策',    'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 20, 'theme_id' => 3,  'name' => '各立場の意見',  'sort_order' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 21, 'theme_id' => 3,  'name' => '考察・疑問',    'sort_order' => 7, 'created_at' => $now, 'updated_at' => $now],
            // テーマ4
            ['id' => 22, 'theme_id' => 4,  'name' => '概念・定義',    'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 23, 'theme_id' => 4,  'name' => '背景・歴史',    'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 24, 'theme_id' => 4,  'name' => '現状・データ',  'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 25, 'theme_id' => 4,  'name' => '課題・対策',    'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 27, 'theme_id' => 4,  'name' => '各立場の意見',  'sort_order' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 28, 'theme_id' => 4,  'name' => '考察・疑問',    'sort_order' => 7, 'created_at' => $now, 'updated_at' => $now],
            // テーマ5
            ['id' => 29, 'theme_id' => 5,  'name' => '概念・定義',    'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 30, 'theme_id' => 5,  'name' => '背景・歴史',    'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 31, 'theme_id' => 5,  'name' => '現状・データ',  'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 32, 'theme_id' => 5,  'name' => '課題・対策',    'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 34, 'theme_id' => 5,  'name' => '各立場の意見',  'sort_order' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 35, 'theme_id' => 5,  'name' => '考察・疑問',    'sort_order' => 7, 'created_at' => $now, 'updated_at' => $now],
            // テーマ6
            ['id' => 36, 'theme_id' => 6,  'name' => '概念・定義',    'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 37, 'theme_id' => 6,  'name' => '背景・歴史',    'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 38, 'theme_id' => 6,  'name' => '現状・データ',  'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 39, 'theme_id' => 6,  'name' => '課題・対策',    'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 41, 'theme_id' => 6,  'name' => '各立場の意見',  'sort_order' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 42, 'theme_id' => 6,  'name' => '考察・疑問',    'sort_order' => 7, 'created_at' => $now, 'updated_at' => $now],
            // テーマ7
            ['id' => 43, 'theme_id' => 7,  'name' => '概念・定義',    'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 44, 'theme_id' => 7,  'name' => '背景・歴史',    'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 45, 'theme_id' => 7,  'name' => '現状・データ',  'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 46, 'theme_id' => 7,  'name' => '課題・対策',    'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 48, 'theme_id' => 7,  'name' => '各立場の意見',  'sort_order' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 49, 'theme_id' => 7,  'name' => '考察・疑問',    'sort_order' => 7, 'created_at' => $now, 'updated_at' => $now],
            // テーマ8
            ['id' => 50, 'theme_id' => 8,  'name' => '概念・定義',    'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 51, 'theme_id' => 8,  'name' => '背景・歴史',    'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 52, 'theme_id' => 8,  'name' => '現状・データ',  'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 53, 'theme_id' => 8,  'name' => '課題・対策',    'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 55, 'theme_id' => 8,  'name' => '各立場の意見',  'sort_order' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 56, 'theme_id' => 8,  'name' => '考察・疑問',    'sort_order' => 7, 'created_at' => $now, 'updated_at' => $now],
            // テーマ9
            ['id' => 57, 'theme_id' => 9,  'name' => '概念・定義',    'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 58, 'theme_id' => 9,  'name' => '背景・歴史',    'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 59, 'theme_id' => 9,  'name' => '現状・データ',  'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 60, 'theme_id' => 9,  'name' => '課題・対策',    'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 62, 'theme_id' => 9,  'name' => '各立場の意見',  'sort_order' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 63, 'theme_id' => 9,  'name' => '考察・疑問',    'sort_order' => 7, 'created_at' => $now, 'updated_at' => $now],
            // テーマ10
            ['id' => 64, 'theme_id' => 10, 'name' => '概念・定義',    'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 65, 'theme_id' => 10, 'name' => '背景・歴史',    'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 66, 'theme_id' => 10, 'name' => '現状・データ',  'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 67, 'theme_id' => 10, 'name' => '課題・対策',    'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 69, 'theme_id' => 10, 'name' => '各立場の意見',  'sort_order' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 70, 'theme_id' => 10, 'name' => '考察・疑問',    'sort_order' => 7, 'created_at' => $now, 'updated_at' => $now],
            // テーマ11
            ['id' => 71, 'theme_id' => 11, 'name' => '概念・定義',    'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 72, 'theme_id' => 11, 'name' => '背景・歴史',    'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 73, 'theme_id' => 11, 'name' => '現状・データ',  'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 74, 'theme_id' => 11, 'name' => '課題・対策',    'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 76, 'theme_id' => 11, 'name' => '各立場の意見',  'sort_order' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 77, 'theme_id' => 11, 'name' => '考察・疑問',    'sort_order' => 7, 'created_at' => $now, 'updated_at' => $now],
            // テーマ12
            ['id' => 78, 'theme_id' => 12, 'name' => '概念・定義',    'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 79, 'theme_id' => 12, 'name' => '背景・歴史',    'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 80, 'theme_id' => 12, 'name' => '現状・データ',  'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 81, 'theme_id' => 12, 'name' => '課題・対策',    'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 83, 'theme_id' => 12, 'name' => '各立場の意見',  'sort_order' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 84, 'theme_id' => 12, 'name' => '考察・疑問',    'sort_order' => 7, 'created_at' => $now, 'updated_at' => $now],
            // テーマ13
            ['id' => 85, 'theme_id' => 13, 'name' => '概念・定義',    'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 86, 'theme_id' => 13, 'name' => '背景・歴史',    'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 87, 'theme_id' => 13, 'name' => '現状・データ',  'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 88, 'theme_id' => 13, 'name' => '課題・対策',    'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 90, 'theme_id' => 13, 'name' => '各立場の意見',  'sort_order' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 91, 'theme_id' => 13, 'name' => '考察・疑問',    'sort_order' => 7, 'created_at' => $now, 'updated_at' => $now],
            // テーマ14
            ['id' => 92, 'theme_id' => 14, 'name' => '概念・定義',    'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 93, 'theme_id' => 14, 'name' => '背景・歴史',    'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 94, 'theme_id' => 14, 'name' => '現状・データ',  'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 95, 'theme_id' => 14, 'name' => '課題・対策',    'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 97, 'theme_id' => 14, 'name' => '各立場の意見',  'sort_order' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 98, 'theme_id' => 14, 'name' => '考察・疑問',    'sort_order' => 7, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // ============================================================
        // 項目
        // ============================================================
        DB::table('items')->insert([
            ['id' => 90002,  'theme_id' => 1, 'category_id' => 1,  'user_id' => 1, 'name' => '地域猫活動、TNRとは',                              'sort_order' => 1,  'status' => 'pending', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 1,      'theme_id' => 2, 'category_id' => 9,  'user_id' => 1, 'name' => '殺処分の歴史背景',                                 'sort_order' => 2,  'status' => 'pending', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2,      'theme_id' => 2, 'category_id' => 10, 'user_id' => 1, 'name' => '殺処分を減らす取り組み',                           'sort_order' => 1,  'status' => 'pending', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4,      'theme_id' => 2, 'category_id' => 11, 'user_id' => 1, 'name' => '殺処分ゼロを継続する難しさ',                       'sort_order' => 3,  'status' => 'pending', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5,      'theme_id' => 2, 'category_id' => 14, 'user_id' => 1, 'name' => 'どうすれば保健所への持ち込みを減らすことができる？', 'sort_order' => 4,  'status' => 'pending', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6,      'theme_id' => 2, 'category_id' => 14, 'user_id' => 1, 'name' => 'ブリーダーは法令違反をしなければ利益が出にくいのでは？', 'sort_order' => 5, 'status' => 'pending', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 60001,  'theme_id' => 2, 'category_id' => 11, 'user_id' => 1, 'name' => '保健所への持ち込み数が、引き出し数を大幅に上回っている', 'sort_order' => 6, 'status' => 'pending', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 90001,  'theme_id' => 2, 'category_id' => 11, 'user_id' => 1, 'name' => '保護団体の飼育崩壊',                               'sort_order' => 7,  'status' => 'pending', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 120001, 'theme_id' => 2, 'category_id' => 14, 'user_id' => 1, 'name' => '避妊去勢手術費用の負担が減ると、引取り数は減る？',   'sort_order' => 8,  'status' => 'pending', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 150001, 'theme_id' => 2, 'category_id' => 11, 'user_id' => 1, 'name' => '犬猫に関わる全ての人ができること',                  'sort_order' => 9,  'status' => 'pending', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 180001, 'theme_id' => 2, 'category_id' => 11, 'user_id' => 1, 'name' => '殺処分回避を民間ボランティアに委ねている現状',       'sort_order' => 10, 'status' => 'pending', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 240001, 'theme_id' => 2, 'category_id' => 10, 'user_id' => 1, 'name' => 'テスト0419',                                       'sort_order' => 11, 'status' => 'pending', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // ============================================================
        // 内容
        // ============================================================
        DB::table('contents')->insert([
            ['id' => 120001, 'item_id' => 90002,  'user_id' => 1, 'title' => '地域猫',                       'status' => 'approved', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 150001, 'item_id' => 90002,  'user_id' => 1, 'title' => null,                           'status' => 'approved', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 180001, 'item_id' => 90002,  'user_id' => 1, 'title' => 'TNRとは（具体的な手順）',       'status' => 'approved', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 180002, 'item_id' => 90002,  'user_id' => 1, 'title' => null,                           'status' => 'approved', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 1,      'item_id' => 1,      'user_id' => 1, 'title' => '狂犬病予防',                    'status' => 'approved', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 30001,  'item_id' => 1,      'user_id' => 1, 'title' => '動物の保護及び管理に関する法律の制定', 'status' => 'approved', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 240003, 'item_id' => 4,      'user_id' => 1, 'title' => '「ゼロ」の先にある問いかけ',    'status' => 'approved', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 300001, 'item_id' => 5,      'user_id' => 1, 'title' => null,                           'status' => 'pending',  'created_at' => $now, 'updated_at' => $now],
            ['id' => 330001, 'item_id' => 60001,  'user_id' => 1, 'title' => null,                           'status' => 'pending',  'created_at' => $now, 'updated_at' => $now],
            ['id' => 330002, 'item_id' => 60001,  'user_id' => 1, 'title' => null,                           'status' => 'approved', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 210001, 'item_id' => 180001, 'user_id' => 1, 'title' => null,                           'status' => 'approved', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 360001, 'item_id' => 240001, 'user_id' => 1, 'title' => 'test0419-001',                 'status' => 'pending',  'created_at' => $now, 'updated_at' => $now],
        ]);

        // ============================================================
        // 文章（content_sentences）
        // ============================================================
        DB::table('content_sentences')->insert([
            [
                'content_id' => 120001,
                'type'       => 'reference',
                'value'      => '野良猫を殺さず、人と共存する取り組み',
                'url'        => 'https://kspca.jp/noraneko/',
                'url_title'  => 'ノラ猫を増やさないために | 公益財団法人 神奈川県動物愛護協会',
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'content_id' => 150001,
                'type'       => 'reference',
                'value'      => '不妊去勢手術を行わず餌やりをする行為は、不幸な命を増やすことにつながる。',
                'url'        => 'https://www.env.go.jp/nature/dobutsu/aigo/project/actionplan.html#inline_content2',
                'url_title'  => '環境省 _ 人と動物が幸せに暮らす社会の実現プロジェクト ｜ アクションプラン',
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'content_id' => 180001,
                'type'       => 'reference',
                'value'      => "Trap（トラップ）：猫を捕獲器で安全に捕まえる。\nNeuter（ニューター）：不妊・去勢手術を行い、耳先をさくらの花びらの形にカットする（「さくら耳」）。\nReturn（リターン）：猫を元の場所に戻す。",
                'url'        => 'https://www.doubutukikin.or.jp/activity/sakuraneko-tnr/',
                'url_title'  => 'さくらねこTNRとは – どうぶつ基金',
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'content_id' => 180002,
                'type'       => 'reference',
                'value'      => "地域猫活動とは（TNR＋管理）\nTNRを行った猫を、地域住民が主体となってエサやトイレの管理、周辺美化を行い、一代限りの命を適切に見守る活動です。\n活動の重要性と効果\n繁殖防止： 猫は1年に複数回、多数の仔猫を産むため、地域の猫全頭に速やかにTNRを行うことが重要。\n環境改善： スプレー行為や発情期の鳴き声が軽減される。\n殺処分減少： 野良猫の数そのものを減らす。",
                'url'        => 'https://www.neco-republic.jp/about/tnr/',
                'url_title'  => null,
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'content_id' => 1,
                'type'       => 'reference',
                'value'      => '狂犬病は発症したら致死率100%の病気であり、1950年に狂犬予防法が制定され、犬の登録、予防注射、鑑札装着の義務化、野犬の捕獲と殺処分が徹底される以前は多くの犬と人の感染が起きていた。法令制定後、狂犬病は7年で根絶される。',
                'url'        => 'https://www.mhlw.go.jp/stf/seisakunitsuite/bunya/kenkou_iryou/kenkou/kekkaku-kansenshou18/kyokenbyou.html',
                'url_title'  => '狂犬病｜厚生労働省',
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'content_id' => 30001,
                'type'       => 'opinion',
                'value'      => '狂犬病の根絶が継続するなか、動物愛護意識の高まりを受けて',
                'url'        => null,
                'url_title'  => 'e-Gov 法令検索',
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'content_id' => 30001,
                'type'       => 'reference',
                'value'      => '1973年、「動物の保護及び管理に関する法律」が制定され、ペットの適性飼育に加え、殺処分については第35条に「殺処分がなくなることを目指して、所有者がいると推測されるものについてはその所有者を発見し、当該所有者に返還するよう努めるとともに、所有者がいないと推測されるもの、所有者から引取りを求められたもの又は所有者の発見ができないものについてはその飼養を希望する者を募集し、当該希望する者に譲り渡すよう努めるものとする。」と明記される。',
                'url'        => 'https://laws.e-gov.go.jp/law/348AC1000000105/',
                'url_title'  => 'e-Gov 法令検索',
                'sort_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'content_id' => 240003,
                'type'       => 'opinion',
                'value'      => "殺処分ゼロの達成は、確かに重要なマイルストーンだ。しかし「継続」には、単なる数字の管理を超えた、社会全体の構造変革が求められる。ボランティアへの依存から公的制度への移行、飼育文化の底上げ、多頭飼育崩壊への予防的介入、そして「譲渡困難な個体」をいかに社会で支えるか——これらの課題を解決しない限り、殺処分ゼロは砂上の楼閣になりうる。\n事業者の無計画な乱繁殖、適正な行政指導が行われないままの営業、一般飼養者が不妊去勢手術をしないまま多頭飼育に陥るケース、一般消費者の衝動買い——これらは社会全体の問題として考えていく必要がある。",
                'url'        => null,
                'url_title'  => null,
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'content_id' => 300001,
                'type'       => 'opinion',
                'value'      => '動作確認てすと',
                'url'        => null,
                'url_title'  => null,
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'content_id' => 330001,
                'type'       => 'opinion',
                'value'      => 'テスト',
                'url'        => null,
                'url_title'  => null,
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'content_id' => 330002,
                'type'       => 'opinion',
                'value'      => 'test4/13',
                'url'        => null,
                'url_title'  => null,
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'content_id' => 210001,
                'type'       => 'reference',
                'value'      => '保健所から犬猫を引き出す団体への自治体からの不妊去勢手術などへの助成金は無く、',
                'url'        => 'https://www.env.go.jp/nature/dobutsu/aigo/2_data/statistics/files/r05/teiyo_r05.pdf',
                'url_title'  => null,
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'content_id' => 210001,
                'type'       => 'opinion',
                'value'      => '個人や企業からの寄付を受けて運営を続けるケースが多い。',
                'url'        => null,
                'url_title'  => null,
                'sort_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'content_id' => 360001,
                'type'       => 'reference',
                'value'      => 'test0419-001',
                'url'        => 'https://www.env.go.jp/nature/dobutsu/aigo/2_data/statistics/dog-cat.html',
                'url_title'  => null,
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
