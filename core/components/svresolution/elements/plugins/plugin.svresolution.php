<?php

if (!$modx instanceof modX) {
    return;
}

$eventName = '';
if (isset($modx->event) && $modx->event instanceof modSystemEvent) {
    $eventName = $modx->event->name;
}

if ($eventName !== 'OnLoadWebDocument') {
    return;
}

$contextKey = $modx->context->get('key');
if ($contextKey === 'mgr') {
    return;
}

$modx->lexicon->load('svresolution:default');

$version = $modx->getOption('version', $scriptProperties, '5');
$color = $modx->getOption('color', $scriptProperties, '#000000');
$bgcolor = $modx->getOption('bgcolor', $scriptProperties, '#ffffff');
$position = $modx->getOption('position', $scriptProperties, 'left,bottom');
$allowedGroup = $modx->getOption('allowed_group', $scriptProperties, 'Administrator');
$zindex = $modx->getOption('zindex', $scriptProperties, '10000');

$allowedGroup = trim($allowedGroup);

$horizontal = 'left';
$vertical = 'bottom';

$positionParts = explode(',', strtolower($position));
foreach ($positionParts as $positionPart) {
    $positionPart = trim($positionPart);

    if ($positionPart === 'left' || $positionPart === 'right') {
        $horizontal = $positionPart;
    }

    if ($positionPart === 'top' || $positionPart === 'bottom') {
        $vertical = $positionPart;
    }
}

$zindex = preg_replace('/[^0-9]/', '', $zindex);
if ($zindex === '') {
    $zindex = '10000';
}

if (!preg_match('/^#([a-fA-F0-9]{3}|[a-fA-F0-9]{6})$/', $color)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[SVResolution] Неверный формат цвета текста: ' . $color);
    $color = '#000000';
}

if (!preg_match('/^#([a-fA-F0-9]{3}|[a-fA-F0-9]{6})$/', $bgcolor)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[SVResolution] Неверный формат цвета фона: ' . $bgcolor);
    $bgcolor = '#ffffff';
}

$isAllowed = false;

if ($modx->user && $modx->user->isAuthenticated('mgr')) {
    if ($allowedGroup !== '' && $modx->user->isMember($allowedGroup)) {
        $isAllowed = true;
    }
}

/*
 * Служебный запрос SVResolution.
 *
 * Видимый блок больше не вставляется напрямую в HTML страницы.
 * Он отдаётся отдельным JavaScript-запросом только после проверки manager-сессии.
 */
