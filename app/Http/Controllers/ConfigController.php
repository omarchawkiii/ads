<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfigController extends Controller
{
    public function edit()
    {
        $config = Config::all()->first() ;
        return view('admin.configs.index' , compact('config'));

    }


    public function update(Request $request)
    {
        $config = Config::all()->first();
        if($request->use_noc == true)
        {
            $use_noc = 1 ;
        }
        else
        {
            $use_noc = 0 ;
        }


        //dd($request->timeStart) ;
        $new_config = $config->update([
            'link' => $request->link,
            'use_noc' => $use_noc,
            'user' => $request->user,
            'password' => $request->password,
        ]);
        if($new_config)
        {
            return true ;
        }
        else
        {
            return false ;
        }

    }
    public function update_use_noc(Request $request)
    {
        $config = Config::all()->first();

        $new_config = $config->update([
            'use_noc' => $request->usenoc ,
        ]);
        if($new_config)
        {
           return true ;
        }
        else
        {
            return false ;
        }

    }

    public function change_theme()
    {
       if(Auth::user()->theme?->light)
       {
            $light = 0 ;
       }
       else
       {
            $light = 1;
       }
       Theme::updateOrCreate([
            'user_id' => Auth::user()->id,
        ],[
            'user_id' => Auth::user()->id,
            'light' => $light,
        ]);
        return redirect()->back();

    }

}
