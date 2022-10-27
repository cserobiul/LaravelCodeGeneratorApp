<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location;
use Barryvdh\DomPDF\Facade as PDF;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __invoke()
    {
        return view('code.create');
    }


    public function print(){
        $data = User::where('id',1)->first();
        $pdf = PDF::loadView('backend.user.print',compact('data'));
        return $pdf->stream('certificate_print.pdf');

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        return view('home');
    }
     public function userList(Request $request)
    {
        $users = User::select("*");

        if ($request->has('view_deleted')) {
            $users = $users->onlyTrashed();
        }

        $users = $users->paginate(8);

        return view('user.index', compact('users'));
    }

    public function onlineUserList()
    {

        $data['users'] = User::select("*")
            ->whereNotNull('last_seen')
            ->orderBy('last_seen', 'DESC')
            ->paginate(10);

        $ip = '20.185.38.187'; /* Static IP address */


        $data['currentUserInfo'] = Location::get($ip);


        \Log::channel('apolcustomlog')->info('some try to view all online user list!!');

        return view('user.online_user',$data);
    }

    public function userDelete($id){

        User::find($id)->delete();
        $adminName = Auth::user()->name;
        \Log::channel('apolcustomlog')->info($adminName.' delete someone!!');
        return back()->with('success','User deleted.');
    }

    public function userRestore($id){
        User::withTrashed()->find($id)->restore();

        return back();
    }

    public function restoreAll(){
        User::onlyTrashed()->restore();
        return back();
    }


    public function codeCreate(){
        return view('code.create');
    }
     public function codeStore(Request $request){

        $request->validate([
            'model_name' => ['required','string'],
        ],[
            'model_name.required' => 'Model Name Require'
        ]);

         $data['model_name'] = $request->model_name;
         $data['location'] = $request->location;
         $data['items'] = $request->item_name;

         return view('code.index',$data);
//         return view('code.index',$data);
    }

     public function codeIndex(){
        return view('code.index');
    }

    public function socialShare(){
        $shareButtons = \Share::page(
            'https://www.itsolutionstuff.com',
            'Your share text comes here',
        )
            ->facebook()
            ->twitter()
            ->linkedin()
            ->telegram()
            ->whatsapp()
            ->reddit();

        $posts = Post::get();

        return view('socialshare', compact('shareButtons', 'posts'));
    }

    public function DBBackup(){
        Artisan::call('backup:run');

        return back()->with('success','successfully backup done');
    }

}
