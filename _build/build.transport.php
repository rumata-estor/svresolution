<?php

require_once dirname(__FILE__) . '/build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
require_once MODX_CORE_PATH . 'model/modx/transport/modpackagebuilder.class.php';

$modx = new modX();
$modx->initialize('mgr');

$modx->setLogLevel(modX::LOG_LEVEL_INFO);
if (XPDO_CLI_MODE) {
    $modx->setLogTarget('ECHO');
} else {
    $modx->setLogTarget('HTML');
}

/**
 * Читает текстовый файл документации.
 *
 * @param string $file
 * @return string
 */
function svrReadFile($file)
{
    if (is_file($file)) {
        $content = file_get_contents($file);
        if ($content !== false) {
            return $content;
        }
    }

    return '';
}

$modx->log(modX::LOG_LEVEL_INFO, '=== ' . SVR_PKG_NAME . ' package build started ===');

$builder = new modPackageBuilder($modx);
$builder->createPackage(SVR_PKG_NAME_LOWER, SVR_PKG_VERSION, SVR_PKG_RELEASE);
$builder->registerNamespace(
    SVR_PKG_NAME_LOWER,
    false,
    true,
    '{core_path}components/' . SVR_PKG_NAME_LOWER . '/'
);

$category = $modx->newObject('modCategory');
$category->set('category', SVR_PKG_NAME);

$pluginFile = $sources['data'] . 'transport.plugin.php';
if (!is_file($pluginFile)) {
    $modx->log(modX::LOG_LEVEL_ERROR, 'Plugin transport file not found: ' . $pluginFile);
    exit(1);
}

$plugin = require $pluginFile;
if (!$plugin || !($plugin instanceof modPlugin)) {
    $modx->log(modX::LOG_LEVEL_ERROR, 'transport.plugin.php must return an instance of modPlugin.');
    exit(1);
}

$plugins = array($plugin);
$category->addMany($plugins, 'Plugins');

$vehicleAttributes = array(
    xPDOTransport::UNIQUE_KEY => 'category',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::ABORT_INSTALL_ON_VEHICLE_FAIL => true,

    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array(
        'Plugins' => array(
            xPDOTransport::UNIQUE_KEY => 'name',
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::RELATED_OBJECTS => true,
        ),
        'PluginEvents' => array(
            xPDOTransport::UNIQUE_KEY => array('pluginid', 'event'),
            xPDOTransport::PRESERVE_KEYS => true,
            xPDOTransport::UPDATE_OBJECT => false,
            xPDOTransport::RELATED_OBJECTS => true,
        ),
    ),
);

$vehicle = $builder->createVehicle($category, $vehicleAttributes);

$vehicle->resolve('file', array(
    'source' => $sources['source_core'],
    'target' => "return MODX_CORE_PATH . 'components/';",
));

$builder->putVehicle($vehicle);

$builder->setPackageAttributes(array(
    'license' => svrReadFile($sources['docs'] . 'license.txt'),
    'readme' => svrReadFile($sources['docs'] . 'readme.txt'),
    'changelog' => svrReadFile($sources['docs'] . 'changelog.txt'),
));

$builder->pack();

$modx->log(modX::LOG_LEVEL_INFO, '=== ' . SVR_PKG_NAME . ' package build finished ===');