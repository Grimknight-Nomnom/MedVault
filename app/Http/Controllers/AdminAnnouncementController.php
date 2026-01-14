<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminAnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(10);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['title', 'description']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('announcements', 'public');
            $data['image_path'] = $path;
        }

        Announcement::create($data);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['title', 'description']);
        $data['is_active'] = $request->has('is_active'); // Checkbox handling

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($announcement->image_path) {
                Storage::disk('public')->delete($announcement->image_path);
            }
            $path = $request->file('image')->store('announcements', 'public');
            $data['image_path'] = $path;
        }

        $announcement->update($data);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->image_path) {
            Storage::disk('public')->delete($announcement->image_path);
        }
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }
}