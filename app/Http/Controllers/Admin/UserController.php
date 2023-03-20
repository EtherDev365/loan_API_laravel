<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller {

    public function __construct(){
        //$this->middleware("auth:api");
    }
    /* Get bank info. */
    public function getUserList(Request $request) {
        $userModel = DB::table('user')->leftJoin('channel', 'channel.id', '=','user.channel_id' );
        if($request->phone_number){
            $userModel = $userModel->where('user.phone_number','Like', '%' . $request->phone_number . '%');
        }

        if($request->id_card_name){
            $userModel = $userModel->where('user.id_card_name','Like', '%' . $request->id_card_name . '%');
        }
        if($request->channel_id){
            $userModel = $userModel->where('user.channel_id','=', $request->channel_id);
        }
        $userList = $userModel->select('user.*', 'channel.name')->get()->toArray();
        return response()->json(['success'=>true, 'userList' => $userList]);
    }

    public function getUserById(Request $request) {
        $userModel = DB::table('user')->leftJoin('channel', 'channel.id', '=','user.channel_id' )
        ->where('user.id','=', $request->id)->first();
        return response()->json(['success'=>true, 'user' => $userModel]);
    }

    public function updateUserLoginStatus(Request $request) {
        $data = [];
        $data['login_status'] = $request->login_status;
        $userModel = User::where('id', $request->id)->update($data);
        return response()->json(['success'=>true, 'result' => $userModel]);
    }

    public function updateUserMainInformation(Request $request) {
        $data = [];
        $data['phone_number'] = $request->phone_number;
        $data['login_status'] = $request->login_status;
        $data['status'] = $request->status;
        $data['password'] = $request->password;
        $userModel = User::where('id', $request->id)->update($data);
        return response()->json(['success'=>true, 'result' => $userModel]);
    }

    public function deleteUser(Request $request) {
        $userModel = User::where('id', $request->id)->delete();
        return response()->json(['success'=>true, 'result' => $userModel]);
    }
}