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

class CompressorService extends BaseApplicationComponent
{
    protected $cache_dir;
    protected $cache_url;
    protected $document_root;

    public function __construct()
    {
        $this->document_root = craft()->config->get('documentRoot');
        $this->cache_dir = $this->document_root . "/cache";
        $this->cache_url = Craft::getSiteUrl() . "/cache";
    }

    private function recache($files, $ext)
    {
      $modified_times = array();

      foreach($files as $file)
      {
        if (strpos($file, '.com') === false)
        {
          $file_path = $this->document_root . $file;
          if (!is_file($file_path)) throw new Exception(Craft::t("$file_path is not a valid file!"));
          $modified_times[] = filemtime($file_path);
        }
      }

      $md5 = md5(json_encode($files) . json_encode($modified_times));

      $cached_files = glob($this->cache_dir . "/cached.$md5.$ext");

      if (empty($cached_files)) return array('recache' => true, 'md5' => $md5);

      $cached_file = end($cached_files);

      return array('recache' => false, 'cache_file' => basename($cached_file));
    }

    private function makeCachePath($file) 
    {
        return $this->cache_dir . '/' . ltrim($file, '/');
    }

    private function makeCacheUrl($file)
    {
        return $this->cache_url . '/' . ltrim($file, '/');
    }

    private function outputCss($file)
    {
        $html = "<link rel=\"stylesheet\" href=\"" . $this->makeCacheUrl($file) . "\">";
 
        $charset = craft()->templates->getTwig()->getCharset();
        return new \Twig_Markup($html, $charset);
    }

    private function outputJs($file)
    {
        $html = "<script src=\"" . $this->makeCacheUrl($file) . "\"></script>";
 
        $charset = craft()->templates->getTwig()->getCharset();
        return new \Twig_Markup($html, $charset);
    }

    public function css($css)
    {
      /*require_once(__DIR__ . '/../lib/Minify/CSS.php');
      require_once(__DIR__ . '/../lib/Minify/CSS/Compressor.php');
      require_once(__DIR__ . '/../lib/Minify/CommentPreserver.php');*/

      if (!is_array($css)) $css = array($css);

      $recache = $this->recache($css, "css");
      if ($recache['recache'] === false) 
      {
          return $this->outputCss($recache['cache_file']);
      }
      else if ($recache['recache'] === true)
      {
        $cached_file = $this->makeCachePath("cached." . $recache['md5'] . ".css");

        $css_content = "";

        foreach($css as $file)
        {
          $file = (strpos($file, '.com') === false) ?  $this->document_root . $file : $file;
          $css_content .= file_get_contents($file);
        }

        $css = trim($css_content);
        $css = str_replace("\r\n", "\n", $css);
        $search = array("/\/\*[^!][\d\D]*?\*\/|\t+/", "/\s+/", "/\}\s+/");
        $replace = array(null, " ", "}\n");
        $css = preg_replace($search, $replace, $css);
        $search = array("/;[\s+]/", "/[\s+];/", "/\s+\{\\s+/", "/\\:\s+\\#/", "/,\s+/i", "/\\:\s+\\\'/i", "/\\:\s+([0-9]+|[A-F]+)/i", "/\{\\s+/", "/;}/");
        $replace = array(";", ";", "{", ":#", ",", ":\'", ":$1", "{", "}");
        $css = preg_replace($search, $replace, $css);
        $css = str_replace("\n", null, $css);

        //$css = \Minify_CSS::minify($css_content, array('preserve_comments' => true));

        file_put_contents($cached_file, $css);
        return $this->outputCss(basename($cached_file));
      }
    }

    public function js($js)
    {
      require_once(__DIR__ . '/../lib/Minify/JS/ClosureCompiler.php');

      if (!is_array($js)) $js = array($js);
      
      $recache = $this->recache($js, "js");
      if ($recache['recache'] === false)
      {
        return $this->outputJs($recache['cache_file']);
      }
      else if ($recache['recache'] === true)
      {
        $cached_file = $this->makeCachePath("cached." . $recache['md5'] . ".js");

        $js_content = "";

        foreach($js as $file)
        {
          $file = (strpos($file, '.com') === false) ?  $this->document_root . $file : $file;
          $js_content .= file_get_contents($file);
        }

        $minified = \Minify_JS_ClosureCompiler::minify($js_content);

        file_put_contents($cached_file, $minified);
        return $this->outputJs(basename($cached_file));
      }
    }
}