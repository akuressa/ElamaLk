<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Pages
    public function index()
    {
        $pages = DB::table('pages')
            ->select(DB::raw('MAX(id) as id'), DB::raw('MAX(page_name) as page_name'), DB::raw('MAX(page_slug) as page_slug'), DB::raw('MAX(section_name) as section_name'), DB::raw('MAX(status) as status'))
            ->where('status', ">=", 0)
            ->groupBy('page_name')
            ->orderByDesc('page_name')
            ->get();

        return view('admin.pages.pages.index', compact('pages'));
    }

    public function pageStatus($page_name, $status){
        Page::where('page_name', $page_name)->update(['status' => $status]);
        return redirect()->route('admin.pages.index')->with('success', trans('Status updated successfully!'));
    }

    // Edit Page
    public function edit(Request $request)
    {
        $pages = Page::where('page_name', $request->page_name)->get();

        $page_name = Page::where('page_name', $request->page_name)->first()->page_name;

        if ($pages == null) {
            return redirect()->route('admin.pages.index')->with('failed', trans('No Page Found!'));
        }

        return view('admin.pages.pages.edit', compact('pages', 'page_name'));
    }

    // Update Page
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page_content' => 'required|string',
        ]);

        // Validate page content
        if ($validator->fails()) {
            return redirect()->route('admin.pages.index')
                ->with('failed', trans('Page Content is required!'))
                ->withErrors($validator)
                ->withInput();
        }

        // Get page
        $page = Page::where('page_id', $request->page_id)->first();

        Page::where('page_id', $request->page_id)->update([
            'page_name' => $page->page_name,
            'page_slug' => Str::kebab($page->page_name),
            'page_content' => $request->page_content,
            'meta_title' => $page->meta_title,
            'meta_description' => $page->meta_description,
            'meta_keywords' => $page->meta_keywords,
        ]);

        return redirect()->route('admin.pages.index')->with('success', trans('Page Updated Successfully!'));
    }

    // Update SEO
    public function updateSeo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'meta_title' => 'required|string',
            'meta_description' => 'required|string',
            'meta_keywords' => 'required|string',
        ]);

        // Validate page content
        if ($validator->fails() || $request->page_name == null) {
            return redirect()->route('admin.pages.index')
                ->with('failed', trans('Page Content is required!'))
                ->withErrors($validator)
                ->withInput();
        }

        Page::where('page_name', $request->page_name)->update([
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ]);

        return redirect()->route('admin.pages.index')->with('success', trans('Page SEO Updated Successfully!'));
    }
}
