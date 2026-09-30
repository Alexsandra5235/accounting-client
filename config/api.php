<?php

return [
    'log_token' => env('API_LOG_TOKEN', 'default-token'),
    'log_classifiers_url' => env('API_LOG_CLASSIFIERS_URL', ''),
    'yandex_iam_token' => env('YANDEX_CLOUD_IAM_TOKEN', ''),
    'yandex_folder_id' => env('YANDEX_CLOUD_FOLDER_ID', ''),
    'yandex_cloud_api_key' => env('YANDEX_CLOUD_API_KEY', ''),
];
