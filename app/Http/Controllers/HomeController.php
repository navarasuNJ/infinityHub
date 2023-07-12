<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Auth;
use response;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $user=Auth::user();
        $data['token']=$user->createToken("API TOKEN")->plainTextToken;
        $data['countries']=Country::get()->count();
        $data['states']=State::get()->count();
        $data['cities']=City::get()->count();
        return view('home',$data);
    }

    public function import()
    {
        try {
            $counrtyResponse = Http::withHeaders([
                'X-CSCAPI-KEY' => 'MUQzMEptNEE3bXZTNVhSQUVtNEVBNk9ZakgzRk9wcUdFYnJjT0EwNQ=='
            ])->get('https://api.countrystatecity.in/v1/countries');
            $countryData = json_decode($counrtyResponse->body(),true);
            Country::insert($countryData);
    
            $statesResponse = Http::withHeaders([
                'X-CSCAPI-KEY' => 'MUQzMEptNEE3bXZTNVhSQUVtNEVBNk9ZakgzRk9wcUdFYnJjT0EwNQ=='
            ])->get('https://api.countrystatecity.in/v1/states');
            $stateData = json_decode($statesResponse->body(),true);
            $data=[];
            foreach($stateData as $row){
                unset($row['type']);
                unset($row['latitude']);
                unset($row['longitude']);
                $data[]=$row;
            }
            State::insert($data);

            // Have more than 1.5L records in cites so i have imported only indian cities

            $country=Country::where('name','India')->get()->first();
            if($country){
                $cityResponse = Http::withHeaders([
                    'X-CSCAPI-KEY' => 'MUQzMEptNEE3bXZTNVhSQUVtNEVBNk9ZakgzRk9wcUdFYnJjT0EwNQ=='
                ])->get('https://api.countrystatecity.in/v1/countries/'.$country->iso2.'/cities');
                $cityData = json_decode($cityResponse->body(),true);
                $cdata=[];
                foreach($cityData as $res){
                    $res['country_id']=$country->id;
                    $cdata[]=$res;
                }
                City::insert($cdata);
            }

            Session::flash('status', 'Data Imported Successfully..!!');
        } catch (\Exception $ex) {
            Session::flash('error', $ex->getMessage());
        }
        return redirect()->back();
    }

    // API functions

    public function getCountries(Request $request)
    {
        $data=Country::get();
        return response()->json($data);
    }

    public function getStates(Request $request)
    {
        $data=State::get();
        return response()->json($data);
    }

    public function getCities(Request $request)
    {
        $data=City::get();
        return response()->json($data);
    }
}
