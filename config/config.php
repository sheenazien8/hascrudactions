<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    /**
     * this key config settings for model folder
     * the default folder is App\\
     */
    'model_folder' => 'App\\',

    /**
     * you can modify the default return method
     * default value view,
     * available value view, api
     */
    'return' => 'view',
    /**
     * Wrapper view, you can adjust static view like footer adn header
     * put file in views path
     * layouts:
     * section:
     * javascript:
     */
    'wrapper' => [
        'layouts' => '',
        'section' => '',
        'javascript' => ''
    ]
];
