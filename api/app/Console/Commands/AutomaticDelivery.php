<?php

namespace App\Console\Commands;

use App\Code;
use App\Models\v1\Dhl;
use App\Models\v1\GoodIndent;
use App\Notifications\Common;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutomaticDelivery extends Command
{
    /**
     * 自动发货
     *
     * @var string
     */
    protected $signature = 'automatic:delivery';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'System Automated Delivery。';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $GoodIndent = GoodIndent::where('state', GoodIndent::GOOD_INDENT_STATE_DELIVER)->with(['User','GoodLocation'])->get();
        if ($GoodIndent) {
            foreach ($GoodIndent as $G) {
                DB::transaction(function ()use($G){
                    $GoodIndent=GoodIndent::find($G->id);
                    $GoodIndent->dhl_id = 3;
                    $GoodIndent->odd = '777032723314255';
                    $GoodIndent->state = GoodIndent::GOOD_INDENT_STATE_TAKE;
                    $GoodIndent->shipping_time = Carbon::now()->toDateTimeString();
                    $GoodIndent->save();
                    $Dhl=Dhl::find($GoodIndent->dhl_id);
                    (new Common)->deliveryRelease([
                        'id'=>$GoodIndent->id,  //订单ID
                        'identification'=>$GoodIndent->identification,  //订单号
                        'dhl'=>$Dhl->name,  //快递公司
                        'odd'=>$GoodIndent->odd,   // 快递单号
                        'total'=>$GoodIndent->total,    //订单金额
                        'shipping_time'=>$GoodIndent->shipping_time,    //发货时间
                        'location'=>$G->GoodLocation,    //收货地址
                        'template'=>'delivery_release',   //通知模板标识
                        'user_id'=>$G->User->id    //用户ID
                    ]);
                });
            }
        }
    }
}
