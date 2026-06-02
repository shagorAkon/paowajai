<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::where('position', 'hero')->orderBy('sort_order')->get();
        return response()->json($banners);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120', // 5MB max
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
        ]);

        $imagePath = $request->file('image')->store('banners', 'public');

        $banner = Banner::create([
            'position' => 'hero',
            'image' => $imagePath,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'button_text' => $request->button_text,
            'link' => $request->link,
            'is_active' => true,
            'sort_order' => Banner::where('position', 'hero')->max('sort_order') + 1,
        ]);

        Cache::forget('storefront_home_data');

        return response()->json([
            'message' => 'Banner added successfully',
            'banner' => $banner
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|max:5120',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['title', 'subtitle', 'button_text', 'link']);

        if ($request->hasFile('image')) {
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        Cache::forget('storefront_home_data');

        return response()->json([
            'message' => 'Banner updated successfully',
            'banner' => $banner
        ]);
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }
        
        $banner->delete();

        Cache::forget('storefront_home_data');

        return response()->json(['message' => 'Banner deleted successfully']);
    }
}
