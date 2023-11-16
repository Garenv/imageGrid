<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Traits\ProfanityTrait;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Stevebauman\Location\Facades\Location;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    use ProfanityTrait;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $ipAddress = request()->ip();
        $locationData = Location::get($ipAddress);

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'UserID' => 'u-' . Str::uuid()->toString(),
            'password' => Hash::make($data['password']),
            'ip' => $locationData->ip,
            'countryName' => $locationData->countryName,
            'countryCode' => $locationData->countryCode,
            'regionCode' => $locationData->regionCode,
            'regionName' => $locationData->regionName,
            'cityName' => $locationData->cityName,
            'zipCode' => $locationData->zipCode,
            'isoCode' => $locationData->isoCode,
            'postalCode' => $locationData->postalCode,
            'latitude' => $locationData->latitude,
            'longitude' => $locationData->longitude,
            'metroCode' => $locationData->metroCode,
            'areaCode' => $locationData->areaCode,
            'timezone' => $locationData->timezone
        ]);

    }

}
