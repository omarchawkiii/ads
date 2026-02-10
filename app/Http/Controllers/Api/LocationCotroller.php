<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationCotroller extends Controller
{

    public function refresh_locations(Request $request)
    {

        $contents =$request->input('content') ;

        if($contents)
        {

            foreach($contents['data'] as $data)
            {
                Location::updateOrCreate([
                    'name' => $data['name']
                    ],
                    [
                    'name' => $data['name'],
                    ]);
            }
            return response()->json([
                'status' =>1,
                'message' => 'Location synchronization completed successfully.',
            ], 200);

        }
        else
        {
            return response()->json([
                'status' =>0,
                'message' => "No Data",
            ], 200);
        }

    }
}
