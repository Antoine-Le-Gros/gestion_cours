<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@hotwired/turbo' => [
        'version' => '7.3.0',
    ],
    'react' => [
        'version' => '18.3.1',
    ],
    'react-dom' => [
        'version' => '18.3.1',
    ],
    'scheduler' => [
        'version' => '0.23.2',
    ],
    '@symfony/ux-react' => [
        'path' => './vendor/symfony/ux-react/assets/dist/loader.js',
    ],
    'wouter' => [
        'version' => '3.3.5',
    ],
    'regexparam' => [
        'version' => '3.0.0',
    ],
    'use-sync-external-store/shim/index.js' => [
        'version' => '1.2.2',
    ],
    'bootstrap' => [
        'version' => '5.3.3',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '5.3.3',
        'type' => 'css',
    ],
    'react-google-charts' => [
        'version' => '5.2.1',
    ],
    'prop-types' => [
        'version' => '15.8.1',
    ],
    'affectation' => [
        'path' => './assets/js/affectation.js',
    ],
];
