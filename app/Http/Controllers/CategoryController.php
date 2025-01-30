<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return Inertia::render('Words/categories', [
            'categories' => $categories,
        ]);
    }

    /**
     * validates the category.
     *
     * @param Request $request
     * @return array
     */
    private function validateCategory(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|unique:categories,name|max:255',
            'description' => 'nullable|string|max:1000',
        ]);
    }

    //TODO: add error handling
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
     public function store(Request $request)
     {
       $validated = $this->validateCategory($request);

       $category = Category::create([
          'name' => $validated['name'],
          'slug' => Str::slug($request->name),
          'description' => $validated['description'] ?? null,
        ]);

       return response()->json(['message' => 'دسته‌بندی با موفقیت ایجاد شد', 'category' => $category], 201);
     }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Inertia\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $category = Category::findOrFail($id);

        // اصلاح اعتبارسنجی برای مجاز کردن نام فعلی در دیتابیس
        $validated = $request->validate([
          'name' => "required|string|unique:categories,name,{$category->id}",
          'description' => 'nullable|string',
        ]);

        // به‌روزرسانی مقادیر دسته‌بندی
        $category->update([
          'name' => $validated['name'],
          'slug' => Str::slug($validated['name']),
          'description' => $request->filled('description') ? $validated['description'] : $category->description,
        ]);

        return response()->json(['message' => 'دسته‌بندی با موفقیت به‌روزرسانی شد', 'category' => $category], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'دسته‌بندی با موفقیت حذف شد'], 200);
    }
}
