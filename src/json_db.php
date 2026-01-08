<?php

function readData(): array {
    $file = __DIR__ . "/../storage/data.json";

    if (!file_exists($file)) {
        file_put_contents($file, json_encode([
            "users" => [],
            "categories" => [],
            "news" => []
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    $content = file_get_contents($file);
    $data = json_decode($content, true);

    return $data ?? [
        "users" => [],
        "categories" => [],
        "news" => []
    ];
}

function saveData(array $data): void {
    $file = __DIR__ . "/../storage/data.json";

    file_put_contents(
        $file,
        json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        LOCK_EX
    );
}
?>