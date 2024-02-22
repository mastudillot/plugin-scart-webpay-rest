<?php
/**
 * Plugin format 3.0
 * Use for S-cart 7.x
 */
#App\Plugins\Payment\Transbank\AppConfig.php
namespace App\Plugins\Payment\Transbank;

use App\Plugins\Payment\Transbank\Models\PluginModel;
use SCart\Core\Admin\Models\AdminConfig;
use App\Plugins\ConfigDefault;
use App\Plugins\Payment\Transbank\Utils\PluginConstants;

class AppConfig extends ConfigDefault
{
    const ORDER_STATUS_PROCESSING = 2; // Processing
    const ORDER_STATUS_FAILED = 6; // Failed
    const PAYMENT_STATUS = 3; // Paid

    const CONFIG_PREFIX = '_config';

    public function __construct()
    {
        $this->configGroup = PluginConstants::$pluginConfigGroup;
        $this->configCode = PluginConstants::$pluginConfigCode;
        $this->configKey = PluginConstants::$pluginConfigKey;
        $this->scartVersion = PluginConstants::$pluginScartVersion;
        $this->pathPlugin = PluginConstants::$pluginPath;
        $this->title = trans($this->pathPlugin.'::lang.title');
        $this->image = PluginConstants::$pluginImage;
        $this->version = PluginConstants::$pluginVersion;
        $this->auth = PluginConstants::$pluginAuthor;
        $this->link = PluginConstants::$pluginUrl;
    }

    public function install()
    {
        //Check Plugin key exist
        $pluginIsInstalled = AdminConfig::where('key', $this->configKey)->first();
        if ($pluginIsInstalled) {
            return ['error' => 1, 'msg' =>  sc_language_render('admin.plugin.plugin_exist')];
        }

        //Insert plugin config
        $configData = $this->getInitialConfigData();
        $insertFailed = AdminConfig::insert(
            $configData
        );

        if (!$insertFailed) {
            return ['error' => 1, 'msg' => sc_language_render('admin.plugin.install_failed')];
        }

        return (new PluginModel)->installExtension();
    }

    public function uninstall()
    {
        $return = ['error' => 0, 'msg' => ''];
        //Please delete all values inserted in the installation step
        $process = (new AdminConfig)
            ->where('key', $this->configKey)
            ->orWhere('code', $this->configKey.self::CONFIG_PREFIX)
            ->delete();
        if (!$process) {
            $return = [
                'error' => 1,
                'msg' => sc_language_render('admin.plugin.action_error', ['action' => 'Uninstall'])
            ];
        }
        (new PluginModel)->uninstallExtension();
        return $return;
    }
    
    public function enable()
    {
        $return = ['error' => 0, 'msg' => ''];
        $process = (new AdminConfig)->where('key', $this->configKey)->update(['value' => self::ON]);
        if (!$process) {
            $return = ['error' => 1, 'msg' => 'Error enable'];
        }
        return $return;
    }

    public function disable()
    {
        $return = ['error' => 0, 'msg' => ''];
        $process = (new AdminConfig)
            ->where('key', $this->configKey)
            ->update(['value' => self::OFF]);
        if (!$process) {
            $return = ['error' => 1, 'msg' => 'Error disable'];
        }
        return $return;
    }

    public function config()
    {
        //redirect to url config of plugin
        return redirect(sc_route_admin('admin_webpayplus.index'));
    }

    public function getData()
    {
        return [
            'title' => $this->title,
            'code' => $this->configCode,
            'key' => $this->configKey,
            'image' => $this->image,
            'permission' => self::ALLOW,
            'version' => $this->version,
            'auth' => $this->auth,
            'link' => $this->link,
            'value' => 0, // this return need for plugin shipping
            'pathPlugin' => $this->pathPlugin
        ];
    }

    /**
     * Process after order success
     *
     * @param   [array]  $data
     *
     */
    public function endApp($data = []) {
        //action after end app
    }

    private function getInitialConfigData() {
        return [
            [
                'group'  => $this->configGroup,
                'code'   => $this->configCode,
                'key'    => $this->configKey,
                'sort'   => 0,
                'value'  => self::ON, //Enable extension
                'detail' => $this->pathPlugin.'::lang.title',
            ],
            [
                'group'  => $this->configGroup,
                'code' => $this->configKey.self::CONFIG_PREFIX,
                'key' => $this->configKey.'_commerce_code',
                'sort' => 0, // Sort extensions in group
                'value' => '',
                'detail' => $this->pathPlugin.'::lang.admin.commerce_code',
            ],
            [
                'group'  => $this->configGroup,
                'code' => $this->configKey.self::CONFIG_PREFIX,
                'key' => $this->configKey.'_api_key',
                'sort' => 0, // Sort extensions in group
                'value' => '',
                'detail' => $this->pathPlugin.'::lang.admin.api_key',
            ],
            [
                'group'  => $this->configGroup,
                'code' => $this->configKey.self::CONFIG_PREFIX,
                'key' => $this->configKey.'_environment',
                'sort' => 0, // Sort extensions in group
                'value' => 'integration',
                'detail' => $this->pathPlugin.'::'. $this->configKey . '.environment',
            ],
            // Payment status
            [
                'group'  => $this->configGroup,
                'code' => $this->configKey.self::CONFIG_PREFIX,
                'key' => $this->configKey.'_order_status_success',
                'sort' => 0, // Sort extensions in group
                'value' => self::ORDER_STATUS_PROCESSING,
                'detail' => $this->pathPlugin.'::lang.admin.order_status_success',
            ],
            [
                'group'  => $this->configGroup,
                'code' => $this->configKey.self::CONFIG_PREFIX,
                'key' => $this->configKey.'_order_status_failed',
                'sort' => 0, // Sort extensions in group
                'value' => self::ORDER_STATUS_FAILED,
                'detail' => $this->pathPlugin.'::lang.admin.order_status_failed',
            ],
            [
                'group'  => $this->configGroup,
                'code' => $this->configKey.self::CONFIG_PREFIX,
                'key' => $this->configKey.'_payment_status',
                'sort' => 0, // Sort extensions in group
                'value' => self::PAYMENT_STATUS,
                'detail' => $this->pathPlugin.'::lang.admin.payment_status',
            ],
        ];
    }
}
