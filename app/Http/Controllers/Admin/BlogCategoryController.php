<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogCategoryController extends Controller
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

    // Blog Category
    public function index()
    {
        $blog_categories = BlogCategory::where('status', '>=', 0)->get();
        return view('admin.pages.blog-categories.index', compact('blog_categories'));
    }

    // Create blog category
    public function createBlogCategory()
    {
        return view('admin.pages.blog-categories.create');
    }

    // Store blog category
    public function publishBlogCategory(Request $request)
    {
        
        // Validation
        $validator = Validator::make($request->all(), [
            'blog_category_name' => 'required|string|max:255',
            'blog_category_slug' => 'required|string',
        ]);

        // Validate content 
        if ($validator->fails()) {
            return redirect()->route('admin.blog-categories.index')->with('failed', trans('Validation Failed!'))->withErrors($validator)->withInput();
        }

        $category = $request->blog_category_slug;

        $old_categories = BlogCategory::where('status', '>=', 0)->pluck('blog_category_slug')->toArray();   

        if (in_array($category, $old_categories)) {
            return back()->with('failed', trans('Blog Category already exists!'))->withInput();
        }

        $blog_category = new BlogCategory();
        $blog_category->blog_category_id = uniqid();
        $blog_category->blog_category_name = $request->blog_category_name;
        $blog_category->blog_category_slug = $request->blog_category_slug;

        $blog_category->save();

        return redirect()->route('admin.blog-categories.index')->with('success', trans('New Blog Category Created Successfully!'));
    }

    // Edit blog category
    public function editBlogCategory(Request $request, $blog_category_id)
    {
        $blog_category_details = BlogCategory::where('blog_category_id', $blog_category_id)->first();
        return view('admin.pages.blog-categories.edit', compact('blog_category_details'));
    }

    // Update blog category
    public function updateBlogCategory(Request $request, $blog_category_id)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'blog_category_name' => 'required|string|max:255',
            'blog_category_slug' => 'required|string',
        ]);

        // Validate content 
        if ($validator->fails()) {
            return redirect()->route('admin.blog-categories.index')->with('failed', trans('Validation Failed!'))->withErrors($validator)->withInput();
        }

        $blog_category = BlogCategory::where('blog_category_id', $blog_category_id)->firstOrFail();

        $blog_category->blog_category_name = $request->blog_category_name;
        $blog_category->blog_category_slug = $request->blog_category_slug;

        $blog_category->save();

        return redirect()->route('admin.blog-categories.index')->with('success', trans('Blog Category Updated Successfully!'));
    }

    // Update status
    public function actionBlogCategory(Request $request)
    {
        // Check status
        switch ($request->query('mode')) {
            case 'unpublish':
                $status = 0;
                break;

            case 'delete':
                $status = -1;
                break;

            default:
                $status = 1;
                break;
        }

        // Update status
        BlogCategory::where('blog_category_id', $request->query('blog_category_id'))->update(['status' => $status]);

        // Redirect
        return redirect()->route('admin.blog-categories.index')->with('success', trans('Status updated successfully!'));
    }
}
