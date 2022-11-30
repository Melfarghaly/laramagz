<?php

namespace App\Http\Controllers\Front;

use App\Helpers\Settings;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Post;
use App\Models\Setting;
use App\Models\TermTaxonomy;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * @param $name
     * @return mixed
     */
    public function get_setting($name)
    {
        $get_settings = Setting::get();
        $value = $get_settings->whereIn('name', $name)->first()->value;
        return $value;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $image = (Settings::get('ogimage')) ? route('ogi.display', Settings::get('ogimage')) :
            asset('img/cover.png');

        SEOTools::setTitle("Contact's Page");
        SEOTools::setDescription("Contact's Page");
        SEOTools::metatags()->setKeywords("Contact's Page");
        SEOTools::setCanonical(url("/contact"));
        SEOTools::opengraph()->setUrl(url("/contact"));
        SEOTools::opengraph()->setSiteName(Settings::get('company_name'));
        SEOTools::opengraph()->addImage($image);
        SEOTools::twitter()->setType('summary_large_image');
        SEOTools::twitter()->setSite('@' . Settings::get('twitter'));
        SEOTools::twitter()->setTitle("Contact's Page");
        SEOTools::twitter()->setDescription("Contact's Page");
        SEOTools::twitter()->setImage($image);
        SEOTools::jsonLd()->setTitle("Contact's Page");
        SEOTools::jsonLd()->setDescription("Contact's Page");
        SEOTools::jsonLd()->setType('WebPage');
        SEOTools::jsonLd()->setUrl(url("/contact"));
        SEOTools::jsonLd()->addImage($image);

        $getPosts = Post::wherePostType('post');
        $recentPosts = $getPosts->latest();
        if ($recentPosts->first() === null) {
            $last_recentPost = '';
        }else{
            $last_recentPost = $recentPosts->first()->post_title;
        }
        $tag_count = TermTaxonomy::where('taxonomy', 'tag')->withCount('post')->orderBy('post_count', 'DESC')->get();

        return view(Settings::active_theme('page/contact'),compact('tag_count','recentPosts','last_recentPost'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|email',
            'subject' => 'nullable',
            'message' => 'required',
            'g-recaptcha-response' => 'required|captcha'
        ]);

        $save = Contact::create($data);

        if ($save) {
            return response()->json([
                'status' => true,
                'data' => 'Message has been sent'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => 'Message could not be sent'
            ]);
        }
    }
}
