# Yandex Reviews App (Laravel 12 + Vue 3)

Готовый шаблон интеграции с **Yandex Business API** для получения отзывов, рейтинга и количества отзывов компании.

## Что реализовано

- Авторизация (email/password, session cookie).
- Страница настроек интеграции.
- Сохранение ссылки на карточку Яндекс Карт.
- Сохранение `organization_id` (ID организации для API).
- Получение и вывод:
  - рейтинга компании,
  - общего количества отзывов,
  - списка отзывов.
- Пагинация и сортировка (сначала новые / сначала старые).

---

## 1) Создание проекта

```bash
composer create-project laravel/laravel yandex-reviews-app
cd yandex-reviews-app
composer require laravel/sanctum guzzlehttp/guzzle
npm install
npm install vue vue-router axios @vitejs/plugin-vue --save-dev
```

Скопируйте файлы из этого репозитория в новый проект.

---

## 2) Настройка .env

```env
APP_NAME="Yandex Reviews App"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=yandex_reviews
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
YANDEX_BUSINESS_API_BASE_URL=https://api.business.yandex.ru
YANDEX_BUSINESS_OAUTH_TOKEN=ваш_oauth_token
YANDEX_BUSINESS_PER_PAGE=20
```

---

## 3) Миграции и пользователь

```bash
php artisan migrate
php artisan tinker
```

В `tinker`:

```php
\App\Models\User::create([
  'name' => 'Admin',
  'email' => 'admin@example.com',
  'password' => bcrypt('password')
]);
```

---

## 4) Официальный API Yandex Business

Интеграция использует REST API Yandex Business:

- Базовый URL: `https://api.business.yandex.ru`
- Авторизация: OAuth token в header `Authorization: Bearer <TOKEN>`
- Текущий сервис вызывает endpoint:
  - `GET /v1/organizations/{organizationId}/reviews`
  - query params: `page`, `limit`, `orderBy`

Порядок получения данных:
1. Получить `organization_id` (из кабинета Яндекс Бизнес; ID в URL карточки не всегда равен API ID).
2. Сохранить `organization_id` в настройках.
3. Запросить отзывы через backend (`/api/reviews`).

> Внимание: названия и версия endpoint могут меняться в официальной документации Яндекса. Проверьте актуальность в docs Yandex Business API и при необходимости обновите `YandexBusinessService`.

---

## 5) Запуск

В первом терминале:

```bash
php artisan serve
```

Во втором терминале:

```bash
npm run dev
```

Приложение откроется на `http://localhost:8000`.

---

## 6) Использование

1. Откройте `/login`.
2. Войдите `admin@example.com / password`.
3. Перейдите в «Настройки».
4. Укажите ссылку на карточку, например:
   `https://yandex.ru/maps/org/samoye_populyarnoye_kafe/1010501395/reviews/`
5. Укажите `organization_id` из Яндекс Бизнес.
6. Перейдите в «Отзывы» — увидите рейтинг, количество отзывов и список.

---

## Структура

```text
yandex-reviews-app/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php
│   │   ├── SettingsController.php
│   │   └── ReviewController.php
│   ├── Services/
│   │   └── YandexBusinessService.php
│   └── Models/
│       └── Setting.php
├── database/migrations/
├── routes/api.php
├── routes/web.php
├── resources/js/
│   ├── app.js
│   ├── router.js
│   └── components/
│       ├── Login.vue
│       ├── Dashboard.vue
│       ├── Settings.vue
│       └── Reviews.vue
├── .env.example
├── composer.json
├── package.json
├── vite.config.js
└── README.md
```

## Деплой (кратко)

- GitHub: push репозиторий и подключите CI/CD.
- VDS:
  1. Установить `nginx`, `php-fpm 8.2+`, `mysql`, `nodejs`.
  2. `composer install --no-dev`
  3. `npm ci && npm run build`
  4. `php artisan migrate --force`
  5. Настроить `nginx` на `public/`.
  6. Добавить `systemd`/supervisor для очередей (при необходимости).
