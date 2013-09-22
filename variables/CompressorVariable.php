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

class CompressorVariable
{
    public function css($files)
    {
        return craft()->compressor->css($files);
    }

    public function js($files)
    {
        return craft()->compressor->js($files);
    }
}