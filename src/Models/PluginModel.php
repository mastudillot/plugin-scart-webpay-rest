<?php
#App\Plugins\Payment\Transbank\Models\PluginModel.php
namespace App\Plugins\Payment\Transbank\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PluginModel extends Model
{
    public $timestamps    = false;
    public $table = SC_DB_PREFIX.'webpay_transactions';
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
                $table->string('order_id', 100);
                $table->integer('amount');
                $table->string('token', 100)->unique();
                $table->string('session_id', 100)->nullable();
                $table->string('status', 50);
                $table->longText('transbank_response')->nullable();
                $table->string('transbank_status', 50)->nullable();
                $table->string('transbank_product', 50);
                $table->string('transbank_environment', 50)->nullable();
                $table->timestamps();
                $table->foreign('order_id')->references('id')->on(SC_DB_PREFIX.'shop_order');
            });
        }

        return ['error' => 0, 'msg' => 'install success'];
    }
    
}