if (isset($_GET['svresolution']) && $_GET['svresolution'] === '1') {
    if (!headers_sent()) {
        header('Content-Type: application/javascript; charset=UTF-8');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
        header('X-Robots-Tag: noindex, nofollow', true);
    }

    if (!$isAllowed) {
        echo '';
        exit;
    }

    $titleBootstrap = $modx->lexicon('svresolution_title_bootstrap', array(
        'version' => $version,
    ));

    $titleVisibleFor = $modx->lexicon('svresolution_title_visible_for', array(
        'group' => $allowedGroup,
    ));

    $title = $titleBootstrap;
    if ($titleVisibleFor !== '') {
        $title .= '. ' . $titleVisibleFor;
    }

    $styleParts = array();
    $styleParts[] = 'position:fixed';
    $styleParts[] = $horizontal . ':0';
    $styleParts[] = $vertical . ':0';
    $styleParts[] = 'z-index:' . $zindex;
    $styleParts[] = 'padding:6px 8px';
    $styleParts[] = 'border-width:1px';
    $styleParts[] = 'border-style:solid';
    $styleParts[] = 'border-color:' . $color;
    $styleParts[] = 'background:' . $bgcolor;
    $styleParts[] = 'color:' . $color;
    $styleParts[] = 'font-size:12px';
    $styleParts[] = 'font-weight:bold';
    $styleParts[] = 'line-height:1.2';
    $styleParts[] = 'opacity:0.92';
    $styleParts[] = 'cursor:default';
    $styleParts[] = 'text-align:center';

    $style = implode(';', $styleParts) . ';';

    echo '
(function() {
    if (window.__svresolutionInitialized) {
        return;
    }

    window.__svresolutionInitialized = true;

    var version = ' . json_encode($version) . ';
    var indicatorStyle = ' . json_encode($style) . ';
    var indicatorTitle = ' . json_encode($title) . ';

    function getBreakpoint(width, bootstrapVersion) {
        bootstrapVersion = String(bootstrapVersion);

        if (bootstrapVersion === "3") {
            if (width >= 1200) { return "LG"; }
            if (width >= 992) { return "MD"; }
            if (width >= 768) { return "SM"; }
            return "XS";
        }

        if (bootstrapVersion === "4") {
            if (width >= 1200) { return "XL"; }
            if (width >= 992) { return "LG"; }
            if (width >= 768) { return "MD"; }
            if (width >= 576) { return "SM"; }
            return "XS";
        }

        if (width >= 1400) { return "XXL"; }
        if (width >= 1200) { return "XL"; }
        if (width >= 992) { return "LG"; }
        if (width >= 768) { return "MD"; }
        if (width >= 576) { return "SM"; }
        return "XS";
    }

    function createIndicator() {
        var indicator = document.getElementById("svresolution-indicator");

        if (indicator) {
            return indicator;
        }

        indicator = document.createElement("div");
        indicator.id = "svresolution-indicator";
        indicator.setAttribute("style", indicatorStyle);
        indicator.setAttribute("title", indicatorTitle);

        indicator.innerHTML =
            "<div><span id=\"svresolution-breakpoint\">?</span></div>" +
            "<div id=\"svresolution-width\" style=\"font-weight:normal;font-size:11px;margin-top:2px;\">W ? px</div>";

        if (document.body) {
            document.body.appendChild(indicator);
        }

        return indicator;
    }

    function updateResolutionInfo() {
        var width = 0;
        var breakpointNode = document.getElementById("svresolution-breakpoint");
        var widthNode = document.getElementById("svresolution-width");

        if (!breakpointNode || !widthNode) {
            return;
        }

        if (document.documentElement && document.documentElement.clientWidth) {
            width = document.documentElement.clientWidth;
        } else if (window.innerWidth) {
            width = window.innerWidth;
        } else if (document.body && document.body.clientWidth) {
            width = document.body.clientWidth;
        }

        breakpointNode.textContent = getBreakpoint(width, version);
        widthNode.textContent = "W " + width + " px";
    }

    function initIndicator() {
        createIndicator();

        if (window.addEventListener) {
            window.addEventListener("resize", updateResolutionInfo);
        } else if (window.attachEvent) {
            window.attachEvent("onresize", updateResolutionInfo);
        }

        updateResolutionInfo();
    }

    if (document.readyState === "loading") {
        if (document.addEventListener) {
            document.addEventListener("DOMContentLoaded", initIndicator);
        } else {
            window.attachEvent("onload", initIndicator);
        }
    } else {
        initIndicator();
    }
})();
';

    exit;
}

$baseUrl = $modx->getOption('base_url', null, '/');
$baseUrl = trim($baseUrl);

if ($baseUrl === '') {
    $baseUrl = '/';
}

if (strpos($baseUrl, '/') !== 0) {
    $baseUrl = '/' . $baseUrl;
}

if (substr($baseUrl, -1) !== '/') {
    $baseUrl .= '/';
}

/*
 * Обычная страница.
 *
 * Здесь вставляется только безопасный загрузчик.
 * Сам видимый блок запрашивается отдельно с базового адреса сайта.
 * Поэтому на 404-страницах служебный запрос не должен получать 404.
 */
$loader = '
<!-- SVResolution loader. The visible block is loaded only after a separate manager-session check. -->
<script>
(function() {
    if (window.__svresolutionLoaderInitialized) {
        return;
    }

    window.__svresolutionLoaderInitialized = true;

    function loadSVResolution() {
        var script = document.createElement("script");
        var servicePath = ' . json_encode($baseUrl) . ';
        var url = window.location.protocol + "//" + window.location.host + servicePath;
        var query = "svresolution=1";

        query = query + "&_svresolution=" + new Date().getTime();

        script.src = url + "?" + query;
        script.async = true;

        if (document.body) {
            document.body.appendChild(script);
        }
    }

    if (document.readyState === "loading") {
        if (document.addEventListener) {
            document.addEventListener("DOMContentLoaded", loadSVResolution);
        } else {
            window.attachEvent("onload", loadSVResolution);
        }
    } else {
        loadSVResolution();
    }
})();
</script>
';

$modx->regClientHTMLBlock($loader);

return;
