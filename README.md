# SVResolution

**SVResolution** — служебная утилита для MODX Revolution, которая помогает при адаптивной вёрстке сайтов на Bootstrap.

Плагин выводит на сайте компактный индикатор с текущим Bootstrap-брейкпоинтом и шириной окна браузера в пикселях. Это помогает быстрее проверять, в каком диапазоне находится страница и как в этот момент должны работать сетка, стили и адаптивные элементы.

English version is available below.

---

## Русский

### Назначение

SVResolution полезен при разработке и проверке адаптивной вёрстки. Индикатор показывает:

- текущий брейкпоинт Bootstrap;
- текущую ширину окна браузера в пикселях.

Поддерживаются правила брейкпоинтов Bootstrap 3, 4 и 5.

Плагин работает автоматически и не требует вставки сниппета в шаблоны сайта.

### Условия показа

Индикатор виден только авторизованному пользователю панели управления MODX, который состоит в разрешённой группе пользователей.

По умолчанию используется группа:

```text
Administrator
```

Название группы можно изменить в свойствах плагина.

### Как это работает

Начиная с версии 1.0.3, видимый HTML-блок индикатора не вставляется напрямую в HTML страницы.

На страницу добавляется только небольшой загрузчик JavaScript. Сам индикатор загружается отдельным служебным запросом после проверки:

- активной сессии панели управления MODX;
- принадлежности пользователя к разрешённой группе.

Такой подход снижает риск ситуации, когда служебный индикатор может попасть в кэш страницы и стать видимым обычным посетителям сайта.

### Работа на 404-страницах

Индикатор может отображаться на 404-страницах для авторизованного пользователя панели управления MODX из разрешённой группы.

Служебный запрос для загрузки индикатора выполняется через базовый адрес сайта, а не через адрес несуществующей страницы. Это позволяет избежать обращения служебного запроса к URL 404-страницы.

### Используемое событие MODX

В текущей версии плагин использует системное событие:

```text
OnLoadWebDocument
```

### Свойства плагина

| Свойство | Описание |
|---|---|
| `position` | Положение индикатора на экране. Например: `left,bottom`, `right,top`. |
| `version` | Версия Bootstrap, по правилам которой рассчитывается брейкпоинт. Поддерживаются `3`, `4`, `5`. |
| `color` | Цвет текста и рамки индикатора. |
| `bgcolor` | Цвет фона индикатора. |
| `allowed_group` | Имя группы пользователей панели управления MODX, которым разрешён показ индикатора. |
| `zindex` | CSS `z-index` индикатора. |

### Установка

1. Установите транспортный пакет через менеджер пакетов MODX.
2. Убедитесь, что плагин `SVResolution` включён.
3. Очистите кэш MODX.
4. Откройте сайт, будучи авторизованным в панели управления MODX.
5. Проверьте, что индикатор виден только разрешённому пользователю.

### Сборка пакета из исходников

Исходники содержат сборщик транспортного пакета в папке `_build`.

Если проект расположен рядом с `config.core.php`, сборщик попробует определить путь к MODX автоматически.

Также путь к `core` можно передать явно через переменную окружения:

```bash
MODX_CORE_PATH=/path/to/modx/core/ php _build/build.transport.php
```

После успешной сборки транспортный пакет будет создан в папке пакетов MODX.

### Совместимость

Текущая версия предназначена для MODX Revolution 2.x.

Для версии 1.0.4 на ModStore указаны:

- минимальная версия MODX: 2.6;
- максимальная версия MODX: 2.8;
- минимальная версия PHP: 7.3.

### Автор

Александр Ларин (Палочкин)

### Лицензия

MIT

---

## English

### Purpose

**SVResolution** is a small helper utility for MODX Revolution that helps during responsive layout development with Bootstrap.

The plugin displays a compact frontend indicator showing:

- the current Bootstrap breakpoint;
- the current browser viewport width in pixels.

It supports Bootstrap 3, 4, and 5 breakpoint rules.

The plugin works automatically and does not require adding snippet calls to site templates.

### Visibility conditions

The indicator is visible only to an authenticated MODX manager user who belongs to the allowed user group.

The default group is:

```text
Administrator
```

The allowed group name can be changed in the plugin properties.

### How it works

Since version 1.0.3, the visible indicator HTML block is no longer injected directly into the page HTML.

The page receives only a small JavaScript loader. The indicator itself is loaded through a separate service request after checking:

- the active MODX manager session;
- the user membership in the allowed user group.

This reduces the risk of exposing the helper indicator to regular site visitors through cached page HTML.

### 404 page behavior

The indicator can be displayed on 404 pages for an authenticated MODX manager user from the allowed user group.

The service request used to load the indicator is made through the site base URL, not through the missing page URL. This avoids sending the service request to the 404 page URL.

### MODX system event

The current version uses the following MODX system event:

```text
OnLoadWebDocument
```

### Plugin properties

| Property | Description |
|---|---|
| `position` | Indicator position on the screen. For example: `left,bottom`, `right,top`. |
| `version` | Bootstrap version whose breakpoint rules are used. Supported values: `3`, `4`, `5`. |
| `color` | Indicator text and border color. |
| `bgcolor` | Indicator background color. |
| `allowed_group` | Name of the MODX manager user group allowed to see the indicator. |
| `zindex` | Indicator CSS `z-index`. |

### Installation

1. Install the transport package through the MODX Package Manager.
2. Make sure the `SVResolution` plugin is enabled.
3. Clear the MODX cache.
4. Open the site while authenticated in the MODX manager.
5. Check that the indicator is visible only to the allowed user.

### Building from source

The source files include a transport package builder in the `_build` directory.

If the project is located near `config.core.php`, the builder will try to detect the MODX core path automatically.

You can also pass the core path explicitly through the environment variable:

```bash
MODX_CORE_PATH=/path/to/modx/core/ php _build/build.transport.php
```

After a successful build, the transport package will be created in the MODX packages directory.

### Compatibility

The current version is intended for MODX Revolution 2.x.

For version 1.0.4, the ModStore package settings are:

- minimum MODX version: 2.6;
- maximum MODX version: 2.8;
- minimum PHP version: 7.3.

### Author

Alexander Larin (Palochkin)

### License

MIT
