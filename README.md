# Librar Backend 
Бэкенд приложение онлайн-библиотеки для бронирования книг

## Стек
### Язык - PHP 8.2
### Фреймворк - [Laravel 12](https://laravel.com/)
### База данных - PostgreSQL 16
### Библиотеки 
- **[L5-Swagger](https://github.com/darkaonline/l5-swagger)** - для создания Swagger-документации
- **[Laravel-Excel](https://laravel-excel.com/)** - для работы с Excel-файлами

## Установка
### 1. Клонирование репозитория
```bash
git clone https://github.com/zevess/librar-backend.git
cd librar-backend
```
### 2. Настройка переменных окружения (`.env`)
Создайте локальный файл конфигурации из шаблона:
```bash
cp .env.example .env.production
```
Откройте созданный файл `.env` и настройте ключевые параметры:
* **Подключение к БД**: Если ваша PostgreSQL запущена локально вне Docker, укажите:
  ```env
  DB_CONNECTION=pgsql
  DB_HOST=host.docker.internal
  DB_PORT=5432
  DB_DATABASE=имя_вашей_базы
  DB_USERNAME=ваш_логин
  DB_PASSWORD=ваш_пароль
  ```
* **Frontend URL (CORS)**: Укажите актуальный адрес вашего фронтенда (например, Vue на порту 3000):
  ```env
  VITE_APP_URL=http://localhost:3000
  ```
* **Google SMTP**: Для отправки писем используйте 16-значный **Пароль приложения** из настроек безопасности Google:
  ```env
  MAIL_MAILER=smtp
  MAIL_HOST=smtp.gmail.com
  MAIL_PORT=587
  MAIL_USERNAME=ваша_почта@gmail.com
  MAIL_PASSWORD=xxxx-xxxx-xxxx-xxxx
  ```
### 3. Сборка и запуск контейнеров
Запустите Docker Compose для автоматической сборки образов и поднятия контейнеров (`librar-api-app`, `librar-api-nginx`, `librar-api-scheduler`) в фоновом режиме:
```bash
docker compose up -d
```
### 4. Инициализация Laravel внутри контейнера
Выполните команды инициализации приложения в запущенной Docker-среде:
```bash
# Установка всех PHP зависимостей через Composer
docker compose exec librar-api composer install

# Генерация уникального ключа безопасности приложения
docker compose exec librar-api php artisan key:generate

# Создание символической ссылки для хранения изображений (Storage)
docker compose exec librar-api php artisan storage:link

# Запуск миграций базы данных
docker compose exec librar-api php artisan migrate
```

Запущенный проект будет доступен по адресу [localhost:8000/api](http://localhost:8000/api), Swagger-документация по адресу [localhost:8000/api/documentation](http://localhost:8000/api/documentation)

## Функционал
- Регистрация и аутентификация пользователей через access и refresh токены
- Ролевая модель доступа 
- CRUD для всех сущностей
- Бронирование книг
- Автоматическая отмена просроченных броней
- Импорт и экспорт `.xlsx` файлов
- Swagger/OpenAPI документация

## Логическая схема базы данных
<img width="1846" height="592" alt="Снимок экрана 2026-05-27 173101" src="https://github.com/user-attachments/assets/6e6696c1-7f74-4645-9eac-52c1c8ca2114" />

## Что было изучено в ходе разработки
- Построение бэкенд приложения по архитектуре MVC (Model-View-Controller) с репозиториями для взаимодействия с базой данных, и сервисами для бизнес-логики
- Создания аутентификации через access/refresh токены
- Работа с планировщиком задач 
- Отправка писем на почту с уведомлениями, сбросом пароля или подтверждением аккаунта
- Импорт и экспорт `.xlsx` файлов, работа с их данными
- Создание Swagger-документации по спецификации OpenAPI

## Использованные источники
- **[Laravel 12 Курс](https://youtube.com/playlist?list=PLXdAzchAW3NuVtRkHUDc3JJOPJmtVXPHW&si=7pvNryuKz7pEdzJ-)**
- **[Документация L5-Swagger](https://github.com/DarkaOnLine/L5-Swagger/wiki#welcome-to-the-l5-swagger-wiki)**
- **[Документация Laravel-Excel](https://docs.laravel-excel.com/3.1/getting-started/)**
- **[Гайды на Youtube](https://www.youtube.com/)**
- **[Поиск Google](https://www.google.com/)**
