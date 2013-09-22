<?php

/**
 *
 * @package     Compressor
 * @version     Version 1.0
 * @author      Connor Smith
 * @copyright   Copyright (c) 2013
 * @link        sphinx.io
 *
 */

namespace Craft;

class CompressorPlugin extends BasePlugin
{
    public function getName()
    {
        return Craft::t('Compressor');
    }

    public function getVersion()
    {
        return '1.0';
    }

    public function getDeveloper()
    {
        return 'Connor Smith';
    }

    public function getDeveloperUrl()
    {
        return 'http://sphinx.io';
    }

    public function hasCpSection()
    {
        return false;
    }

    public function addTwigExtension()
    {
        Craft::import('plugins.compressor.twigextensions.CompressorTwigExtension');

        return new CompressorTwigExtension();
    }
}