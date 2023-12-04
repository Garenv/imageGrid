<?php

namespace App\Http\Controllers;

use App\Dal\Interfaces\IWinnersRepository;
use Illuminate\Support\Facades\Auth;

class WinnersController extends Controller
{

    /**
     * @var IWinnersRepository
     */
    protected $__winnersRepository;

    public function __construct(IWinnersRepository $winnersRepository)
    {
        $this->__winnersRepository = $winnersRepository;
    }

    public function getThisWeeksWinners() {
        $loggedInUserId = Auth::user()['UserID'];
        return $this->__winnersRepository->getThisWeeksWinners($loggedInUserId);
    }

    public function getLastWeeksWinners() {
        return $this->__winnersRepository->getLastWeeksWinners();
    }

}
