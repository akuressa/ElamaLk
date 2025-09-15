<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Booking;
use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\BusinessEmployee;
use App\Models\BusinessService;
use App\Models\City;
use App\Models\Configuration;
use App\Models\Currency;
use App\Models\Page;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class WebController extends Controller
{
    // Home page
    public function webIndex()
    {
        // Queries
        $config = Configuration::get();

        // Check website
        if ($config[43]->config_value == "yes") {
            // Currency
            $currency = Currency::where('iso_code', $config['1']->config_value)->first();

            // Setting
            $setting = Setting::where('status', 1)->first();

            // Plans
            $plans = Plan::where('status', 1)->where('is_private', 0)->orderBy('plan_price', 'asc')->get();

            // Blogs
            $blogs = Blog::where('status', 1)->limit(3)->orderBy('created_at', 'desc')->get();

            // Business Categories
            $business_categories = BusinessCategory::where('status', 1)->get();

            // Check plan for free
            $planPrices = [];
            for ($j = 0; $j < count($plans); $j++) {
                $planPrices[$j] = $plans[$j]->plan_price;
            }

            // Pages
            $pages = Page::where("page_name", "home")->get();

            foreach ($pages as $page) {
                $page->page_content = str_replace("indigo", $config[11]->config_value, $page->page_content);
            }

            // Seo Tools
            SEOTools::setTitle($pages[0]->meta_title);
            SEOTools::setDescription($pages[0]->meta_description);

            SEOMeta::setTitle($pages[0]->meta_title);
            SEOMeta::setDescription($pages[0]->meta_description);
            SEOMeta::addMeta('article:section', $pages[0]->page_name . ' - ' . $pages[0]->meta_description, 'property');
            SEOMeta::addKeyword([$pages[0]->meta_keywords]);

            OpenGraph::setTitle($pages[0]->meta_title);
            OpenGraph::setDescription($pages[0]->meta_description);
            OpenGraph::setUrl(URL::full());
            OpenGraph::addImage([asset($setting->site_logo), 'size' => 300]);

            JsonLd::setTitle($pages[0]->meta_title);
            JsonLd::setDescription($pages[0]->meta_description);
            JsonLd::addImage(asset($setting->site_logo));

            // Business Categories
            $business_categories_array = BusinessCategory::where('status', 1)
                ->select('business_category_name', 'business_category_slug')
                ->get()
                ->toArray();
            $meta_title = $pages[0]->meta_title;

            // Return values
            $returnValues = compact('plans', 'config', 'currency', 'setting', 'pages', 'blogs', 'business_categories', 'business_categories_array', 'meta_title');
            return view("website.index", $returnValues);
        } else {
            abort(404);
        }
    }

    // Features page
    public function webFeatures()
    {
        // Queries
        $config = Configuration::get();

        // Check website
        if ($config[43]->config_value == "yes") {
            $setting = Setting::where('status', 1)->first();

            $page = Page::where("page_name", "features")->first();

            $page->page_content = str_replace("indigo", $config[11]->config_value, $page->page_content);

            // Seo Tools
            SEOTools::setTitle($page->meta_title);
            SEOTools::setDescription($page->meta_description);

            SEOMeta::setTitle($page->meta_title);
            SEOMeta::setDescription($page->meta_description);
            SEOMeta::addMeta('article:section', $page->page_name . ' - ' . $page->meta_description, 'property');
            SEOMeta::addKeyword([$page->meta_keywords]);

            OpenGraph::setTitle($page->meta_title);
            OpenGraph::setDescription($page->meta_description);
            OpenGraph::setUrl(URL::full());
            OpenGraph::addImage([asset($setting->site_logo), 'size' => 300]);

            JsonLd::setTitle($page->meta_title);
            JsonLd::setDescription($page->meta_description);
            JsonLd::addImage(asset($setting->site_logo));

            // Return values
            $returnValues = compact('config', 'setting', 'page');

            return view("website.pages.features.index", $returnValues);
        } else {
            abort(404);
        }
    }

    // Contact page
    public function webContact()
    {
        // Queries
        $config = Configuration::get();

        // Check website
        if ($config[43]->config_value == "yes") {
            $setting = Setting::where('status', 1)->first();

            $page = Page::where("page_name", "contact")->first();

            $page->page_content = str_replace("indigo", $config[11]->config_value, $page->page_content);

            // Seo Tools
            SEOTools::setTitle($page->meta_title);
            SEOTools::setDescription($page->meta_description);

            SEOMeta::setTitle($page->meta_title);
            SEOMeta::setDescription($page->meta_description);
            SEOMeta::addMeta('article:section', $page->page_name . ' - ' . $page->meta_description, 'property');
            SEOMeta::addKeyword([$page->meta_keywords]);

            OpenGraph::setTitle($page->meta_title);
            OpenGraph::setDescription($page->meta_description);
            OpenGraph::setUrl(URL::full());
            OpenGraph::addImage([asset($setting->site_logo), 'size' => 300]);

            JsonLd::setTitle($page->meta_title);
            JsonLd::setDescription($page->meta_description);
            JsonLd::addImage(asset($setting->site_logo));

            // Return values
            $returnValues = compact('config', 'setting', 'page');

            return view("website.pages.contact.index", $returnValues);
        } else {
            abort(404);
        }
    }

    // About us page
    public function webAbout()
    {
        // Queries
        $config = Configuration::get();

        // Check website
        if ($config[43]->config_value == "yes") {
            $setting = Setting::where('status', 1)->first();

            $page = Page::where("page_name", "about")->first();

            $page->page_content = str_replace("indigo", $config[11]->config_value, $page->page_content);

            // Seo Tools
            SEOTools::setTitle($page->meta_title);
            SEOTools::setDescription($page->meta_description);

            SEOMeta::setTitle($page->meta_title);
            SEOMeta::setDescription($page->meta_description);
            SEOMeta::addMeta('article:section', $page->page_name . ' - ' . $page->meta_description, 'property');
            SEOMeta::addKeyword([$page->meta_keywords]);

            OpenGraph::setTitle($page->meta_title);
            OpenGraph::setDescription($page->meta_description);
            OpenGraph::setUrl(URL::full());
            OpenGraph::addImage([asset($setting->site_logo), 'size' => 300]);

            JsonLd::setTitle($page->meta_title);
            JsonLd::setDescription($page->meta_description);
            JsonLd::addImage(asset($setting->site_logo));

            // Return values
            $returnValues = compact('config', 'setting', 'page');

            return view("website.pages.about.index", $returnValues);
        } else {
            abort(404);
        }
    }

    // Blogs page
    public function blogs()
    {
        // Queries
        $config = Configuration::get();

        // Check website
        if ($config[43]->config_value == "yes") {

            // Blogs
            $blogs = Blog::where('status', 1)->orderBy('created_at', 'asc')->get();
            $setting = Setting::where('status', 1)->first();

            foreach ($blogs as $blog) {
                $blog->long_description = str_replace("indigo", $config[11]->config_value, $blog->long_description);
            }

            // Get page details
            $page = Page::where('page_slug', 'home')->where('status', 1)->get();

            // Seo Tools            
            SEOTools::setTitle('Blogs');
            SEOTools::setDescription('Blogs' . ' - ' . $page[0]->description);

            SEOMeta::setTitle('Blogs');
            SEOMeta::setDescription('Blogs' . ' - ' . $page[0]->description);
            SEOMeta::addMeta('article:section', 'Blogs', 'property');
            SEOMeta::addKeyword([$page[0]->keywords]);

            OpenGraph::setTitle('Blogs');
            OpenGraph::setDescription('Blogs' . ' - ' . $page[0]->description);
            OpenGraph::setUrl(URL::full());
            OpenGraph::addImage([asset($setting->site_logo), 'size' => 300]);

            JsonLd::setTitle('Blogs');
            JsonLd::setDescription('Blogs' . ' - ' . $page[0]->description);
            JsonLd::addImage(asset($setting->site_logo));

            // Return values
            $returnValues = compact('blogs', 'config', 'setting');

            return view("website.pages.blogs.index", $returnValues);
        } else {
            abort(404);
        }
    }

    // View blog page
    public function viewBlog($blog_slug)
    {
        // Queries
        $config = Configuration::get();
        $setting = Setting::where('status', 1)->first();

        // Check website
        if ($config[43]->config_value == "yes") {
            $blog = Blog::where('blog_slug', $blog_slug)->first();

            // Seo Tools
            SEOTools::setTitle($blog->title);
            SEOTools::setDescription($blog->description);
            SEOTools::addImages(asset($blog->blog_cover));

            SEOMeta::setTitle($blog->title);
            SEOMeta::setDescription($blog->description);
            SEOMeta::addMeta('article:section', $blog->title, 'property');
            SEOMeta::addKeyword([$blog->keywords]);

            OpenGraph::setTitle($blog->title);
            OpenGraph::setDescription($blog->description);
            OpenGraph::addProperty('type', 'article');
            OpenGraph::setUrl(url($blog->blog_slug));

            JsonLd::setType('Article');
            JsonLd::setTitle($blog->title);
            JsonLd::setDescription($blog->description);

            $recentBlogs = Blog::where('blog_slug', '!=', $blog_slug)->where('status', 1)->limit(2)->orderBy('created_at', 'desc')->get();

            // Return values
            $returnValues = compact('blog', 'config', 'setting', 'setting', 'recentBlogs');

            return view("website.pages.blogs.view", $returnValues);
        } else {
            abort(404);
        }
    }

    // Privacy policy page
    public function webPrivacy()
    {
        // Queries
        $config = Configuration::get();

        // Check website
        if ($config[43]->config_value == "yes") {
            $setting = Setting::where('status', 1)->first();

            $page = Page::where("page_name", "privacy-policy")->first();

            $page->page_content = str_replace("indigo", $config[11]->config_value, $page->page_content);

            // Seo Tools
            SEOTools::setTitle($page->meta_title);
            SEOTools::setDescription($page->meta_description);

            SEOMeta::setTitle($page->meta_title);
            SEOMeta::setDescription($page->meta_description);
            SEOMeta::addMeta('article:section', $page->page_name . ' - ' . $page->meta_description, 'property');
            SEOMeta::addKeyword([$page->meta_keywords]);

            OpenGraph::setTitle($page->meta_title);
            OpenGraph::setDescription($page->meta_description);
            OpenGraph::setUrl(URL::full());
            OpenGraph::addImage([asset($setting->site_logo), 'size' => 300]);

            JsonLd::setTitle($page->meta_title);
            JsonLd::setDescription($page->meta_description);
            JsonLd::addImage(asset($setting->site_logo));

            // Return values
            $returnValues = compact('config', 'setting', 'page');

            return view("website.pages.privacy-policy.index", $returnValues);
        } else {
            abort(404);
        }
    }

    // Refund policy page
    public function webRefund()
    {
        // Queries
        $config = Configuration::get();

        // Check website
        if ($config[43]->config_value == "yes") {
            $setting = Setting::where('status', 1)->first();

            $page = Page::where("page_name", "refund-policy")->first();

            $page->page_content = str_replace("indigo", $config[11]->config_value, $page->page_content);

            // Seo Tools
            SEOTools::setTitle($page->meta_title);
            SEOTools::setDescription($page->meta_description);

            SEOMeta::setTitle($page->meta_title);
            SEOMeta::setDescription($page->meta_description);
            SEOMeta::addMeta('article:section', $page->page_name . ' - ' . $page->meta_description, 'property');
            SEOMeta::addKeyword([$page->meta_keywords]);

            OpenGraph::setTitle($page->meta_title);
            OpenGraph::setDescription($page->meta_description);
            OpenGraph::setUrl(URL::full());
            OpenGraph::addImage([asset($setting->site_logo), 'size' => 300]);

            JsonLd::setTitle($page->meta_title);
            JsonLd::setDescription($page->meta_description);
            JsonLd::addImage(asset($setting->site_logo));

            // Return values
            $returnValues = compact('config', 'setting', 'page');

            return view("website.pages.refund-policy.index", $returnValues);
        } else {
            abort(404);
        }
    }

    // Terms and conditions page
    public function webTerms()
    {
        // Queries
        $config = Configuration::get();

        // Check website
        if ($config[43]->config_value == "yes") {
            $setting = Setting::where('status', 1)->first();

            $page = Page::where("page_name", "terms-and-conditions")->first();

            $page->page_content = str_replace("indigo", $config[11]->config_value, $page->page_content);

            // Seo Tools
            SEOTools::setTitle($page->meta_title);
            SEOTools::setDescription($page->meta_description);

            SEOMeta::setTitle($page->meta_title);
            SEOMeta::setDescription($page->meta_description);
            SEOMeta::addMeta('article:section', $page->page_name . ' - ' . $page->meta_description, 'property');
            SEOMeta::addKeyword([$page->meta_keywords]);

            OpenGraph::setTitle($page->meta_title);
            OpenGraph::setDescription($page->meta_description);
            OpenGraph::setUrl(URL::full());
            OpenGraph::addImage([asset($setting->site_logo), 'size' => 300]);

            JsonLd::setTitle($page->meta_title);
            JsonLd::setDescription($page->meta_description);
            JsonLd::addImage(asset($setting->site_logo));

            // Return values
            $returnValues = compact('config', 'setting', 'page');

            return view("website.pages.terms-and-conditions.index", $returnValues);
        } else {
            abort(404);
        }
    }

    // Businesses page
    public function businesses(Request $request, $business_category_slug)
    {
        // Queries
        $config = Configuration::get();
        $setting = Setting::where('status', 1)->first();
        // Get cities
        $cities = City::where('country_id', $config[34]->config_value)->get();

        // Pages
        $pages = Page::where("page_name", "home")->get();

        // Seo Tools
        SEOTools::setTitle($pages[0]->meta_title);
        SEOTools::setDescription($pages[0]->meta_description);

        SEOMeta::setTitle($pages[0]->meta_title);
        SEOMeta::setDescription($pages[0]->meta_description);
        SEOMeta::addMeta('article:section', $pages[0]->page_name . ' - ' . $pages[0]->meta_description, 'property');
        SEOMeta::addKeyword([$pages[0]->meta_keywords]);

        OpenGraph::setTitle($pages[0]->meta_title);
        OpenGraph::setDescription($pages[0]->meta_description);
        OpenGraph::setUrl(URL::full());
        OpenGraph::addImage([asset($setting->site_logo), 'size' => 300]);

        JsonLd::setTitle($pages[0]->meta_title);
        JsonLd::setDescription($pages[0]->meta_description);
        JsonLd::addImage(asset($setting->site_logo));

        // Get business category
        $business_category = BusinessCategory::where('business_category_slug', $business_category_slug)->first();

        $is_request_query = request('city') != null ? true : false;

        // Check if business category exists
        if (!$business_category) {
            $businesses = collect([]);

            // Return values
            $returnValues = compact('config', 'setting', 'businesses', 'cities');

            return view("website.pages.businesses.index", $returnValues);
        }

        // Get business category id
        $business_category_id = $business_category->business_category_id;

        // Check website
        if ($config[43]->config_value == "yes") {


            if ($is_request_query) {
                // Get city
                $city = City::where('name', $request->city)->first();

                // Check if city exists
                if (!$city) {
                    $businesses = collect([]);

                    // Return values
                    $returnValues = compact('config', 'setting', 'businesses', 'cities');

                    return view("website.pages.businesses.index", $returnValues);
                }

                // Get city id
                $city_id = $city->id;

                // Get businesses
                $businesses = DB::table('businesses')
                    ->leftJoin('business_services', 'businesses.business_id', '=', 'business_services.business_id')
                    ->leftJoin('business_employees', 'businesses.business_id', '=', 'business_employees.business_id')
                    ->leftJoin('states', 'businesses.business_state', '=', 'states.id')
                    ->leftJoin('cities', 'businesses.business_city', '=', 'cities.id')
                    ->select(
                        'businesses.*',
                        DB::raw('COUNT(DISTINCT business_services.id) as service_count'),
                        DB::raw('COUNT(DISTINCT business_employees.id) as employee_count'),
                        'states.name as state',
                        'cities.name as city',
                    )
                    ->where('businesses.business_category_id', $business_category_id)
                    ->where('businesses.business_city', $city_id)
                    ->where('businesses.status', 1)
                    ->groupBy(
                        'businesses.id',
                        'businesses.user_id',
                        'businesses.business_id',
                        'businesses.business_name',
                        'businesses.business_description',
                        'businesses.business_category_id',
                        'businesses.business_cover_image_url',
                        'businesses.business_logo_url',
                        'businesses.business_website_url',
                        'businesses.business_email',
                        'businesses.business_phone',
                        'businesses.business_address',
                        'businesses.business_country',
                        'businesses.business_state',
                        'businesses.business_city',
                        'businesses.tax_number',
                        'businesses.status',
                        'businesses.offline_transaction_details',
                        'businesses.created_at',
                        'businesses.updated_at',
                        'states.name',
                        'cities.name'
                    )
                    ->having('service_count', '>=', 1)
                    ->having('employee_count', '>=', 1)
                    ->get();
            } else {
                // Get businesses
                $businesses = DB::table('businesses')
                    ->leftJoin('business_services', 'businesses.business_id', '=', 'business_services.business_id')
                    ->leftJoin('business_employees', 'businesses.business_id', '=', 'business_employees.business_id')
                    ->leftJoin('states', 'businesses.business_state', '=', 'states.id')
                    ->leftJoin('cities', 'businesses.business_city', '=', 'cities.id')
                    ->select(
                        'businesses.*',
                        DB::raw('COUNT(DISTINCT business_services.id) as service_count'),
                        DB::raw('COUNT(DISTINCT business_employees.id) as employee_count'),
                        'states.name as state',
                        'cities.name as city',
                    )
                    ->where('businesses.business_category_id', $business_category_id)
                    ->where('businesses.status', 1)
                    ->groupBy(
                        'businesses.id',
                        'businesses.user_id',
                        'businesses.business_id',
                        'businesses.business_name',
                        'businesses.business_description',
                        'businesses.business_category_id',
                        'businesses.business_cover_image_url',
                        'businesses.business_logo_url',
                        'businesses.business_website_url',
                        'businesses.business_email',
                        'businesses.business_phone',
                        'businesses.business_address',
                        'businesses.business_country',
                        'businesses.business_state',
                        'businesses.business_city',
                        'businesses.tax_number',
                        'businesses.status',
                        'businesses.offline_transaction_details',
                        'businesses.created_at',
                        'businesses.updated_at',
                        'states.name',
                        'cities.name'
                    )
                    ->having('service_count', '>=', 1)
                    ->having('employee_count', '>=', 1)
                    ->get();
            }

            // Get cities
            $cities = City::where('country_id', $config[34]->config_value)->get();

            // Return values
            $returnValues = compact('config', 'setting', 'businesses', 'cities');

            return view("website.pages.businesses.index", $returnValues);
        } else {
            $businesses = collect([]);

            // Return values
            $returnValues = compact('config', 'setting', 'businesses', 'cities');

            return view("website.pages.businesses.index", $returnValues);
        }
    }

    // Business page
    public function business($business_id)
    {
        // Queries
        $config = Configuration::get();
        $setting = Setting::where('status', 1)->first();

        // Pages
        $pages = Page::where("page_name", "home")->get();

        foreach ($pages as $page) {
            $page->page_content = str_replace("indigo", $config[11]->config_value, $page->page_content);
        }

        // Seo Tools
        SEOTools::setTitle($pages[0]->meta_title);
        SEOTools::setDescription($pages[0]->meta_description);

        SEOMeta::setTitle($pages[0]->meta_title);
        SEOMeta::setDescription($pages[0]->meta_description);
        SEOMeta::addMeta('article:section', $pages[0]->page_name . ' - ' . $pages[0]->meta_description, 'property');
        SEOMeta::addKeyword([$pages[0]->meta_keywords]);

        OpenGraph::setTitle($pages[0]->meta_title);
        OpenGraph::setDescription($pages[0]->meta_description);
        OpenGraph::setUrl(URL::full());
        OpenGraph::addImage([asset($setting->site_logo), 'size' => 300]);

        JsonLd::setTitle($pages[0]->meta_title);
        JsonLd::setDescription($pages[0]->meta_description);
        JsonLd::addImage(asset($setting->site_logo));

        // Validation
        if ($config[43]->config_value == "yes") {
            // Get business user id
            $business_user_id = Business::where('business_id', $business_id)->first()->user_id;

            // Get user
            $user = User::where('user_id', $business_user_id)->first();

            // Plan details
            $planDetails = json_decode($user->plan_details, true);

            // Get plan features
            $planFeatures = is_string($planDetails['plan_features'])
                ? json_decode($planDetails['plan_features'], true)
                : $planDetails['plan_features'];

            // Get no of bookings
            $noOfBookings = (int) $planFeatures['no_of_bookings'];

            // Get plan start date
            $plan_start_date = Carbon::parse($planDetails['plan_start_date'])->format('Y-m-d');

            // Get plan end date
            $plan_end_date = Carbon::parse($planDetails['plan_end_date'])->format('Y-m-d');

            // Get successed bookings
            $Successed_bookings = Booking::where('business_id', $business_id)->where('booking_date', '>=', $plan_start_date)->where('booking_date', '<=', $plan_end_date)->where('status', 1)->count();

            if ($noOfBookings > $Successed_bookings) {
                $is_booking_available = true;
            } else {
                $is_booking_available = false;
            }

            // Get setting
            $setting = Setting::where('status', 1)->first();

            // Businesses
            $business = Business::where('business_id', $business_id)
                ->leftJoin('states', 'businesses.business_state', '=', 'states.id')
                ->leftJoin('cities', 'businesses.business_city', '=', 'cities.id')
                ->select('businesses.*', 'states.name as state_name', 'cities.name as city_name')
                ->where('status', 1)
                ->first();

            // Services
            $business_services = BusinessService::where('business_id', $business_id)->where('status', 1)->get();

            // Employees
            $business_employees = BusinessEmployee::where('business_id', $business_id)->where('status', 1)->get();

            //Currency
            $currency = Currency::where('iso_code', $config['1']->config_value)->first();

            // Return values
            $returnValues = compact('config', 'setting', 'business_services', 'business_employees', 'business', 'is_booking_available', 'currency');

            return view("website.pages.business.index", $returnValues);
        } else {
            abort(404);
        }
    }
}
