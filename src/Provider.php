<?php
/**
 * Provides everything needed for the Plugin
 */
    $this->loadTranslationsFrom(__DIR__.'/Lang', 'Plugins/Payment/WebpayPlus');
    $this->loadViewsFrom(__DIR__.'/Views', 'Plugins/Payment/WebpayPlus');

    if (sc_config('WebpayPlus')) {
      // Revisar que se puede hacer
      // $this->mergeConfigFrom(
      //     __DIR__.'/config.php', 'key_define_for_plugin'
      // );
    }
