<?php

namespace App\Dal\Repositories;

use App\Dal\Interfaces\IFaqRepository;
use App\Models\Faq;

class FaqRepository implements IFaqRepository
{

    function getFaq()
    {
        return Faq::all();
    }
}
