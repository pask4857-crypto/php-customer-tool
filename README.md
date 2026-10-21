# PHP Customer Tool

使用 PHP 與 MySQL 製作的簡易客戶管理工具，作為 PHP、資料庫操作及表單驗證的練習專案。

## 功能

- 顯示所有客戶資料
- 依客戶編號查詢
- 新增客戶
- 驗證必填欄位
- 驗證姓名與電子郵件長度
- 驗證電子郵件格式
- 使用 PDO 預備陳述式操作資料庫
- 使用 `htmlspecialchars()` 處理 HTML 輸出，降低 XSS 風險
- 將本機資料庫連線設定與程式碼分離

## 使用技術

- PHP 8.3
- MySQL 8.0
- PDO
- HTML

## 環境需求

- PHP 8.3 或相容版本
- MySQL 8.0 或相容版本
- 已啟用 PHP 的 `pdo_mysql` 擴充功能

## 安裝與設定

### 1. 建立資料庫

使用 MySQL Workbench 執行 `database/setup.sql`，建立專案使用的資料庫與資料表。

### 2. 設定資料庫連線

複製或參考 `config.example.php` 的設定，在專案根目錄建立 `config.local.php`。

依照自己的 MySQL 環境設定主機、資料庫名稱、帳號與密碼。

請勿將包含實際帳號密碼的 `config.local.php` 上傳至 GitHub。

### 3. 啟動專案

在專案根目錄開啟 PowerShell，執行：

```powershell
php -S localhost:8000
```

接著使用瀏覽器開啟：

http://localhost:8000

## 專案結構

```text
php-customer-tool/
├── database/
│   └── setup.sql
├── .gitignore
├── config.example.php
├── config.local.php
├── db.php
├── index.php
└── README.md
```

其中 `config.local.php` 是本機專用設定檔，不應提交至版本控制。

## 注意事項

此專案為練習與作品集用途，使用 PHP 內建開發伺服器執行，不應直接視為可部署至正式環境的完整系統。
