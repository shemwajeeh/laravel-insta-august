<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCaptionTranslation;
use App\Services\CaptionTranslateService;
use Illuminate\Http\Request;

class PostCaptionTranslateController extends Controller
{
    public function __construct(private CaptionTranslateService $svc) {}

    public function show(Request $request, Post $post)
    {
        // 必要なら公開範囲チェック
        // $this->authorize('view', $post);

        $target = strtolower($request->query('lang', 'en'));

        // キャッシュ確認
        $cached = PostCaptionTranslation::where('post_id', $post->id)
            ->where('lang', $target)
            ->first();

        if ($cached) {
            return response()->json([
                'lang'   => $cached->lang,
                'text'   => $cached->text,
                'cached' => true,
            ]);
        }

        $source = trim((string) ($post->caption ?? ''));
        if ($source === '') {
            return response()->json([
                'lang'   => $target,
                'text'   => '',
                'cached' => false,
            ]);
        }

        // DeepL言語コードマップ
        $map = ['en' => 'EN', 'ja' => 'JA', 'ko' => 'KO', 'zh' => 'ZH', 'fr' => 'FR', 'es' => 'ES'];
        $deeplLang = $map[$target] ?? strtoupper($target);

        // 翻訳
        $translated = $this->svc->translate($source, $deeplLang);

        // 保存
        $row = PostCaptionTranslation::create([
            'post_id' => $post->id,
            'lang'    => $target,
            'text'    => $translated,
        ]);

        return response()->json([
            'lang'   => $row->lang,
            'text'   => $row->text,
            'cached' => false,
        ]);
    }
}
