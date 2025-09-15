<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogController extends Controller
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

    // Check slug exists
    public function createSlug($title, $count = 0)
    {
        // Generate the initial slug from the title
        $slug = Str::slug($title);

        // If a count is provided, append it to the slug
        if ($count > 0) {
            $slug .= '-' . $count;
        }

        // Check if the slug already exists in the database
        $existingSlug = Blog::where('blog_slug', $slug)->first();

        // If the slug exists, recursively call this method with an incremented count
        if ($existingSlug) {
            return $this->createSlug($title, $count + 1);
        }

        // If the slug does not exist, return it
        return $slug;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // Blogs
    public function index()
    {
        // Queries            

        $blogs =  Blog::leftJoin('blog_categories', 'blogs.blog_category_id', '=', 'blog_categories.blog_category_id')
            ->select('blogs.*', 'blog_categories.blog_category_name')
            ->where('blogs.status', '>=', 0)
            ->get();


        // View
        return view('admin.pages.blogs.index', compact('blogs'));
    }

    // Add Blog
    public function createBlog()
    {
        // Queries
        $blog_categories = BlogCategory::where('status', '>=', 0)->get();
        $config = Configuration::get();

        // View
        return view('admin.pages.blogs.create', compact('blog_categories', 'config'));
    }

    // Publish Blog
    public function publishBlog(Request $request)
    {

        // Validation
        $validator = Validator::make($request->all(), [
            'blog_cover' => ['required', 'mimes:jpg,jpeg,png,webp'],
            'blog_name' => 'required|min:3',
            'blog_slug' => 'required|min:3',
            'short_description' => 'required|min:3',
            'long_description' => 'required|min:3',
            'category_id' => 'required',
            'tags' => 'required',
            'seo_title' => 'required',
            'seo_description' => 'required',
            'seo_keywords' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->with('failed', trans('Validation Failed!'))->with('failed', $validator->messages()->all()[0])->withInput();
        }

        // Cover image
        $blogCoverImage = $request->blog_cover->getClientOriginalName();
        $UploadCoverImage = pathinfo($blogCoverImage, PATHINFO_FILENAME);
        $UploadExtension = pathinfo($blogCoverImage, PATHINFO_EXTENSION);

        // Upload image
        if ($UploadExtension == "jpeg" || $UploadExtension == "png" || $UploadExtension == "jpg" || $UploadExtension == "webp") {
            // Upload image
            $CoverImage = 'images/blogs/cover-images/' . $UploadCoverImage . '_' . uniqid() . '.' . $UploadExtension;
            $request->blog_cover->move(public_path('images/blogs/cover-images'), $CoverImage);
        }

        // Generate a unique slug for the blog post
        $existingSlug = Blog::where('blog_slug', $request->blog_slug)->first();

        if ($existingSlug) {
            $blogSlug = $this->createSlug($request->blog_name);
        } else {
            $blogSlug = $request->blog_slug;
        }

        // Save Blog
        $blog = new Blog();
        $blog->blog_id = uniqid();
        $blog->blog_cover = $CoverImage;
        $blog->blog_name = ucfirst($request->blog_name);
        $blog->blog_slug = $blogSlug;
        $blog->short_description = ucfirst($request->short_description);
        $blog->long_description = $request->long_description;
        $blog->blog_category_id = $request->category_id;
        $blog->tags = ucfirst($request->tags);
        $blog->title = ucfirst($request->seo_title);
        $blog->description = ucfirst($request->seo_description);
        $blog->keywords = $request->seo_keywords;
        $blog->save();

        // Redirect
        return redirect()->route('admin.blogs.index')->with('success', trans('Blog published successfully!'));
    }

    // Edit Blog
    public function editBlog($blog_id)
    {
        // Queries
        $blogsCategories = BlogCategory::where('status', '>=', 0)->get();
        $config = Configuration::get();

        // Get page details
        $blogDetails = Blog::where('blog_id', $blog_id)->where('status', '>=', 0)->first();

        // View
        return view('admin.pages.blogs.edit', compact('blogsCategories', 'blogDetails', 'config'));
    }

    // Update Blog
    public function updateBlog(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'blog_name' => 'required|min:3',
            'blog_slug' => 'required|min:3',
            'short_description' => 'required|min:3',
            'long_description' => 'required|min:3',
            'category_id' => 'required',
            'tags' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('failed', trans('Validation Failed!'))->with('failed', $validator->messages()->all()[0])->withInput();
        }

        // Blog id
        $blogId = $request->blog_id;


        // Check cover image
        if ($request->hasFile('blog_cover')) {
            // Validation
            $validator = Validator::make($request->all(), [
                'blog_cover' => ['required', 'mimes:jpg,jpeg,png,webp'],
            ]);

            if ($validator->fails()) {
                return back()->with('failed', $validator->messages()->all()[0])->withInput();
            }

            // Cover image
            $blogCoverImage = $request->blog_cover->getClientOriginalName();
            $UploadCoverImage = pathinfo($blogCoverImage, PATHINFO_FILENAME);
            $UploadExtension = pathinfo($blogCoverImage, PATHINFO_EXTENSION);

            // Upload image
            if ($UploadExtension == "jpeg" || $UploadExtension == "png" || $UploadExtension == "jpg" || $UploadExtension == "webp") {
                // Upload image
                $CoverImage = 'images/blogs/cover-images/' . $UploadCoverImage . '_' . uniqid() . '.' . $UploadExtension;
                $request->blog_cover->move(public_path('images/blogs/cover-images'), $CoverImage);
            }

            // Update blog cover image
            Blog::where('blog_id', $blogId)->update(['blog_cover' => $CoverImage]);
        }

        // Generate a unique slug for the blog post
        $existingSlug = Blog::where('blog_slug', $request->blog_slug)->first();

        if ($existingSlug) {
            $blogSlug = $request->blog_slug;
        } else {
            $blogSlug = $this->createSlug($request->blog_name);
        }

        // Update blog details
        Blog::where('blog_id', $blogId)->update([
            'blog_name' => ucfirst($request->blog_name),
            'blog_slug' => $blogSlug,
            'short_description' => $request->short_description,
            'long_description' => $request->long_description,
            'blog_category_id' => $request->category_id,
            'tags' => ucfirst($request->tags),
            'title' => ucfirst($request->seo_title),
            'description' => ucfirst($request->seo_description),
            'keywords' => $request->seo_keywords
        ]);

        // Redirect
        return redirect()->route('admin.blogs.index')->with('success', trans('Blog Updated Successfully!'));
    }

    // Actions
    public function actionBlog(Request $request)
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
        Blog::where('blog_id', $request->query('blog_id'))->update(['status' => $status]);

        // Redirect
        return redirect()->route('admin.blogs.index')->with('success', trans('Status updated successfully!'));
    }
}
