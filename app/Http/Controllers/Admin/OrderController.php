<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Order;
use Illuminate\Support\Facades\DB;
class OrderController extends Controller {

    public function __construct(){
        //$this->middleware("auth:api");
    }
    /* Get bank info. */
    public function getOrderList(Request $request) {
        $orderList = Order::all();

        $orderModel = DB::table('orders')->leftJoin('user', 'user.id', '=','orders.user_id' )
        ->leftJoin('channel', 'channel.id', '=','user.channel_id' );
        if($request->phone_number){
            $orderModel = $orderModel->where('user.phone_number','Like', '%' . $request->phone_number . '%');
        }
        if($request->withdraw_status <> -1){
            $orderModel = $orderModel->where('orders.withdraw_status','=', $request->withdraw_status);
        }
        if($request->audit_result <> -1){
            $orderModel = $orderModel->where('orders.audit_result','=', $request->audit_result);
        }
        if($request->withdraw_button <> -1){
            $orderModel = $orderModel->where('orders.withdraw_button','=', $request->withdraw_button);
        }
        if($request->status <> -1){
            $orderModel = $orderModel->where('orders.status','=', $request->status);
        }
        $orderList = $orderModel->select('user.*', 'channel.name', 'orders.id', 'orders.*')->get()->toArray();
        return response()->json(['success'=>true, 'orderList' => $orderList]);
    }

    public function updateOrder(Request $request) {
        $data = [];
        $data['withdraw_button'] = $request->withdraw_button;
        $data['withdraw_amount'] = $request->withdraw_amount;
        $data['contract'] = $request->contract;
        $data['status'] = $request->status;

        $userModel = Order::where('id', $request->id)->update($data);
        return response()->json(['success'=>true, 'result' => $userModel]);
    }
}