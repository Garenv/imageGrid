<?php

namespace App\Http\Controllers\Auth;

use App\Dal\Interfaces\IUsersRepository;
use App\Http\Controllers\Controller;
use App\Mail\UserRegistered;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Rules\ReCaptchaV3;
use App\Traits\ProfanityTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Stevebauman\Location\Facades\Location;
use Symfony\Component\HttpFoundation\JsonResponse as BaseJsonResponse;

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

    public function register(Request $request)
    {
        $response = $this->validateUser($request);

        if($response->getStatusCode() == 422) {
            return back()->withErrors($response ->getData(true)['errors']);
        }

        $user = new User($response->getData(true)["user"]);
        event(new Registered($user));
        $this->guard()->login($user);

        $registeredUserData = [
            'name' => $user['name'],
            'email' => $user['email'],
            'regionName' => $user['regionName'],
            'cityName' => $user['cityName']
        ];

        $this->mailToSupport($registeredUserData);

        return $request->wantsJson() ? new JsonResponse([], 201) : redirect($this->redirectPath());
    }

    private function mailToSupport(Array $registeredUserData): void
    {
        if(isNotProduction()) return;
        Mail::to('support@phopixel.com')->send(new UserRegistered($registeredUserData));
    }

    /**
     * @param Request $request
     * @return BaseJsonResponse|null
     * This hits the api.php file
     */
    public function validateUser(Request $request): ?BaseJsonResponse
    {

        $data = $request->all();

        $messages = [
            'name' => 'This name already exists, choose another one',
            'registerPassword.required' => 'Password is required.',
            'registerPassword.min' => 'The password must be at least 10 characters.',
            'registerPassword.confirmed' => 'The password confirmation does not match.',
            'registerPassword.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.'
        ];

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'registerPassword' => [
                'required',
                'string',
                'min:10',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&:\[\]{}])[A-Za-z\d@$!%*#?&:\[\]{}]+$/'
            ],
            'g-recaptcha-response' => ['required', new ReCaptchaV3('submitRegisterForm', 0.5)]
            /*
                Basic validation: Ensures that a valid code was provided by the browser through the recaptcha/api.js anti-bot mechanism.
                'g-recaptcha-response' => ['required', new ReCaptchaV3()]

                Stricter verification: In addition to code validation, it verifies that the form data-action and the action reported by Google both match ‘submitContact’.
                'g-recaptcha-response' => ['required', new ReCaptchaV3('submitContact')]

                Even stricter verification: data-action must match, and the bot score reported back by Google must be higher than 0.5.
                'g-recaptcha-response' => ['required', new ReCaptchaV3('submitContact', 0.5)]
            */
        ], $messages);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $name = $data['name'];
        $checkNameForProfanityWhenRegistering = $this->checkNameForProfanityWhenRegistering($name);

        if($checkNameForProfanityWhenRegistering === 'true') {
            return response()->json(['errors' => "Names may not contain any profanity"], 422);
        }

        if(!isset($data["skipMultipleAccounts"])) {
            $getUserIpAddress = getUserIpAddr();
            $existingUser = $this->__usersRepository->getIpAddresses($getUserIpAddress);

            if(isset($existingUser)) {
                if ($existingUser['ip'] === $getUserIpAddress) {
                    return response()->json(['errors' => "You may not create multiple accounts in order to gain an unfair advantage by uploading additional photos."],422);
                }
            }
        }

        return response()->json(["user" => $this->createUser($data)]);
    }


    public function createUser(array $data)
    {
        $locationData = Location::get();
        $device = getUserDeviceData()['device'];
        $deviceOs = getUserDeviceData()['device_os'];
        $osVersion = getUserDeviceData()['os_version'];

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'UserID' => 'u-' . Str::uuid()->toString(),
            'password' => Hash::make($data['registerPassword']),
            'ip' =>  getUserIpAddr(),
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
            'os_version' => $osVersion,
            'email_verified_at' => $data['email_verified_at'] ?? null
        ]);

    }

}
