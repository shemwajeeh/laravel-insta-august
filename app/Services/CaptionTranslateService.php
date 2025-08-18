<?php

namespace App\Services;

use GuzzleHttp\Client;

class CaptionTranslateService
{
    public function __construct(
        private ?string $apiKey = null,
        private ?string $apiUrl = null,
        private ?Client $http = null,
    ) {
        $this->apiKey = $this->apiKey ?? config('services.deepl.key');
        $this->apiUrl = $this->apiUrl ?? config('services.deepl.url', 'https://api-free.deepl.com/v2/translate');
        $this->http   = $this->http   ?? new Client(['timeout' => 15]);
    }

    /**
     * @param string $text 元キャプション
     * @param string $targetLang DeepLの言語コード（EN/JA/KO/…）
     */
    public function translate(string $text, string $targetLang): string
    {
        $resp = $this->http->post($this->apiUrl, [
            'headers' => ['Authorization' => 'DeepL-Auth-Key ' . $this->apiKey],
            'form_params' => [
                'text'        => $text,
                'target_lang' => strtoupper($targetLang),
            ],
        ]);

        $data = json_decode((string) $resp->getBody(), true);
        return $data['translations'][0]['text'] ?? '';
    }
}
