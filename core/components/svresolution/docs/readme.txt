SVResolution
====================

Version: 1.0.4
Author: Александр Ларин (Палочкин)
License: MIT

Read the English version below.

====================
РУССКИЙ
====================

Назначение
----------

SVResolution — это дополнение для MODX Revolution, содержащее один плагин для вывода служебного индикатора на сайте.

Плагин помогает быстро видеть текущий брейкпоинт Bootstrap и текущую ширину окна браузера прямо на странице во время разработки и проверки адаптивности.

Плагин работает автоматически и не требует вставки сниппета в шаблоны сайта.

Что показывает плагин
---------------------

Плагин выводит компактный служебный блок, в котором отображаются:
- текущий брейкпоинт Bootstrap
- текущая ширина окна браузера в пикселях

Дополнительная информация о версии Bootstrap и ограничении показа выводится в title блока.

Условия показа
--------------

Блок показывается только авторизованному пользователю панели управления MODX, который состоит в разрешённой группе пользователей.

Видимый блок не вставляется напрямую в HTML страницы. На страницу добавляется только небольшой загрузчик JavaScript, а сам индикатор загружается отдельным служебным запросом после проверки сессии панели управления MODX и разрешённой группы.

Работа с кэшем
--------------

Плагин не вставляет готовый видимый HTML-блок напрямую в кэшируемый HTML страницы.

Это снижает риск ситуации, когда служебный индикатор может попасть в кэш страницы и стать видимым обычным посетителям сайта.

Работа на 404-страницах
-----------------------

Индикатор может отображаться на 404-страницах для авторизованного пользователя панели управления MODX из разрешённой группы.

Служебный запрос для загрузки индикатора выполняется через базовый адрес сайта, а не через адрес несуществующей страницы. Это позволяет избежать обращения служебного запроса к URL 404-страницы.

Текущее рабочее событие
-----------------------

В текущей версии плагин использует системное событие OnLoadWebDocument.

Свойства плагина
----------------

position  
Определяет положение блока на экране. Позволяет выбрать, у какого края окна будет расположен индикатор.

version  
Определяет версию Bootstrap, по правилам которой рассчитывается текущий брейкпоинт.

color  
Задаёт цвет текста и рамки блока.

bgcolor  
Задаёт цвет фона блока.

allowed_group  
Определяет имя группы пользователей панели управления MODX, которым разрешён показ блока.

zindex  
Определяет CSS z-index блока, то есть его приоритет поверх других элементов страницы.

====================
ENGLISH
====================

Purpose
-------

SVResolution is an extra for MODX Revolution that contains a single plugin for displaying a helper indicator on the frontend.

The plugin helps you quickly see the current Bootstrap breakpoint and the current browser window width directly on the page during development and responsive layout testing.

The plugin works automatically and does not require adding a snippet call to site templates.

What the plugin shows
---------------------

The plugin displays a compact helper block with:
- the current Bootstrap breakpoint
- the current browser window width in pixels

Additional information about the Bootstrap version and visibility restriction is shown in the block title.

Visibility conditions
---------------------

The block is visible only to an authenticated MODX manager user who belongs to the allowed user group.

The visible block is not injected directly into the page HTML. The page receives only a small JavaScript loader, and the indicator itself is loaded through a separate service request after checking the MODX manager session and the allowed user group.

Cache behavior
--------------

The plugin does not inject the ready visible HTML block directly into cacheable page HTML.

This reduces the risk of the helper indicator being stored in the page cache and becoming visible to regular site visitors.

404 page behavior
-----------------

The indicator can be displayed on 404 pages for an authenticated MODX manager user from the allowed user group.

The service request used to load the indicator is made through the site base URL, not through the missing page URL. This avoids sending the service request to the 404 page URL.

Current working event
---------------------

In the current version, the plugin uses the OnLoadWebDocument system event.

Plugin properties
-----------------

position  
Defines the position of the block on the screen. It controls near which edge of the window the indicator will be displayed.

version  
Defines the Bootstrap version whose breakpoint rules are used to calculate the current breakpoint.

color  
Sets the text color and border color of the block.

bgcolor  
Sets the background color of the block.

allowed_group  
Defines the name of the MODX manager user group allowed to see the block.

zindex  
Defines the CSS z-index of the block, that is, its stacking priority over other page elements.