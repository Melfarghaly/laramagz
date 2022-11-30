<?php

namespace App\Http\Controllers\Front;

use App\Helpers\Settings;
use App\Http\Controllers\Controller;
use App\Models\Term;
use App\Models\TermTaxonomy;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Vinkla\Hashids\HashidsManager;

class CategoryController extends Controller
{
    protected $hashids;

    /**
     * CategoryController constructor.
     * @param HashidsManager $hashids
     */
    public function __construct(HashidsManager $hashids)
    {
        $this->hashids = $hashids;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Term $term
     * @return Application|Factory|View
     */
    public function index(Term $term)
    {
        $term_taxonomy_id = $term->taxonomy->id;
        $termTaxonomy = TermTaxonomy::find($term_taxonomy_id);
        $paginate_posts = $termTaxonomy->post()->latest()->paginate(8);
        $hashids = $this->hashids;

        $image = (Settings::get('ogimage')) ? route('ogi.display', Settings::get('ogimage')) :
            asset('img/cover.png');

        SEOTools::setTitle(Settings::get('sitename'). " - Category: " . $term->name);
        SEOTools::setDescription(Settings::get('sitedescription'));
        SEOTools::metatags()->setKeywords(Settings::get('metakeyword'));
        SEOTools::setCanonical(Settings::get('siteurl'));
        SEOTools::opengraph()->setTitle(Settings::get('sitename'));
        SEOTools::opengraph()->setDescription(Settings::get('sitedescription'));
        SEOTools::opengraph()->setUrl(Settings::get('siteurl'));
        SEOTools::opengraph()->setSiteName(Settings::get('company_name'));
        SEOTools::opengraph()->addImage($image);
        SEOTools::twitter()->setType('summary_large_image');
        SEOTools::twitter()->setSite('@' . Settings::get('twitter'));
        SEOTools::twitter()->setTitle(Settings::get('sitename'));
        SEOTools::twitter()->setDescription(Settings::get('sitedescription'));
        SEOTools::twitter()->setImage($image);
        SEOTools::jsonLd()->setTitle(Settings::get('sitename'));
        SEOTools::jsonLd()->setDescription(Settings::get('sitedescription'));
        SEOTools::jsonLd()->setType('WebPage');
        SEOTools::jsonLd()->setUrl(Settings::get('siteurl'));
        SEOTools::jsonLd()->addImage($image);

        return view(Settings::active_theme('page/category'), compact(
            'term', 'paginate_posts', 'hashids'
        ));
    }
}
