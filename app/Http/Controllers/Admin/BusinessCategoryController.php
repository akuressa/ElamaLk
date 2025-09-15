<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\BusinessCategory;
use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Support\Facades\Validator;

class BusinessCategoryController extends Controller
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

    // Business Category
    public function index()
    {
        $business_categories = BusinessCategory::where('status', '>=', 0)->orderBy('created_at', 'desc')->get();
        return view('admin.pages.business-categories.index', compact('business_categories'));
    }

    // Add Business Category
    public function add()
    {
        return view('admin.pages.business-categories.add');
    }

    // Save Business Category
    public function saveBusinessCategory(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'business_category_name' => 'required|string|max:255',
            'business_category_description' => 'required|string',
            'business_category_logo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:' . (int) env('SIZE_LIMIT', 2048),
        ]);

        // Validation error
        if ($validator->fails()) {
            return back()->with('failed', trans('Validation Failed!'))->withErrors($validator)->withInput();
        }

        $category = Str::kebab($request->business_category_name);

        $old_categories = BusinessCategory::where('status', '>=', 0)->pluck('business_category_slug')->toArray();   

        if (in_array($category, $old_categories)) {
            return back()->with('failed', trans('Business Category already exists!'))->withInput();
        }

        // Save Business Category
        $business_category = new BusinessCategory();
        $business_category->business_category_id = uniqid();
        $business_category->business_category_name = ucfirst($request->business_category_name);
        $business_category->business_category_description = ucfirst($request->business_category_description);

        if ($request->hasFile('business_category_logo_url')) {
            $file = $request->file('business_category_logo_url');
            $originalName = $file->getClientOriginalName();
            $uploadLogoImage = pathinfo($originalName, PATHINFO_FILENAME);
            $uploadExtension = pathinfo($originalName, PATHINFO_EXTENSION);
            $uploadPath = 'images/admin/business_categories/';
            $newLogoImage = $uploadPath . $uploadLogoImage . '_' . uniqid() . '.' . $uploadExtension;

            $business_category->business_category_logo_url = $newLogoImage;

            // Move the uploaded file to the destination path
            $file->move(public_path($uploadPath), $newLogoImage);
        }

        $business_category->business_category_slug = Str::kebab($request->business_category_name);

        $business_category->status = 1;

        $business_category->save();

        return redirect()->route('admin.business-categories.index')->with('success', trans('New Business Category Created Successfully!'));
    }

    // Activate Business Category
    public function activationBusinessCategory(Request $request)
    {
        // Get plan details
        $business_categories = BusinessCategory::where('business_category_id', $request->query('business_category_id'))->first();
        $status = ($business_categories->status == 0) ? 1 : 0;

        // Update status
        BusinessCategory::where('business_category_id', $request->query('business_category_id'))->update(['status' => $status]);
        return redirect()->route('admin.business-categories.index')->with('success', trans('Business Category Status Updated Successfully!'));
    }

    // Edit Business Category
    public function editBusinessCategory(Request $request, $business_category_id)
    {
        $business_category = BusinessCategory::where('business_category_id', $business_category_id)->first();
        return view('admin.pages.business-categories.edit', compact('business_category'));
    }

    // Update Business Category
    public function updateBusinessCategory(Request $request, $business_category_id)
    {
        
        // Validation
        $validator = Validator::make($request->all(), [
            'business_category_name' => 'required|string|max:255',
            'business_category_description' => 'required|string',
            'business_category_logo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:' . (int) env('SIZE_LIMIT', 2048),
        ]); 

        // Validation error
        if ($validator->fails()) {
            return back()->with('failed', trans('Validation Failed!'))->withErrors($validator)->withInput();
        }

        $business_category = BusinessCategory::where('business_category_id', $business_category_id)->firstOrFail();

        // Update the basic fields
        $business_category->business_category_name = ucfirst($request->business_category_name);
        $business_category->business_category_description = ucfirst($request->business_category_description);
        $business_category->business_category_slug = Str::kebab($request->business_category_name);

        // Handle logo upload
        if ($request->hasFile('business_category_logo_url')) {
            $file = $request->file('business_category_logo_url');
            $originalName = $file->getClientOriginalName();
            $uploadPath = 'images/admin/business_categories/';
            $uploadLogoImage = pathinfo($originalName, PATHINFO_FILENAME);
            $uploadExtension = pathinfo($originalName, PATHINFO_EXTENSION);
            $newLogoImage = $uploadPath . $uploadLogoImage . '_' . uniqid() . '.' . $uploadExtension;

            // Delete the old logo if it exists
            if (!empty($business_category->business_category_logo_url) && file_exists(public_path($business_category->business_category_logo_url))) {
                unlink(public_path($business_category->business_category_logo_url));
            }

            // Move the uploaded file to the destination path
            $file->move(public_path($uploadPath), $newLogoImage);
            $business_category->business_category_logo_url = $newLogoImage;
        }

        $business_category->save();

        return redirect()->route('admin.business-categories.index')->with('success', trans('Business Category Details Updated Successfully!'));
    }

    // Delete Business Category
    public function deleteBusinessCategory(Request $request)
    {
        $status = -1;

        // Update status
        BusinessCategory::where('business_category_id', $request->query('business_category_id'))->update(['status' => $status]);
        return redirect()->route('admin.business-categories.index')->with('success', trans('Business Category Deleted Successfully!'));
    }
}
