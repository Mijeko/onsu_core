<?php

class ConsVar
{
   const IMG_LOADER = '/img/ajax-loader.gif';

   public static function showLoaderWithTemplatePath () {
      return SITE_TEMPLATE_PATH.self::IMG_LOADER;
   }
}
