
<?php

$configPath = __DIR__ . '/config.local.php';

if (!is_file($configPath)) {
    exit('找不到本機資料庫設定，請先建立 config.local.php。');
}

$config = require $configPath;

$dsn = sprintf(
    'mysql:host=%s;dbname=%s;charset=utf8mb4',
    $config['host'],
    $config['dbname']
);

try {
    $pdo = new PDO(
        $dsn,
        $config['username'],
        $config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    exit('資料庫連線失敗，請檢查本機設定。');
}
