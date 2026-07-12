# 設備報修管理系統 EquipFlow

[![Vue 3](https://img.shields.io/badge/Vue%203-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white)](https://vuejs.org/) [![Vuetify](https://img.shields.io/badge/Vuetify-1867C0?style=for-the-badge&logo=vuetify&logoColor=white)](https://vuetifyjs.com/) [![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/) [![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/) [![SQLite](https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white)](https://www.sqlite.org/)

## 專案簡介

**EquipFlow** 是一套**前後端分離**的設備報修管理系統。前端使用 Vue 3 + Vuetify 開發，後端採用 PHP + Laravel 提供 RESTful API，適合企業、學校或組織內部的設備維護與報修流程管理。

## 專案特色

- 前後端完全分離架構
- Vue 3 Composition API + Pinia 狀態管理
- Laravel RESTful API
- 響應式現代化 UI

## 核心功能

### 儀表板

- 總案件數、待處理案件、已完成案件等 KPI 統計
- 今日新增案件與逾期案件顯示
- 最新報修案件即時顯示

### 報修管理

- 新增報修單（含設備選擇、優先等級、問題描述）
- 報修案件列表（支援搜尋與狀態篩選）
- 報修單詳細資訊查看
- 報修狀態更新（待處理 → 處理中 → 已完成）
- 刪除報修單

### 設備管理

- 設備列表顯示
- 設備基本資訊管理（名稱、類型、位置、狀態等）
- 設備狀態追蹤（正常、維修中、故障）

### 其他功能

- 響應式設計（支援電腦與手機）
- Loading 狀態與錯誤處理
- Mock 資料模式（便於前端獨立開發）

## 技術架構

### Frontend

- Vue 3 + Composition API
- Vue Router + Pinia
- Vuetify + Axios

### Backend

- PHP 8.2+
- Laravel 11
- SQLite（開發環境）
- RESTful API + Laravel Sanctum 認證

## 系統架構圖

<img width="663" height="1975" alt="EquipFlow-2" src="https://github.com/user-attachments/assets/9af4b92c-f77a-47b1-8fb4-dcccd7ddbf0b" />

## 資料模型

<img width="1085" height="806" alt="EquipFlow" src="https://github.com/user-attachments/assets/145329fc-bf85-4459-8705-c09c5c6c5040" />

### Users（使用者）

| 欄位名稱   | 資料型別     | 說明                                                        |
| ---------- | ------------ | ----------------------------------------------------------- |
| id         | int (PK)     | 主鍵，自動遞增                                              |
| name       | varchar(100) | 使用者姓名                                                  |
| email      | varchar(100) | 電子郵件（唯一）                                            |
| password   | varchar(255) | 密碼（加密後）                                              |
| role       | varchar(50)  | 角色權限（admin 管理員 / staff 員工 / technician 維修人員） |
| department | varchar(100) | 所屬部門                                                    |
| created_at | timestamp    | 建立時間                                                    |
| updated_at | timestamp    | 更新時間                                                    |

### Equipment（設備）

| 欄位名稱      | 資料型別     | 說明                                                       |
| ------------- | ------------ | ---------------------------------------------------------- |
| id            | int (PK)     | 主鍵，自動遞增                                             |
| name          | varchar(150) | 設備名稱                                                   |
| type          | varchar(100) | 設備類型（空調、監控、消防等）                             |
| serial_number | varchar(50)  | 序號（唯一）                                               |
| status        | varchar(30)  | 設備狀態（active 正常 / maintenance 維修中 / broken 故障） |
| location      | varchar(150) | 放置位置                                                   |
| purchase_date | date         | 購買日期                                                   |
| description   | text         | 設備描述                                                   |
| created_at    | timestamp    | 建立時間                                                   |
| updated_at    | timestamp    | 更新時間                                                   |

### Repairs（報修單）

| 欄位名稱     | 資料型別     | 說明                                                                        |
| ------------ | ------------ | --------------------------------------------------------------------------- |
| id           | int (PK)     | 主鍵，自動遞增                                                              |
| equipment_id | int          | 關聯設備 ID                                                                 |
| title        | varchar(200) | 報修單標題                                                                  |
| description  | text         | 問題詳細描述                                                                |
| status       | varchar(30)  | 報修狀態（pending 待處理 / processing 處理中 / done 完成 / cancelled 取消） |
| priority     | varchar(20)  | 優先等級（high 高 / medium 中 / low 低）                                    |
| reported_by  | int          | 報修人（關聯 users）                                                        |
| assigned_to  | int          | 指派處理人（關聯 users）                                                    |
| reported_at  | timestamp    | 報修時間                                                                    |
| completed_at | timestamp    | 完成時間                                                                    |
| created_at   | timestamp    | 建立時間                                                                    |
| updated_at   | timestamp    | 更新時間                                                                    |

### Repair_logs（報修紀錄）

| 欄位名稱   | 資料型別     | 說明                                 |
| ---------- | ------------ | ------------------------------------ |
| id         | int (PK)     | 主鍵，自動遞增                       |
| repair_id  | int          | 關聯報修單 ID                        |
| user_id    | int          | 操作人員 ID                          |
| action     | varchar(100) | 操作行為（如：狀態變更、指派、備註） |
| note       | text         | 備註內容                             |
| created_at | timestamp    | 紀錄時間                             |

## API 設計（RESTful）

`Base URL：http://localhost:8000/api`

### 認證相關（Auth）

| 方法 | Endpoint         | 說明       | Request Body            | 備註       |
| ---- | ---------------- | ---------- | ----------------------- | ---------- |
| POST | `/auth/register` | 使用者註冊 | `name, email, password` | -          |
| POST | `/auth/login`    | 使用者登入 | `email, password`       | 返回 Token |
| POST | `/auth/logout`   | 使用者登出 | -                       | 需要 Token |

### 設備管理（Equipment）

| 方法   | Endpoint          | 說明         | Request Body / Params                                                     | 備註     |
| ------ | ----------------- | ------------ | ------------------------------------------------------------------------- | -------- |
| GET    | `/equipment`      | 取得設備列表 | `?search=xxx&status=active`                                               | 支援分頁 |
| GET    | `/equipment/{id}` | 取得單一設備 | -                                                                         | -        |
| POST   | `/equipment`      | 新增設備     | `name, type, serial_number, status, location, purchase_date, description` | -        |
| PUT    | `/equipment/{id}` | 更新設備     | 同新增設備欄位                                                            | -        |
| DELETE | `/equipment/{id}` | 刪除設備     | -                                                                         | -        |

### 報修管理（Repairs）

| 方法   | Endpoint               | 說明           | Request Body / Params                                     | 備註     |
| ------ | ---------------------- | -------------- | --------------------------------------------------------- | -------- |
| GET    | `/repairs`             | 取得報修列表   | `?status=pending&search=關鍵字`                           | 支援分頁 |
| GET    | `/repairs/{id}`        | 取得單一報修單 | -                                                         | -        |
| POST   | `/repairs`             | 新增報修單     | `title, description, equipment_id, priority, reported_by` | -        |
| PUT    | `/repairs/{id}`        | 更新報修單     | 同新增報修單欄位                                          | -        |
| PATCH  | `/repairs/{id}/status` | 更新報修狀態   | `{ "status": "processing" }`                              | 推薦使用 |
| DELETE | `/repairs/{id}`        | 刪除報修單     | -                                                         | -        |

### 報修紀錄（Repair Logs）

| 方法 | Endpoint             | 說明                     | Request Body   |
| ---- | -------------------- | ------------------------ | -------------- |
| GET  | `/repairs/{id}/logs` | 取得指定報修單的異動紀錄 | -              |
| POST | `/repairs/{id}/logs` | 新增報修操作紀錄         | `action, note` |

### 儀表板統計（Dashboard API）

| 方法 | Endpoint                    | 說明                                                               |
| ---- | --------------------------- | ------------------------------------------------------------------ |
| GET  | `/dashboard/stats`          | 取得儀表板統計資料（總案件數、待處理、處理中、已完成、逾期案件等） |
| GET  | `/dashboard/latest-repairs` | 取得最新 5 筆報修案件                                              |

## 專案架構

```
equipflow/                          # 專案根目錄
├── backend/                        # === Laravel 後端 ===
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/        # API 控制器
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── EquipmentController.php
│   │   │   │   ├── RepairController.php
│   │   │   │   ├── RepairLogController.php
│   │   │   │   └── DashboardController.php
│   │   │   ├── Requests/           # 表單驗證
│   │   │   └── Middleware/
│   │   ├── Models/                 # Eloquent Model
│   │   │   ├── User.php
│   │   │   ├── Equipment.php
│   │   │   ├── Repair.php
│   │   │   └── RepairLog.php
│   │   ├── Services/               # 商業邏輯
│   │   └── Providers/
│   ├── database/
│   │   ├── migrations/             # 資料表遷移
│   │   │   ├── create_users_table.php
│   │   │   ├── create_equipment_table.php
│   │   │   ├── create_repairs_table.php
│   │   │   └── create_repair_logs_table.php
│   │   ├── seeders/                # 測試資料
│   │   │   ├── DatabaseSeeder.php
│   │   │   ├── UserSeeder.php
│   │   │   ├── EquipmentSeeder.php
│   │   │   ├── RepairSeeder.php
│   │   │   └── RepairLogSeeder.php
│   │   └── database.sqlite         # SQLite 資料庫檔案
│   ├── routes/
│   │   └── api.php                 # API 路由
│   ├── tests/
│   ├── composer.json
│   ├── .env
│   └── artisan
│
├── frontend/                       # === Vue 3 前端 ===
│   ├── dist/                       # 打包輸出目錄（build 後產生）
│   ├── public/
│   │   ├── favicon.ico
│   │   └── index.html
│   │
│   ├── src/
│   │   ├── assets/
│   │   │
│   │   ├── components/             # 可重用元件
│   │   │   ├── common/
│   │   │   │   ├── AppNavbar.vue
│   │   │   │   ├── AppSidebar.vue
│   │   │   │   └── BaseCard.vue
│   │   │   ├── RepairForm.vue
│   │   │   ├── RepairTable.vue
│   │   │   └── StatusChip.vue
│   │   │
│   │   ├── composable/             # Composition API 邏輯
│   │   │   ├── useApi.js
│   │   │   └── useRepair.js
│   │   │
│   │   ├── plugins/
│   │   │   ├── vuetify.js
│   │   │   └── webfontloader.js
│   │   │
│   │   ├── router/
│   │   │   └── index.js
│   │   │
│   │   ├── services/               # API 服務層
│   │   │   ├── api.js
│   │   │   ├── repairService.js
│   │   │   └── equipmentService.js
│   │   │
│   │   ├── stores/                 # Pinia 狀態管理
│   │   │   ├── repair.js
│   │   │   └── equipment.js
│   │   │
│   │   ├── utils/                  # 工具函式
│   │   │   ├── formatData.js
│   │   │   └── statuscolor.js
│   │   │
│   │   ├── views/                  # 頁面元件
│   │   │   ├── DashboardView.vue
│   │   │   ├── RepairView.vue
│   │   │   ├── RepairCreateView.vue
│   │   │   ├── RepairDetailView.vue
│   │   │   └── EquipmentView.vue
│   │   │
│   │   ├── App.vue
│   │   └── main.js
│   │
│   ├── .gitignore
│   ├── babel.config.js
│   ├── jsconfig.json
│   ├── package-lock.json
│   ├── package.json
│   └── vue.config.js
│
├── README.md
└── .gitignore
```

## Git 分支規範

### 分支命名規範

| 分支前綴    | 範例                       | 使用說明                   |
| ----------- | -------------------------- | -------------------------- |
| `frontend/` | `frontend/repair-ui`       | 僅修改前端功能或介面       |
| `backend/`  | `backend/repair-api`       | 僅修改後端功能或 API       |
| `feature/`  | `feature/repair-fullstack` | 前後端皆需修改的新功能開發 |
| `bugfix/`   | `bugfix/repair-list-error` | Bug 修正與問題排除         |

## 安裝與執行

### 前端獨立執行

```
# 進入 frontend 資料夾
cd frontend

# 安裝依賴套件
npm install

# 啟動開發伺服器
npm run serve
```

### 後端獨立執行

```
# 進入 backend 資料夾
cd backend

# 安裝 PHP 依賴
composer install

# 複製環境設定檔
cp .env.example .env

# 修改 .env 設定 SQLite
# DB_CONNECTION=sqlite

# 建立 SQLite 檔案
touch database/database.sqlite

# 產生應用程式金鑰
php artisan key:generate

# 安裝 Sanctum
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# 建立資料庫與測試資料
php artisan migrate:fresh --seed

# 啟動 Laravel 開發伺服器
php artisan serve
```

### 前後端同時開發

開兩個終端機視窗：

- 終端機 1（後端）：

```
cd backend
php artisan serve
```

- 終端機 2（前端）：

```
cd frontend
npm run serve
```

- 啟動後的存取位址：

前端：http://localhost:8080
後端 API：http://localhost:8000

## 版權聲明

此專案僅供個人學習與練習使用，禁止用於商業用途。

## 致謝

感謝所有提供建議與協助的朋友。
