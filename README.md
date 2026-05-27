# Librar Backend 
Бэкенд приложение онлайн-библиотеки для бронирования книг

## Стек
### Язык - PHP
### Фреймворк - [Laravel](https://laravel.com/)
### Библиотеки 
- **[L5-Swagger](https://github.com/darkaonline/l5-swagger)** - для создания Swagger-документации
- **[Laravel-Excel](https://laravel-excel.com/)** - для работы с Excel-файлами
Язык PHP, Фреймворк Laravel, вспомогательные библиотеки darkaonline/l5-swagger для создания Swagger-документации и maatwebsite/excel 

## Установка
1. [Установить Herd для создания окружения Php](https://herd.laravel.com/windows)
2. Создать базу данных PostgreSQL
3. Клонировать репозиторий `git clone --single-branch --branch cross-platform-software https://github.com/zevess/librar-backend.git`
4. Установить зависимости `composer install`
5. Создать .env файл, настроить подключение к базе данных
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_database
DB_USERNAME=your_user
DB_PASSWORD=your_password
```
6. Выполнить миграции `php artisan migrate`
7. Запустить Laravel `php artisan serve`

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

## Физическая схема базы данных
### users
```
-- Table: public.users

-- DROP TABLE IF EXISTS public.users;

CREATE TABLE IF NOT EXISTS public.users
(
    id bigint NOT NULL DEFAULT nextval('users_id_seq'::regclass),
    name character varying(255) COLLATE pg_catalog."default" NOT NULL,
    email character varying(255) COLLATE pg_catalog."default" NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) COLLATE pg_catalog."default" NOT NULL,
    remember_token character varying(100) COLLATE pg_catalog."default",
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    role character varying(255) COLLATE pg_catalog."default" NOT NULL DEFAULT 'user'::character varying,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT users_pkey PRIMARY KEY (id),
    CONSTRAINT users_email_unique UNIQUE (email)
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.users
    OWNER to postgres;
```
### personal_access_tokens
```
-- Table: public.personal_access_tokens

-- DROP TABLE IF EXISTS public.personal_access_tokens;

CREATE TABLE IF NOT EXISTS public.personal_access_tokens
(
    id bigint NOT NULL DEFAULT nextval('personal_access_tokens_id_seq'::regclass),
    tokenable_type character varying(255) COLLATE pg_catalog."default" NOT NULL,
    tokenable_id bigint NOT NULL,
    name text COLLATE pg_catalog."default" NOT NULL,
    token character varying(64) COLLATE pg_catalog."default" NOT NULL,
    abilities text COLLATE pg_catalog."default",
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id),
    CONSTRAINT personal_access_tokens_token_unique UNIQUE (token)
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.personal_access_tokens
    OWNER to postgres;
-- Index: personal_access_tokens_expires_at_index

-- DROP INDEX IF EXISTS public.personal_access_tokens_expires_at_index;

CREATE INDEX IF NOT EXISTS personal_access_tokens_expires_at_index
    ON public.personal_access_tokens USING btree
    (expires_at ASC NULLS LAST)
    TABLESPACE pg_default;
-- Index: personal_access_tokens_tokenable_type_tokenable_id_index

-- DROP INDEX IF EXISTS public.personal_access_tokens_tokenable_type_tokenable_id_index;

CREATE INDEX IF NOT EXISTS personal_access_tokens_tokenable_type_tokenable_id_index
    ON public.personal_access_tokens USING btree
    (tokenable_type COLLATE pg_catalog."default" ASC NULLS LAST, tokenable_id ASC NULLS LAST)
    TABLESPACE pg_default;
```
### refresh_tokens 
```
-- Table: public.refresh_tokens

-- DROP TABLE IF EXISTS public.refresh_tokens;

CREATE TABLE IF NOT EXISTS public.refresh_tokens
(
    id bigint NOT NULL DEFAULT nextval('refresh_tokens_id_seq'::regclass),
    user_id bigint NOT NULL,
    token character varying(64) COLLATE pg_catalog."default" NOT NULL,
    expires_at timestamp(0) without time zone NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT refresh_tokens_pkey PRIMARY KEY (id),
    CONSTRAINT refresh_tokens_token_unique UNIQUE (token),
    CONSTRAINT refresh_tokens_user_id_foreign FOREIGN KEY (user_id)
        REFERENCES public.users (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE CASCADE
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.refresh_tokens
    OWNER to postgres;
```

### books
```
-- Table: public.books

-- DROP TABLE IF EXISTS public.books;

CREATE TABLE IF NOT EXISTS public.books
(
    id bigint NOT NULL DEFAULT nextval('books_id_seq'::regclass),
    title character varying(255) COLLATE pg_catalog."default" NOT NULL,
    slug character varying(255) COLLATE pg_catalog."default" NOT NULL,
    description text COLLATE pg_catalog."default",
    image character varying(255) COLLATE pg_catalog."default",
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    author_id bigint,
    publisher_id bigint,
    category_id bigint,
    CONSTRAINT books_pkey PRIMARY KEY (id),
    CONSTRAINT books_author_id_foreign FOREIGN KEY (author_id)
        REFERENCES public.authors (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE SET NULL,
    CONSTRAINT books_category_id_foreign FOREIGN KEY (category_id)
        REFERENCES public.categories (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE SET NULL,
    CONSTRAINT books_publisher_id_foreign FOREIGN KEY (publisher_id)
        REFERENCES public.publishers (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE SET NULL
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.books
    OWNER to postgres;
```

### authors
```
-- Table: public.authors

-- DROP TABLE IF EXISTS public.authors;

CREATE TABLE IF NOT EXISTS public.authors
(
    id bigint NOT NULL DEFAULT nextval('authors_id_seq'::regclass),
    name character varying(255) COLLATE pg_catalog."default" NOT NULL,
    slug character varying(255) COLLATE pg_catalog."default" NOT NULL,
    description text COLLATE pg_catalog."default",
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT authors_pkey PRIMARY KEY (id),
    CONSTRAINT authors_slug_unique UNIQUE (slug)
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.authors
    OWNER to postgres;
```

### categories 
```
-- Table: public.categories

-- DROP TABLE IF EXISTS public.categories;

CREATE TABLE IF NOT EXISTS public.categories
(
    id bigint NOT NULL DEFAULT nextval('categories_id_seq'::regclass),
    name character varying(255) COLLATE pg_catalog."default" NOT NULL,
    slug character varying(255) COLLATE pg_catalog."default" NOT NULL,
    description text COLLATE pg_catalog."default",
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT categories_pkey PRIMARY KEY (id),
    CONSTRAINT categories_slug_unique UNIQUE (slug)
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.categories
    OWNER to postgres;
```

### publishers 
```
-- Table: public.publishers

-- DROP TABLE IF EXISTS public.publishers;

CREATE TABLE IF NOT EXISTS public.publishers
(
    id bigint NOT NULL DEFAULT nextval('publishers_id_seq'::regclass),
    name character varying(255) COLLATE pg_catalog."default" NOT NULL,
    slug character varying(255) COLLATE pg_catalog."default" NOT NULL,
    description text COLLATE pg_catalog."default",
    image character varying(255) COLLATE pg_catalog."default",
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT publishers_pkey PRIMARY KEY (id),
    CONSTRAINT publishers_slug_unique UNIQUE (slug)
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.publishers
    OWNER to postgres;
```

### reservations
```
-- Table: public.reservations

-- DROP TABLE IF EXISTS public.reservations;

CREATE TABLE IF NOT EXISTS public.reservations
(
    id bigint NOT NULL DEFAULT nextval('reservations_id_seq'::regclass),
    book_id bigint,
    reserved_by bigint,
    reserved_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    issued_at timestamp(0) without time zone,
    accepted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    status character varying(255) COLLATE pg_catalog."default",
    CONSTRAINT reservations_pkey PRIMARY KEY (id),
    CONSTRAINT reservations_book_id_foreign FOREIGN KEY (book_id)
        REFERENCES public.books (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION,
    CONSTRAINT reservations_reserved_by_foreign FOREIGN KEY (reserved_by)
        REFERENCES public.users (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE SET NULL,
    CONSTRAINT reservations_status_check CHECK (status::text = ANY (ARRAY['reserved'::character varying, 'canceled'::character varying, 'issued'::character varying, 'completed'::character varying]::text[]))
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.reservations
    OWNER to postgres;
```

### reviews 
```
-- Table: public.reviews

-- DROP TABLE IF EXISTS public.reviews;

CREATE TABLE IF NOT EXISTS public.reviews
(
    id bigint NOT NULL DEFAULT nextval('reviews_id_seq'::regclass),
    user_id bigint,
    book_id bigint,
    text text COLLATE pg_catalog."default",
    rating smallint NOT NULL,
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT reviews_pkey PRIMARY KEY (id),
    CONSTRAINT reviews_book_id_foreign FOREIGN KEY (book_id)
        REFERENCES public.books (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION,
    CONSTRAINT reviews_user_id_foreign FOREIGN KEY (user_id)
        REFERENCES public.users (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.reviews
    OWNER to postgres;
```

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

## Запуск на виртуальной машине
<img width="1919" height="1079" alt="Снимок экрана 2026-05-27 153639" src="https://github.com/user-attachments/assets/7fac2d00-f103-4fc5-b619-35e25beb763a" />
