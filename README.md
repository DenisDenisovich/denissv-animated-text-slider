# Denissv Animated Text Slider WordPress Plugin

A WordPress plugin for creating and managing image carousels.

## Description

Denissv Animated Text Slider — This is a lightweight and flexible WordPress plugin for creating responsive carousels and sliders based on the Swiper.js library. The plugin provides an intuitive admin interface for managing carousels and their slides.

## Features

- 📱 Responsive design
- ⚡ Based on Swiper.js 9.4.1
- 🎨 Support for various types of animations (slide, fade)
- ➡️ Optional navigation arrows
- 🔵 Optional bullet points
- 🎯 Custom carousel height
- 🏷️ A simple shortcode `[carousel3 id="1"]`
- 🛡️ Security: nonce validation, access rights validation

## Requirements

- WordPress 5.0 or later
- PHP 7.2 or later
- jQuery (WordPress's built-in jQuery)

## Установка

1. Скачайте файлы плагина
2. Загрузите папку `Denissv Animated Text Slider/` в папку `/wp-content/plugins/`
3. Активируйте плагин в разделе "Плагины" WordPress

## Использование

### Создание карусели

1. Перейдите в **Карусели3** в меню администратора WordPress
2. Нажмите **Добавить новую** карусель
3. Введите название карусели
4. Настройте параметры в боксе "Настройки карусели" (справа)
5. Добавьте слайды, нажав кнопку **Добавить слайд**
6. Скопируйте шорткод и вставьте на нужную страницу

### Параметры карусели

- **Тип анимации**: Скользящий или Исчезновение
- **Показывать стрелки навигации**: Включить/отключить боковые стрелки
- **Показывать точки навигации**: Включить/отключить точки-буллеты внизу
- **Высота карусели**: Задать высоту (например: 300px, 50%, auto)

### Использование шорткода

```
[carousel3 id="1"]
```

Замените `1` на ID вашей карусели.

## Структура плагина

```
carousel3/
├── Carousel3.php              # Основной файл плагина
├── README.md                  # Этот файл
├── admin/                     # Административная часть
│   ├── class-admin.php
│   ├── class-carousels.php
│   ├── class-sliders.php
│   ├── css/
│   ├── js/
│   └── views/
├── includes/                  # Включаемые файлы
│   ├── class-activator.php
│   └── class-init.php
└── public/                    # Публичная часть (фронтенд)
    ├── class-frontend.php
    ├── assets/
    │   ├── js/
    │   └── styles/
    └── views/
```

## Хуки и фильтры

### Действия (Actions)

- `carousel3_before_carousel` — перед выводом карусели
- `carousel3_after_carousel` — после вывода карусели

### Фильтры (Filters)

- `carousel3_query_slides` — фильтр для изменения запроса слайдов

## Проблемы и поддержка

Если вы обнаружили проблему, проверьте:
- Версия WordPress совместима ли с требованиями плагина
- Другие плагины не конфликтуют ли с Carousel3
- JavaScript консоль браузера не показывает ошибок

## Лицензия

GPL2

## Версия

1.0.0

## Автор

musite.xyz

