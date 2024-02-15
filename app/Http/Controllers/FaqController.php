<?php

namespace App\Http\Controllers;

use App\Dal\Interfaces\IFaqRepository;
use App\Models\Faq;

class FaqController extends Controller
{

    protected IFaqRepository $faqRepository;

    public function __construct(IFaqRepository $faqRepository)
    {
        $this->faqRepository = $faqRepository;
    }

    /***
     * For testing
    */
    public function getFaq()
    {
        $faq = $this->faqRepository->getFaq();
        return response()->json(["faq" => $faq]);
    }

    public function viewFaq()
    {
        $response = $this->getFaq();
        $faq = $response->getData(true)["faq"];
        return view("faq", compact("faq"));
    }
}
