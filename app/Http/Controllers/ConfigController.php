<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\PricingSeason;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfigController extends Controller
{
    public function edit()
    {
        $config  = Config::all()->first() ;
        $seasons = PricingSeason::orderBy('start_date')->get();
        return view('admin.configs.index' , compact('config', 'seasons'));

    }


    public function update(Request $request)
    {
        $config = Config::all()->first();
        if($request->use_noc == 'true')
        {
            $use_noc = 1 ;
        }
        else
        {
            $use_noc = 0 ;
        }
        $new_config = $config->update([
            'link'     => $request->link,
            'use_noc'  => $use_noc,
            'user'     => $request->user,
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
    public function updateTax(Request $request)
    {
        $request->validate(['tax' => 'required|numeric|min:0|max:100']);
        $config = Config::first();
        $config->update(['tax' => $request->tax]);
        return response()->json(['message' => 'Tax updated successfully.', 'tax' => $config->tax]);
    }

    public function updatePrices(Request $request)
    {
        $v = $request->validate([
            'default_location_price' => 'required|numeric|min:0|max:999999.99',
            'default_slot_price'     => 'required|numeric|min:0|max:999999.99',
            'default_hall_type_price' => 'required|numeric|min:0|max:999999.99',
            'default_revenue_share_percent' => 'required|numeric|min:0|max:100',
        ]);
        Config::first()->update($v);
        return response()->json(['message' => 'Default prices updated successfully.'] + $v);
    }

    /** Multiplicateurs de prix (modèle « deck »), plafond et minimum par cinéma et par semaine. */
    public function updateMultipliers(Request $request)
    {
        $mult = 'required|numeric|min:0.01|max:99';
        $v = $request->validate([
            'position_first_multiplier'   => $mult,
            'position_middle_multiplier'  => $mult,
            'position_premium_multiplier' => $mult,
            'target_region_multiplier'    => $mult,
            'target_movie_multiplier'     => $mult,
            'target_audience_multiplier'  => $mult,
            'length_15_multiplier'        => $mult,
            'length_30_multiplier'        => $mult,
            'length_45_multiplier'        => $mult,
            'length_60_multiplier'        => $mult,
            'volume_min_weeks'            => 'required|integer|min:1|max:520',
            'volume_multiplier'           => $mult,
            'max_multiplier'              => 'required|numeric|min:0.01|max:99',
            'min_outlet_week_price'       => 'required|numeric|min:0|max:999999.99',
        ]);
        Config::first()->update($v);

        return response()->json(['message' => 'Pricing multipliers updated successfully.'] + $v);
    }

    public function seasonStore(Request $request)
    {
        $v = $request->validate([
            'name'       => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'multiplier' => 'required|numeric|min:0.01|max:99',
        ]);

        return response()->json(['message' => 'Season added.', 'season' => PricingSeason::create($v)], 201);
    }

    public function seasonUpdate(Request $request, $id)
    {
        $v = $request->validate([
            'name'       => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'multiplier' => 'required|numeric|min:0.01|max:99',
        ]);
        $season = PricingSeason::findOrFail($id);
        $season->update($v);

        return response()->json(['message' => 'Season updated.', 'season' => $season->fresh()]);
    }

    public function seasonDestroy($id)
    {
        PricingSeason::findOrFail($id)->delete();

        return response()->json(['message' => 'Season deleted.']);
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
