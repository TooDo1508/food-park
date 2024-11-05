<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SectionTitle;
use App\Models\Slider;
use App\Models\WhyChooseUs;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    //
    public function index(): View
    {
        $sliders = Slider::where('status', 1)->get();
        $sectionTitles = $this->getSectionTitles();
        $whyChooseUs = WhyChooseUs::where('status', 1)->get();
        return view('frontend.home.index', compact('sliders', 'sectionTitles', 'whyChooseUs'));
    }

    public function getSectionTitles()
    {
        $keys = [
            'why_choose_us_top_title',
            'why_choose_us_main_title',
            'why_choose_us_sub_title'
        ];
        return SectionTitle::whereIn('key', $keys)->pluck('value', 'key');
    }
}
