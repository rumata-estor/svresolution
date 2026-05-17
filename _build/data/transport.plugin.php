<?php

$plugin = $modx->newObject('modPlugin');
$plugin->set('name', SVR_PKG_NAME);
$plugin->set('description', 'Shows Bootstrap breakpoint and screen width for logged-in administrators.');
$plugin->set('plugincode', '');

$pluginCodeFile = $sources['source_core'] . 'elements/plugins/plugin.svresolution.php';
if (is_file($pluginCodeFile)) {
    $pluginCode = file_get_contents($pluginCodeFile);
    if ($pluginCode !== false) {
        $plugin->set('plugincode', $pluginCode);
    }
}

$properties = array(
    'position' => array(
        'name' => 'position',
        'desc' => 'svresolution_prop_position_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'left,bottom',
        'lexicon' => 'svresolution:default',
        'area' => 'svresolution_main',
    ),
    'version' => array(
        'name' => 'version',
        'desc' => 'svresolution_prop_version_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '5',
        'lexicon' => 'svresolution:default',
        'area' => 'svresolution_main',
    ),
    'color' => array(
        'name' => 'color',
        'desc' => 'svresolution_prop_color_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '#000000',
        'lexicon' => 'svresolution:default',
        'area' => 'svresolution_main',
    ),
    'bgcolor' => array(
        'name' => 'bgcolor',
        'desc' => 'svresolution_prop_bgcolor_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '#ffffff',
        'lexicon' => 'svresolution:default',
        'area' => 'svresolution_main',
    ),
    'allowed_group' => array(
        'name' => 'allowed_group',
        'desc' => 'svresolution_prop_allowed_group_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'Administrator',
        'lexicon' => 'svresolution:default',
        'area' => 'svresolution_main',
    ),
    'zindex' => array(
        'name' => 'zindex',
        'desc' => 'svresolution_prop_zindex_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '10000',
        'lexicon' => 'svresolution:default',
        'area' => 'svresolution_main',
    ),
);

$plugin->setProperties($properties);

$events = array();

$event = $modx->newObject('modPluginEvent');
$event->fromArray(array(
    'event' => 'OnLoadWebDocument',
    'priority' => 0,
    'propertyset' => 0,
), '', true, true);
$events[] = $event;

$plugin->addMany($events, 'PluginEvents');

return $plugin;