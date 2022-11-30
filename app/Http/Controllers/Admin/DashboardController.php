<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Posts;
use App\Helpers\Settings;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use App\Models\{Post, Term, User};

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

use Spatie\Analytics\AnalyticsFacade as Analytics;
use Spatie\Analytics\Period;
use Spatie\Permission\Models\{Permission, Role};

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return Renderable|Application|Factory|View
     */
    public function __invoke(Request $request)
    {
        //displays the amount of data in the dashboard
        $countPost       = Posts::postCountAlt();
        $countPage       = Post::ofType('page')->count();
        $countPermission = Permission::count();
        $countGallery    = Post::ofType('gallery')->count();

        //displays the amount of data based on user roles
        if (Auth::User()->hasRole('superadmin')) {
            $countUser = User::count();
            $countRole = Role::count();
        } else {
            $roles = User::showRoles();
            $countUser = User::role($roles)->count();
            $countRole = Role::whereIn('name', $roles)->count();
        }

        //displays the number of category and tag data
        if ( is_null(Term::with('taxonomy')->first()) === TRUE )
        {
            $countCategory = 0;
            $countTag = 0;
        }
        else
        {
            $getDataTaxonomy = Term::with('taxonomy')->get();
            //get the amount of data category
            $countCategory = $getDataTaxonomy->first()->taxonomy->where('taxonomy','category')->count();
            //get the amount of data tag
            $countTag = $getDataTaxonomy->first()->taxonomy->where('taxonomy','tag')->count();
        }

        $arrayCount = [
            'post'       => $countPost,
            'page'       => $countPage,
            'user'       => $countUser,
            'category'   => $countCategory,
            'tag'        => $countTag,
            'role'       => $countRole,
            'permission' => $countPermission,
            'gallery'    => $countGallery
        ];

        $count = (object) $arrayCount;

        //variable for analytics
        $totalVP            = null;
        $activeUsers        = null;
        $devices            = null;
        $mostVisited        = null;
        $topBrowsers        = null;
        $topOperatingSystem = null;
        $topCountry         = null;

        //if internet is available data will be displayed otherwise it will be null
        $connection = false;
        if (Settings::check_connection()) {
            if(env('ANALYTICS_VIEW_ID') != "") {
                //display analytics
                $connection         = true;
                $activeUsers        = $this->fetchOnlineUsers();
                $mostVisited        = Analytics::fetchMostVisitedPages(Period::days(7), 8);
                $topBrowsers        = Analytics::fetchTopBrowsers(Period::days(7), 8);
                $topOperatingSystem = $this->fetchTopOperatingSystem(Period::days(7), 8);
                $topCountry         = $this->fetchTopCountry(Period::days(7), 8);
            }
        }

        return view('admin.dashboard',
            compact('count',
                'activeUsers',
                'mostVisited',
                'topBrowsers',
                'topOperatingSystem',
                'topCountry',
                'connection'));
    }

    /**
     * @return mixed
     */
    public function fetchOnlineUsers() {
        $analytics = Analytics::getAnalyticsService();
        return $analytics->data_realtime
            ->get(
                'ga:' .env('ANALYTICS_VIEW_ID'),
                'rt:activeVisitors'
            )
            ->totalsForAllResults['rt:activeVisitors'];
    }

    /**
     * @param Period $period
     * @param int $maxResults
     * @return Collection
     */
    public function fetchTopOperatingSystem(Period $period, int $maxResults = 10): Collection
    {
        $response = Analytics::performQuery(
            $period,
            'ga:sessions',
            [
                'dimensions' => 'ga:operatingSystem,ga:operatingSystemVersion',
                'sort'       => '-ga:sessions',
            ]
        );

        $topOSs = collect($response['rows'] ?? [])->map(function (array $osRow) {
            return [
                'os'       => $osRow[0],
                'version'  => $osRow[1],
                'sessions' => (int)$osRow[2],
            ];
        });

        if ($topOSs->count() <= $maxResults) {
            return $topOSs;
        }

        return $this->summarizeTopOperatingSystem($topOSs, $maxResults);
    }

    /**
     * @param Collection $topOSs
     * @param int $maxResults
     * @return Collection
     */
    protected function summarizeTopOperatingSystem(Collection $topOSs, int $maxResults): Collection
    {
        return $topOSs
            ->take($maxResults - 1)
            ->push([
                'os'       => 'Others',
                'version'  => '-',
                'sessions' => $topOSs->splice($maxResults - 1)->sum('sessions'),
            ]);
    }

    /**
     * @param Period $period
     * @param int $maxResults
     * @return Collection
     */
    public function fetchTopCountry(Period $period, int $maxResults = 10): Collection
    {
        $response = Analytics::performQuery(
            $period,
            'ga:sessions',
            [
                'dimensions' => 'ga:country',
                'sort'       => '-ga:sessions',
            ]
        );

        $topCountrys = collect($response['rows'] ?? [])->map(function (array $countryRow) {
            return [
                'country'  => $countryRow[0],
                'sessions' => (int)$countryRow[1],
            ];
        });

        if ($topCountrys->count() <= $maxResults) {
            return $topCountrys;
        }

        return $this->summarizeTopCountry($topCountrys, $maxResults);
    }

    /**
     * @param Collection $topCountrys
     * @param int $maxResults
     * @return Collection
     */
    protected function summarizeTopCountry(Collection $topCountrys, int $maxResults): Collection
    {
        return $topCountrys
            ->take($maxResults - 1)
            ->push([
                'country'  => 'Others',
                'sessions' => $topCountrys->splice($maxResults - 1)->sum('sessions'),
            ]);
    }
}
