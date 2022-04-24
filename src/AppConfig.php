<?php
/**
 * Plugin format 2.0
 */
#App\Plugins\Payment\WebpayPlus\AppConfig.php
namespace App\Plugins\Payment\WebpayPlus;

use App\Plugins\Payment\WebpayPlus\Models\PluginModel;
use SCart\Core\Admin\Models\AdminConfig;
use App\Plugins\ConfigDefault;

class AppConfig extends ConfigDefault
{
    const ORDER_STATUS_PROCESSING = 2; // Processing
    const ORDER_STATUS_FAILED = 6; // Failed
    const PAYMENT_STATUS = 3; // Paid

    const CONFIG_PREFIX = '_config';

    public function __construct()
    {
        //Read config from config.json
        $config = file_get_contents(__DIR__.'/config.json');
        $config = json_decode($config, true);
    	$this->configGroup = $config['configGroup'];
    	$this->configCode = $config['configCode'];
        $this->configKey = $config['configKey'];
        $this->scartVersion = $config['scartVersion'];
        //Path
        $this->pathPlugin = $this->configGroup . '/' . $this->configCode . '/' . $this->configKey;
        //Language
        $this->title = trans($this->pathPlugin.'::lang.title');
        //Image logo or thumb
        $this->image = $this->pathPlugin.'/'.$config['image'];
        //
        $this->version = $config['version'];
        $this->auth = $config['auth'];
        $this->link = $config['link'];
    }

    public function install()
    {
        $check = AdminConfig::where('key', $this->configKey)->first();
        if ($check) {
            //Check Plugin key exist
            $return = ['error' => 1, 'msg' =>  sc_language_render('admin.plugin.plugin_exist')];
        } else {
            //Insert plugin to config
            $dataInsert = [
                [
                    'group'  => $this->configGroup,
                    'code'   => $this->configCode,
                    'key'    => $this->configKey,
                    'sort'   => 0,
                    'value'  => self::ON, //Enable extension
                    'detail' => $this->pathPlugin.'::lang.title',
                ],
                [
                    'group' => '',
                    'code' => $this->configKey.self::CONFIG_PREFIX,
                    'key' => $this->configKey.'_commerce_code',
                    'sort' => 0, // Sort extensions in group
                    'value' => '',
                    'detail' => $this->pathPlugin.'::lang.admin.commerce_code',
                ],
                [
                    'group' => '',
                    'code' => $this->configKey.self::CONFIG_PREFIX,
                    'key' => $this->configKey.'_api_key',
                    'sort' => 0, // Sort extensions in group
                    'value' => '',
                    'detail' => $this->pathPlugin.'::lang.admin.api_key',
                ],
                [
                    'group' => '',
                    'code' => $this->configKey.self::CONFIG_PREFIX,
                    'key' => $this->configKey.'_environment',
                    'sort' => 0, // Sort extensions in group
                    'value' => 'integration',
                    'detail' => $this->pathPlugin.'::'. $this->configKey . '.environment',
                ],
                // Payment status
                [
                    'group' => '',
                    'code' => $this->configKey.self::CONFIG_PREFIX,
                    'key' => $this->configKey.'_order_status_success',
                    'sort' => 0, // Sort extensions in group
                    'value' => self::ORDER_STATUS_PROCESSING,
                    'detail' => $this->pathPlugin.'::lang.admin.order_status_success',
                ],
                [
                    'group' => '',
                    'code' => $this->configKey.self::CONFIG_PREFIX,
                    'key' => $this->configKey.'_order_status_failed',
                    'sort' => 0, // Sort extensions in group
                    'value' => self::ORDER_STATUS_FAILED,
                    'detail' => $this->pathPlugin.'::lang.admin.order_status_failed',
                ],
                [
                    'group' => '',
                    'code' => $this->configKey.self::CONFIG_PREFIX,
                    'key' => $this->configKey.'_payment_status',
                    'sort' => 0, // Sort extensions in group
                    'value' => self::PAYMENT_STATUS,
                    'detail' => $this->pathPlugin.'::lang.admin.payment_status',
                ],
            ];
            $process = AdminConfig::insert(
                $dataInsert
            );

            /*Insert plugin's html elements into index of admin pages
            Detail: https://s-cart.org/docs/master/create-new-a-plugin.html 
            */

            // AdminConfig::insert(
            //     [
            //         /*
            //         This is where the html content of the Plugin appears
            //         group_of_layout allow:
            //         Position include "topMenuRight, topMenuLeft, menuLeft,menuRight, blockBottom" -> Show on all index pages in admin with corresponding position as above.
            //         or Position_route_name_of_admin_page. Example menuLeft__admin_product.index, topMenuLeft__admin_order.index
            //         */
            //         'group' => 'group_of_layout',
            //         /*
            //         code is value option
            //         */
            //         'code' => 'code_config_of_plugin',
            //         'key' => 'key_with_value_unique', //
            //         'sort' => 0, // int value
            //         'value' => 'html content or view::path_to_view', // allow html or view::path_to_view
            //         'detail' => '',
            //     ]
            // );
            if (!$process) {
                $return = ['error' => 1, 'msg' => sc_language_render('admin.plugin.install_failed')];
            } else {
                $return = (new PluginModel)->installExtension();
            }
        }

        return $return;
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
            $return = ['error' => 1, 'msg' => sc_language_render('admin.plugin.action_error', ['action' => 'Uninstall'])];
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
}
