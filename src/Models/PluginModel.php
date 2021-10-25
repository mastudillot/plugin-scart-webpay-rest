<?php
#App\Plugins\Payment\WebpayPlus\Models\PluginModel.php
namespace App\Plugins\Payment\WebpayPlus\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PluginModel extends Model
{
    public $timestamps    = false;
    public $table = 'tbk_webpay_transactions';
    protected $connection = SC_CONNECTION;
    protected $guarded    = [];

    public function uninstallExtension()
    {
        return ['error' => 0, 'msg' => 'uninstall success'];
    }

    public function installExtension()
    {
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function (Blueprint $table) {
                $table->increments('id');
                $table->integer('order_id');
                $table->integer('amount');
                $table->string('token', 100)->unique();
                $table->string('session_id', 100)->nullable();
                $table->string('status', 50);
                $table->longText('transbank_response')->nullable();
                $table->string('transbank_status', 50)->nullable();
                $table->string('transbank_product', 50);
                $table->string('transbank_environment', 50)->nullable();
                $table->timestamps();
            });
        }

        return ['error' => 0, 'msg' => 'install success'];
    }
    
}
