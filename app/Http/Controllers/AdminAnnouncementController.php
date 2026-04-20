<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Staff; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AdminAnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(10);
        $staffMembers = Staff::latest()->get(); 
        
        return view('admin.announcements.index', compact('announcements', 'staffMembers'));
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', 
            'is_active' => 'boolean',
            'expires_at' => 'nullable|date|after:today', 
        ]);

        $data = $request->only(['title', 'description', 'expires_at']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('announcements', 'public');
            $data['image_path'] = $path;
        }

        Announcement::create($data);

        return redirect()->route(Auth::user()->role . '.announcements.index')
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', 
            'is_active' => 'boolean',
            'expires_at' => 'nullable|date', 
        ]);

        $data = $request->only(['title', 'description', 'expires_at']);
        $data['is_active'] = $request->has('is_active'); 

        if ($request->hasFile('image')) {
            if ($announcement->image_path) {
                Storage::disk('public')->delete($announcement->image_path);
            }
            $path = $request->file('image')->store('announcements', 'public');
            $data['image_path'] = $path;
        }

        $announcement->update($data);

        return redirect()->route(Auth::user()->role . '.announcements.index')
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

    public function storeStaff(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', 
        ]);

        $data = $request->only(['name', 'role']);
        $data['is_active'] = true; // NEW: Default to active

        if ($request->hasFile('picture')) {
            $path = $request->file('picture')->store('staff_pictures', 'public');
            $data['picture_path'] = $path;
        }

        Staff::create($data);

        return redirect()->route(Auth::user()->role . '.announcements.index')->with('success', 'Staff member added successfully.');
    }

    public function updateStaff(Request $request, Staff $staff)
    {
        // NEW: Check if staff is inactive
        if (!$staff->is_active) {
            return back()->with('error', "Cannot edit an inactive staff member. Please reactivate them first.");
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', 
        ]);

        $data = $request->only(['name', 'role']);

        if ($request->hasFile('picture')) {
            if ($staff->picture_path) {
                Storage::disk('public')->delete($staff->picture_path);
            }
            $path = $request->file('picture')->store('staff_pictures', 'public');
            $data['picture_path'] = $path;
        }

        $staff->update($data);

        return redirect()->route(Auth::user()->role . '.announcements.index')->with('success', 'Staff member updated successfully.');
    }

    public function destroyStaff(Staff $staff)
    {
        if ($staff->picture_path) {
            Storage::disk('public')->delete($staff->picture_path);
        }
        $staff->delete();

        return redirect()->route('admin.announcements.index')->with('success', 'Staff member removed successfully.');
    }

    /**
     * Deactivate a staff member
     */
    public function deactivateStaff(Request $request, $id)
    {
        $request->validate([
            'inactive_reason' => 'required|string|max:1000',
        ]);

        $staff = Staff::findOrFail($id);

        $staff->update([
            'is_active' => false,
            'inactive_reason' => $request->inactive_reason,
            'deactivated_at' => now(),
        ]);

        return back()->with('success', "Staff member '{$staff->name}' has been deactivated.");
    }

    /**
     * Reactivate a staff member
     */
    public function reactivateStaff($id)
    {
        $staff = Staff::findOrFail($id);

        $staff->update([
            'is_active' => true,
            'inactive_reason' => null,
            'deactivated_at' => null,
        ]);

        return back()->with('success', "Staff member '{$staff->name}' has been reactivated.");
    }
}