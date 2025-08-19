<?php
// config/translate.php
return [
    // UIに出す候補（ここに Cebuano / Filipino も含める）
    'targets' => [
        'ar' => 'Arabic',
        'bg' => 'Bulgarian',
        'cs' => 'Czech',
        'da' => 'Danish',
        'de' => 'German',
        'el' => 'Greek',
        'en-GB' => 'English (UK)',
        'en-US' => 'English (US)',
        'es' => 'Spanish',
        'es-419' => 'Spanish (LatAm)',
        'et' => 'Estonian',
        'fi' => 'Finnish',
        'fr' => 'French',
        'he' => 'Hebrew',
        'hu' => 'Hungarian',
        'id' => 'Indonesian',
        'it' => 'Italian',
        'ja' => 'Japanese',
        'ko' => 'Korean',
        'lt' => 'Lithuanian',
        'lv' => 'Latvian',
        'nb' => 'Norwegian (Bokmål)',
        'nl' => 'Dutch',
        'pl' => 'Polish',
        'pt-BR' => 'Portuguese (Brazil)',
        'pt-PT' => 'Portuguese (EU)',
        'ro' => 'Romanian',
        'ru' => 'Russian',
        'sk' => 'Slovak',
        'sl' => 'Slovenian',
        'sv' => 'Swedish',
        'th' => 'Thai',
        'tr' => 'Turkish',
        'uk' => 'Ukrainian',
        'vi' => 'Vietnamese',
        'zh-HANS' => 'Chinese (Simplified)',
        'zh-HANT' => 'Chinese (Traditional)',

        // ★ DeepL未対応（UIには出すが disabled にする）
        'ceb' => 'Cebuano',
        'fil' => 'Filipino (Tagalog)',
    ],

    // 現在 DeepL が受け付けるターゲット言語コード一覧（全部小文字で記載）
    'deepl_supported' => [
        'ar','bg','cs','da','de','el','en','en-gb','en-us','es','es-419','et','fi','fr','he',
        'hu','id','it','ja','ko','lt','lv','nb','nl','pl','pt','pt-br','pt-pt','ro','ru','sk',
        'sl','sv','th','tr','uk','vi','zh','zh-hans','zh-hant',
    ],
];
