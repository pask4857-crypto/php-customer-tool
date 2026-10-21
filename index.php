
<?php

require_once __DIR__ . '/db.php';

$customerId = trim($_GET['id'] ?? '');
$errorMessage = '';
$successMessage = '';
$customers = [];

$newName = '';
$newEmail = '';


 // 處理新增客戶
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = trim($_POST['name'] ?? '');
    $newEmail = trim($_POST['email'] ?? '');

    if ($newName === '' || $newEmail === '') {
        $errorMessage = '姓名與電子郵件都必須填寫。';
    } elseif (preg_match('/^.{1,100}$/us', $newName) !== 1) {
        $errorMessage = '姓名長度不可超過 100 個字元。';
    } elseif (strlen($newEmail) > 255) {
        $errorMessage = '電子郵件長度不可超過 255 個字元。';
    } elseif (filter_var($newEmail, FILTER_VALIDATE_EMAIL) === false) {
        $errorMessage = '請輸入有效的電子郵件地址。';
    } else {
        try {
            $sql = 'INSERT INTO customers (name, email)
                    VALUES (:name, :email)';

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'name' => $newName,
                'email' => $newEmail
            ]);

            // 新增成功後重新導向，避免重新整理時重複提交
            header('Location: index.php?created=1');
            exit;

        } catch (PDOException $e) {
            // 記錄詳細錯誤供開發者排查，不直接顯示給使用者
            error_log($e->getMessage());

            $errorMessage = '新增客戶失敗，請稍後再試。';
        }
    }
}


// 顯示新增成功訊息
if (($_GET['created'] ?? '') === '1') {
    $successMessage = '客戶新增成功。';
}

// 查詢客戶
if ($customerId !== '') {
    if (!ctype_digit($customerId) || (int) $customerId < 1) {
        $errorMessage = '請輸入有效的正整數客戶編號。';
    } else {
        $sql = 'SELECT id, name, email, created_at
                FROM customers
                WHERE id = :id';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => (int) $customerId
        ]);

        $customers = $stmt->fetchAll();

        if (count($customers) === 0) {
            $errorMessage = '找不到指定的客戶。';
        }
    }
} else {
    $sql = 'SELECT id, name, email, created_at
            FROM customers
            ORDER BY id';

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $customers = $stmt->fetchAll();
}

?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP 客戶查詢工具</title>
</head>
<body>
    <h1>PHP 客戶查詢工具</h1>

    <h2>新增客戶</h2>

    <?php if ($successMessage !== ''): ?>
        <p><?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <?php if ($errorMessage !== ''): ?>
        <p><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="POST" action="index.php">
        <div>
            <label for="name">姓名：</label>
            <input
                type="text"
                id="name"
                name="name"
                maxlength="100"
                required
                value="<?= htmlspecialchars($newName, ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div>
            <label for="email">電子郵件：</label>
            <input
                type="email"
                id="email"
                name="email"
                maxlength="255"
                required
                value="<?= htmlspecialchars($newEmail, ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <button type="submit">新增客戶</button>
    </form>

    <hr>

    <h2>依客戶編號搜尋</h2>

    <form method="GET" action="index.php">
        <label for="id">客戶編號：</label>
        <input
            type="number"
            id="id"
            name="id"
            min="1"
            step="1"
            value="<?= htmlspecialchars($customerId, ENT_QUOTES, 'UTF-8') ?>"
        >
        <button type="submit">搜尋</button>
        <a href="index.php">顯示全部</a>
    </form>

    <h2>客戶列表</h2>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>編號</th>
                <th>姓名</th>
                <th>電子郵件</th>
                <th>建立時間</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $customer): ?>
                <tr>
                    <td><?= htmlspecialchars((string) $customer['id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($customer['name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($customer['email'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($customer['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
