<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\GetUsersRequest;
use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    public function getUsers(GetUsersRequest $request)
    {
        $onlyOnline = $request->input('only_online');
        $country    = $request->input('country');
        $city       = $request->input('city');
        $withPhoto  = $request->input('with_photo');
        $gender     = $request->input('gender');
        $ageRange   = $request->input('age_range');

        $users = User::select([
           'first_name', 'last_name', 'birthday', 'gender', 'is_online', 'country_code', 'city'
        ])->when($onlyOnline, function ($query, $onlyOnline) {
            return $query->where('is_online', $onlyOnline);
        })->when($country, function ($query, $country) {
            return $query->where('country_id', $country);
        })->when($city, function ($query, $city) {
            return $query->where('city_id', $city);
        })->when($gender, function ($query, $gender) {
            return $query->where('gender', $gender);
        })->get()->toArray();

        dd($users);
    }
}
