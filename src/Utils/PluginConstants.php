<?php

namespace App\Plugins\Payment\Transbank\Utils;

class PluginConstants
{

    public static $pluginConfigGroup;
    public static $pluginConfigCode;
    public static $pluginConfigKey;
    public static $pluginScartVersion;
    public static $pluginImage;
    public static $pluginVersion;
    public static $pluginAuthor;
    public static $pluginUrl;
    public static $pluginPath;
    public static $configPrefix;
    public static $configCode;
    public static $configCommerceCodeKey;
    public static $configApiKey;
    public static $configEnvironmentKey;
    public static $configOrderStatusSuccessKey;
    public static $configOrderStatusFailedKey;
    public static $configPaymentStatusKey;

    public static function initializeConfig(): void
    {
        self::$pluginConfigGroup = self::getConfigFromFile('configGroup');
        self::$pluginConfigCode = self::getConfigFromFile('configCode');
        self::$pluginConfigKey = self::getConfigFromFile('configKey');
        self::$pluginScartVersion = self::getConfigFromFile('scartVersion');
        self::$pluginImage = self::$pluginPath . '/' . self::getConfigFromFile('image');
        self::$pluginVersion = self::getConfigFromFile('version');
        self::$pluginAuthor = self::getConfigFromFile('auth');
        self::$pluginUrl = self::getConfigFromFile('link');
        self::$pluginPath = self::$pluginConfigGroup . '/' . self::$pluginConfigGroup . '/' . self::$pluginConfigKey;
        self::$configPrefix = '_config';
        self::$configCode = self::$pluginConfigKey . self::$configPrefix;
        self::$configCommerceCodeKey = self::$pluginConfigKey . '_commerce_code';
        self::$configApiKey = self::$pluginConfigKey . '_api_key';
        self::$configEnvironmentKey = self::$pluginConfigKey . '_environment';
        self::$configOrderStatusSuccessKey = self::$pluginConfigKey . '_order_status_success';
        self::$configOrderStatusFailedKey = self::$pluginConfigKey . '_order_status_failed';
        self::$configPaymentStatusKey = self::$pluginConfigKey . '_payment_status';
    }

    private static function getConfigFromFile(string $key): string
    {
        $configFileContent = file_get_contents(__DIR__ . '/../config.json');
        $config = json_decode($configFileContent, true);
        return $config[$key] ?? '';
    }
}

PluginConstants::initializeConfig();
