<?php

namespace App\Http\Controllers\Auth;

use App\Dal\Interfaces\IUsersRepository;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Traits\ProfanityTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
     * @var IUsersRepository
     */
    protected $__usersRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(IUsersRepository $usersRepository)
    {
        $this->__usersRepository = $usersRepository;
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Validation\Validator
     */
    protected function validator(array $data)
    {

        $messages = [
            'registerPassword.required' => 'The password is required.',
            'registerPassword.min' => 'The password must be at least 10 characters.',
            'registerPassword.confirmed' => 'The password confirmation does not match.',
            'registerPassword.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ];

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'registerPassword' => [
                'required',
                'string',
                'min:2',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{10,}$/'
            ],
        ], $messages);

    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $getSiteEnv = getSiteEnv();
        $getUserIpAddress = getUserIpAddr();

        $existingUser = $this->__usersRepository->getIpAddresses($getSiteEnv === 'stage' || $getSiteEnv === 'prod' ?? $getUserIpAddress);

        if(isset($existingUser)) {
            if ($existingUser['ip'] === $getUserIpAddress) {
                return redirect()->back()->with('userTryingToCreateMultipleAccountsError', "You may not create multiple accounts in order to gain an unfair advantage by uploading additional photos.");
            }
        }

        $user = $this->create($request->all());

        event(new Registered($user));

        $this->guard()->login($user);

        return $request->wantsJson() ? new JsonResponse([], 201) : redirect($this->redirectPath());
    }


    /**
     * @param array $data
     * @return \Illuminate\Http\JsonResponse|void
     */
    protected function create(array $data)
    {
        $getSiteEnv = getSiteEnv();
        $getUserIpAddress = getUserIpAddr();
        $locationData = Location::get($getSiteEnv === 'stage' || $getSiteEnv === 'prod' ?? $getUserIpAddress);
        $device = getUserDeviceData()['device'];
        $deviceOs = getUserDeviceData()['device_os'];
        $osVersion = getUserDeviceData()['os_version'];

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'UserID' => 'u-' . Str::uuid()->toString(),
            'password' => Hash::make($data['registerPassword']),
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
            'timezone' => $locationData->timezone,
            'device' => $device,
            'device_os' => $deviceOs,
            'os_version' => $osVersion
        ]);

    }

}
