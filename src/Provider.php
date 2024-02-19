<?php
/**
 * Provides everything needed for the Plugin
 */
    $this->loadTranslationsFrom(__DIR__.'/Lang', 'Plugins/Payment/Transbank');
    $this->loadViewsFrom(__DIR__.'/Views', 'Plugins/Payment/Transbank');

    if (sc_config('Transbank')) {
      // Revisar que se puede hacer
      // $this->mergeConfigFrom(
      //     __DIR__.'/config.php', 'key_define_for_plugin'
      // );
    }
