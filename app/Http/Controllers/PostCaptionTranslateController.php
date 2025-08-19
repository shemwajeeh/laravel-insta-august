<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCaptionTranslation;
use App\Services\CaptionTranslateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostCaptionTranslateController extends Controller
{
    public function __construct(private CaptionTranslateService $svc) {}

    public function show(Request $request, Post $post)
    {
        try {
            // 取得する言語（デフォルトは en-us）
            $req = strtolower($request->query('lang', 'en-us'));

            // DeepLが受け付けるターゲット言語一覧（config/translate.php）
            $supported = array_map('strtolower', config('translate.deepl_supported', []));

            // DeepL非対応のコードは 400 を返す（UIでは disabled だが直叩き対策）
            if (!in_array($req, $supported, true)) {
                return response()->json([
                    'error'   => 'UNSUPPORTED_LANG',
                    'message' => 'Language not supported by DeepL: '.$req,
                ], 400);
            }

            // キャッシュ（post_id + lang は一意）
            $cached = PostCaptionTranslation::where('post_id', $post->id)
                ->where('lang', $req)   // DBには小文字で保存
                ->first();

            if ($cached) {
                return response()->json([
                    'lang'   => $cached->lang,
                    'text'   => $cached->text,
                    'cached' => true,
                ], 200);
            }

            // 翻訳元（caption が無ければ description を使う）
            $source = trim((string) ($post->caption ?? $post->description ?? ''));
            if ($source === '') {
                return response()->json([
                    'lang'   => $req,
                    'text'   => '',
                    'cached' => false,
                ], 200);
            }

            // DeepLに渡す形式（大文字・ハイフン）
            $deeplLang = strtoupper($req);

            // 翻訳呼び出し（DeepL）
            $translated = $this->svc->translate($source, $deeplLang);

            // 保存して返す
            $row = PostCaptionTranslation::create([
                'post_id' => $post->id,
                'lang'    => $req,     // 小文字で保存（例: en-us, zh-hans）
                'text'    => $translated,
            ]);

            return response()->json([
                'lang'   => $row->lang,
                'text'   => $row->text,
                'cached' => false,
            ], 200);

        } catch (\Throwable $e) {
            Log::error('Translate endpoint failed', [
                'post_id' => $post->id,
                'error'   => $e->getMessage(),
            ]);

            // 例外は必ずJSONで返す（フロントで表示できるように）
            return response()->json([
                'error'   => 'TRANSLATION_BACKEND_ERROR',
                'message' => $e->getMessage(),
            ], 502);
        }
    }
}
