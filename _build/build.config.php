<?php
/**
 * Build config for SVResolution.
 */

$mtime = microtime();
$mtime = explode(' ', $mtime);
$tstart = $mtime[1] + $mtime[0];

set_time_limit(0);

if (!defined('SVR_PKG_NAME')) {
    define('SVR_PKG_NAME', 'SVResolution');
}

if (!defined('SVR_PKG_NAME_LOWER')) {
    define('SVR_PKG_NAME_LOWER', 'svresolution');
}

if (!defined('SVR_PKG_VERSION')) {
    define('SVR_PKG_VERSION', '1.0.3');
}

if (!defined('SVR_PKG_RELEASE')) {
    define('SVR_PKG_RELEASE', 'pl');
}

$root = dirname(dirname(__FILE__)) . '/';
$core = '';

/**
 * Проверяет, что путь действительно указывает на MODX core.
 *
 * @param string $path
 * @return bool
 */
function svrIsValidCorePath($path)
{
    $path = trim($path);

    if ($path === '') {
        return false;
    }

    $path = rtrim($path, '/') . '/';

    return is_file($path . 'model/modx/modx.class.php')
        && is_file($path . 'model/modx/transport/modpackagebuilder.class.php');
}

/**
 * Нормализует путь, добавляя завершающий слэш.
 *
 * @param string $path
 * @return string
 */
function svrNormalizePath($path)
{
    return rtrim(trim($path), '/') . '/';
}

/*
 * Первый вариант: путь к core можно передать через переменную окружения:
 *
 * MODX_CORE_PATH=/path/to/modx/core/ php _build/build.transport.php
 */
if (getenv('MODX_CORE_PATH') !== false && getenv('MODX_CORE_PATH') !== '') {
    $envCore = svrNormalizePath(getenv('MODX_CORE_PATH'));

    if (svrIsValidCorePath($envCore)) {
        $core = $envCore;
    }
}

/*
 * Второй вариант: проект лежит внутри сайта MODX или рядом с ним,
 * и рядом можно найти config.core.php.
 */
if ($core === '') {
    $configPaths = array(
        $root . 'config.core.php',
        dirname($root) . '/config.core.php',
        dirname(dirname($root)) . '/config.core.php',
    );

    foreach ($configPaths as $configPath) {
        if (is_file($configPath)) {
            require $configPath;

            if (defined('MODX_CORE_PATH')) {
                $configCore = svrNormalizePath(MODX_CORE_PATH);

                if (svrIsValidCorePath($configCore)) {
                    $core = $configCore;
                    break;
                }
            }
        }
    }
}

/*
 * Третий вариант: рядом с проектом есть папка core.
 */
if ($core === '') {
    $nearbyCorePaths = array(
        $root . 'core/',
        dirname($root) . '/core/',
        dirname(dirname($root)) . '/core/',
    );

    foreach ($nearbyCorePaths as $nearbyCorePath) {
        $nearbyCorePath = svrNormalizePath($nearbyCorePath);

        if (svrIsValidCorePath($nearbyCorePath)) {
            $core = $nearbyCorePath;
            break;
        }
    }
}

if ($core === '') {
    echo "Could not determine valid MODX_CORE_PATH.\n";
    echo "Set MODX_CORE_PATH environment variable or place the build project near config.core.php.\n";
    exit(1);
}

$core = svrNormalizePath($core);

if (!defined('MODX_CORE_PATH')) {
    define('MODX_CORE_PATH', $core);
}

if (!defined('MODX_CONFIG_KEY')) {
    define('MODX_CONFIG_KEY', 'config');
}

$sources = array(
    'root' => $root,
    'build' => $root . '_build/',
    'data' => $root . '_build/data/',
    'source_core' => $root . 'core/components/' . SVR_PKG_NAME_LOWER . '/',
    'elements' => $root . 'core/components/' . SVR_PKG_NAME_LOWER . '/elements/',
    'plugins' => $root . 'core/components/' . SVR_PKG_NAME_LOWER . '/elements/plugins/',
    'lexicon' => $root . 'core/components/' . SVR_PKG_NAME_LOWER . '/lexicon/',
    'docs' => $root . 'core/components/' . SVR_PKG_NAME_LOWER . '/docs/',
    'packages' => MODX_CORE_PATH . 'packages/',
    'model' => MODX_CORE_PATH . 'model/',
);