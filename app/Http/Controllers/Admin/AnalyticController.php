<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Spatie\Analytics\AnalyticsFacade as Analytics;
use Spatie\Analytics\Period;

class AnalyticController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View|Response
     */
    public function index(Request $request)
    {
        $day = $request->session()->has('session_device_analytics') ? session('session_device_analytics') : 7;
        $day_visitor_pageviews = $request->session()->has('session_visitor_pageview_analytics') ? session('session_visitor_pageview_analytics') : 7;
        $day_most_visited_pages = $request->session()->has('session_most_visited_pages') ? session('session_most_visited_pages') : 7;
        $day_browser_used = $request->session()->has('session_browser_used') ? session('session_browser_used') : 7;
        $day_operating_system = $request->session()->has('session_browser_used') ? session('session_browser_used') : 7;
        $day_sessions_country = $request->session()->has('session_country') ? session('session_country') : 7;

        $label_day = $this->label($request->session()->has('session_device_analytics'), $day);
        $label_day_visitor = $this->label($request->session()->has('session_visitor_pageview_analytics'), $day_visitor_pageviews);
        $label_day_most_visited_pages = $this->label($request->session()->has('session_most_visited_pages'), $day_most_visited_pages);
        $label_day_browser_used = $this->label($request->session()->has('session_browser_used'), $day_browser_used);
        $label_day_operating_system_used = $this->label($request->session()->has('session_browser_used'), $day_browser_used);
        $label_day_sessions_country = $this->label($request->session()->has('session_country'), $day_sessions_country);

        $devices = Arr::collapse($this->fetchTopDevice(Period::days($day), 8));
        $col = 12 / count($devices);
        $pageviews_this_year = $this->pageViewsThisYear();
        $visitors_this_year = $this->visitorsThisYear();
        $pageviews = $this->pageViews(Period::days($day_visitor_pageviews));
        $visitors = $this->visitors(Period::days($day_visitor_pageviews));
        $mostVisited = Analytics::fetchMostVisitedPages(Period::days($day_most_visited_pages), 8);
        $topBrowsers = Analytics::fetchTopBrowsers(Period::days($day_browser_used), 8);
        $topOperatingSystem = $this->fetchTopOperatingSystem(Period::days($day_operating_system), 8);
        $topCountry = $this->fetchTopCountry(Period::days($day_sessions_country), 8);

        return view('admin.analytics.index', compact(
            'label_day_visitor',
            'label_day',
            'label_day_visitor',
            'devices',
            'col',
            'pageviews_this_year',
            'visitors_this_year',
            'pageviews',
            'visitors',
            'mostVisited',
            'topBrowsers',
            'topOperatingSystem',
            'topCountry',
            'label_day_most_visited_pages',
            'label_day_browser_used',
            'label_day_operating_system_used',
            'label_day_sessions_country'
        ));
    }

    /**
     * @param $session
     * @param $day
     * @return string
     */
    public function label($session, $day)
    {
        $label = "In 7 dayss";
        if ($session) {
            if ($day == 0) {
                $label = "In Today";
            } else if ($day == 1) {
                $label = "In Yesterday";
            } else if ($day == 7) {
                $label = "In 7 days";
            } else if ($day == 28) {
                $label = "In 28 days";
            } else if ($day == 90) {
                $label = "In 90 days";
            }
        }

        return $label;
    }

    /**
     * @param Period $period
     * @return Collection
     */
    public function fetchTopDevice(Period $period)
    {
        $response = Analytics::performQuery(
            $period,
            'ga:users',
            [
                'dimensions' => 'ga:deviceCategory'
            ]
        );

        return collect($response['rows'] ?? [])->map(function (array $deviceRow) {
            return [$deviceRow[0] => $deviceRow[1]];
        });
    }

    /**
     * @return mixed
     */
    public function visitorsThisYear()
    {
        $startDate = Carbon::now()->startOfYear();
        $endDate = Carbon::now();
        $period_this_year = Period::create($startDate, $endDate);
        return $this->fetchTotalVisitorsAndPageViews($period_this_year)->sum('visitors');
    }

    /**
     * @return mixed
     */
    public function pageViewsThisYear()
    {
        $startDate = Carbon::now()->startOfYear();
        $endDate = Carbon::now();
        $period_this_year = Period::create($startDate, $endDate);
        return $this->fetchTotalVisitorsAndPageViews($period_this_year)->sum('pageViews');
    }

    /**
     * @param Period $period
     * @return mixed
     */
    public function visitors(Period $period)
    {
        return $this->fetchTotalVisitorsAndPageViews($period)->sum('visitors');
    }

    /**
     * @param Period $period
     * @return mixed
     */
    public function PageViews(Period $period)
    {
        return $this->fetchTotalVisitorsAndPageViews($period)->sum('pageViews');
    }

    /**
     * @param Period $period
     * @return mixed
     */
    public function fetchTotalVisitorsAndPageViews(Period $period)
    {
        return Analytics::fetchTotalVisitorsAndPageViews($period);
    }

    /**
     * @param Period $period
     * @param int $maxResults
     * @return Collection
     */
    public function fetchTopOperatingSystem(Period $period, int $maxResults = 10)
    {
        $response = Analytics::performQuery(
            $period,
            'ga:sessions',
            [
                'dimensions' => 'ga:operatingSystem,ga:operatingSystemVersion',
                'sort' => '-ga:sessions',
            ]
        );

        $topOSs = collect($response['rows'] ?? [])->map(function (array $osRow) {
            return [
                'os' => $osRow[0],
                'version' => $osRow[1],
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
    protected function summarizeTopOperatingSystem(Collection $topOSs, int $maxResults)
    {
        return $topOSs
            ->take($maxResults - 1)
            ->push([
                'os' => 'Others',
                'version' => '-',
                'sessions' => $topOSs->splice($maxResults - 1)->sum('sessions'),
            ]);
    }

    /**
     * @param Period $period
     * @param int $maxResults
     * @return Collection
     */
    public function fetchTopCountry(Period $period, int $maxResults = 10)
    {
        $response = Analytics::performQuery(
            $period,
            'ga:sessions',
            [
                'dimensions' => 'ga:country',
                'sort' => '-ga:sessions',
            ]
        );

        $topCountrys = collect($response['rows'] ?? [])->map(function (array $countryRow) {
            return [
                'country' => $countryRow[0],
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
                'country' => 'Others',
                'sessions' => $topCountrys->splice($maxResults - 1)->sum('sessions'),
            ]);
    }
}
