<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Donation;
use App\Models\Receipt;
use App\Models\Cause;
use App\Models\SevaProject;
use App\Models\Destination;
use App\Models\Event;
use App\Models\HeroSlide;
use App\Models\OrganizationSetting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalDonations = Donation::where('status', 'verified')->sum('amount');
        $donationsCount = Donation::where('status', 'verified')->count();
        $usersCount = User::count();
        $sevasCount = SevaProject::count();
        $destinationsCount = Destination::count();
        $eventsCount = Event::count();

        $recentDonations = Donation::with(['user', 'cause'])->latest()->take(10)->get();
        $activityLogs = ActivityLog::latest()->take(15)->get();

        return view('admin.dashboard', compact(
            'totalDonations',
            'donationsCount',
            'usersCount',
            'sevasCount',
            'destinationsCount',
            'eventsCount',
            'recentDonations',
            'activityLogs'
        ));
    }

    // --- SEVA PROJECTS CMS ---
    public function sevas()
    {
        $sevas = SevaProject::orderBy('sort_order', 'asc')->get();
        $causes = Cause::all();
        return view('admin.sevas', compact('sevas', 'causes'));
    }

    public function storeSeva(Request $request)
    {
        $request->validate([
            'title_hi' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'category' => 'required|string',
            'summary_hi' => 'required|string',
            'description_hi' => 'required|string',
            'featured_image' => 'required|string',
        ]);

        SevaProject::create([
            'title_hi' => $request->title_hi,
            'title_en' => $request->title_en,
            'slug' => str()->slug($request->title_en),
            'category' => $request->category,
            'summary_hi' => $request->summary_hi,
            'summary_en' => $request->summary_en ?? $request->summary_hi,
            'description_hi' => $request->description_hi,
            'description_en' => $request->description_en ?? $request->description_hi,
            'featured_image' => $request->featured_image,
            'cause_id' => $request->cause_id,
            'is_published' => $request->boolean('is_published'),
        ]);

        ActivityLog::log('Seva CMS', 'create', 'Created Seva Project: ' . $request->title_en);
        return back()->with('success', 'Seva project created successfully.');
    }

    public function deleteSeva($id)
    {
        $seva = SevaProject::findOrFail($id);
        $name = $seva->title_en;
        $seva->delete();
        ActivityLog::log('Seva CMS', 'delete', 'Deleted Seva Project: ' . $name);
        return back()->with('success', 'Seva project deleted successfully.');
    }

    // --- DESTINATIONS CMS ---
    public function destinations()
    {
        $destinations = Destination::orderBy('sort_order', 'asc')->get();
        return view('admin.destinations', compact('destinations'));
    }

    public function storeDestination(Request $request)
    {
        $request->validate([
            'name_hi' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'tagline_hi' => 'required|string',
            'description_hi' => 'required|string',
            'featured_image' => 'required|string',
        ]);

        Destination::create([
            'name_hi' => $request->name_hi,
            'name_en' => $request->name_en,
            'slug' => str()->slug($request->name_en),
            'tagline_hi' => $request->tagline_hi,
            'tagline_en' => $request->tagline_en ?? $request->tagline_hi,
            'summary_hi' => $request->tagline_hi,
            'description_hi' => $request->description_hi,
            'description_en' => $request->description_en ?? $request->description_hi,
            'featured_image' => $request->featured_image,
            'is_published' => $request->boolean('is_published'),
        ]);

        ActivityLog::log('Destinations CMS', 'create', 'Created Sacred Spot: ' . $request->name_en);
        return back()->with('success', 'Chitrakoot destination created successfully.');
    }

    public function deleteDestination($id)
    {
        $dest = Destination::findOrFail($id);
        $name = $dest->name_en;
        $dest->delete();
        ActivityLog::log('Destinations CMS', 'delete', 'Deleted Sacred Spot: ' . $name);
        return back()->with('success', 'Chitrakoot destination deleted successfully.');
    }

    // --- USERS & ROLE ASSIGNMENT CMS ---
    public function users()
    {
        $users = User::with('membership')->latest()->get();
        return view('admin.users', compact('users'));
    }

    public function updateUserRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'role' => 'required|in:super_admin,content_admin,membership_admin,donation_admin,media_editor,viewer,donor,member',
            'approval_status' => 'required|in:pending,approved,rejected',
        ]);

        $oldRole = $user->role;
        $user->update([
            'role' => $request->role,
            'approval_status' => $request->approval_status,
        ]);

        ActivityLog::log('Users CMS', 'role_update', "Updated User #{$user->id} ({$user->name}) role from {$oldRole} to {$request->role}.");
        return back()->with('success', "Updated {$user->name}'s role to {$request->role}.");
    }

    // --- SETTINGS CMS ---
    public function settings()
    {
        $settings = OrganizationSetting::getSettings();
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $settings = OrganizationSetting::getSettings();
        $settings->update($request->only([
            'org_name',
            'address',
            'phone',
            'email',
            'reg_number',
            'pan_number',
            'gst_number',
            'receipt_header_title',
            'receipt_legal_note',
            'razorpay_key_id',
            'razorpay_key_secret',
            'smtp_host',
            'smtp_port',
            'smtp_username',
            'smtp_password',
            'smtp_from_address',
            'smtp_from_name',
        ]));

        ActivityLog::log('Settings CMS', 'update', 'Updated organization details and gateway/email credentials.');
        return back()->with('success', 'Settings updated successfully.');
    }

    // --- BLOGS CMS ---
    public function blogs()
    {
        $posts = \App\Models\Post::latest()->get();
        return view('admin.blogs', compact('posts'));
    }

    public function storeBlog(Request $request)
    {
        $request->validate([
            'title_hi' => 'required|string|max:255',
            'category' => 'required|string',
            'excerpt' => 'required|string',
            'content_hi' => 'required|string',
            'featured_image' => 'required|string',
        ]);

        \App\Models\Post::create([
            'title_hi' => $request->title_hi,
            'title_en' => $request->title_en ?? $request->title_hi,
            'slug' => str()->slug($request->title_en ?? $request->title_hi) . '-' . time(),
            'category' => $request->category,
            'excerpt' => $request->excerpt,
            'content_hi' => $request->content_hi,
            'featured_image' => $request->featured_image,
            'author_name' => $request->author_name ?? 'श्री कामदगिरि पीठम्',
            'status' => 'published',
        ]);

        ActivityLog::log('Blog CMS', 'create', 'Created Blog Article: ' . $request->title_hi);
        return back()->with('success', 'Blog article created successfully.');
    }

    public function deleteBlog($id)
    {
        $post = \App\Models\Post::findOrFail($id);
        $post->delete();
        ActivityLog::log('Blog CMS', 'delete', 'Deleted Blog Article #' . $id);
        return back()->with('success', 'Blog article deleted successfully.');
    }

    // --- CAUSES CMS ---
    public function causes()
    {
        $causes = Cause::latest()->get();
        return view('admin.causes', compact('causes'));
    }

    public function storeCause(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:100',
            'image_path' => 'required|string',
        ]);

        Cause::create([
            'title' => $request->title,
            'slug' => str()->slug($request->title) . '-' . time(),
            'description' => $request->description,
            'target_amount' => $request->target_amount,
            'minimum_amount' => 100,
            'image_path' => $request->image_path,
            'status' => 'active',
        ]);

        ActivityLog::log('Causes CMS', 'create', 'Created Cause: ' . $request->title);
        return back()->with('success', 'Donation cause created successfully.');
    }

    public function deleteCause($id)
    {
        $cause = Cause::findOrFail($id);
        $cause->delete();
        ActivityLog::log('Causes CMS', 'delete', 'Deleted Cause #' . $id);
        return back()->with('success', 'Donation cause deleted successfully.');
    }

    // --- GALLERY CMS ---
    public function gallery()
    {
        $items = \App\Models\GalleryItem::latest()->get();
        return view('admin.gallery', compact('items'));
    }

    public function storeGalleryItem(Request $request)
    {
        $request->validate([
            'caption_hi' => 'required|string|max:255',
            'caption_en' => 'required|string|max:255',
            'image_url' => 'required|string',
        ]);

        $gallery = \App\Models\Gallery::firstOrCreate(
            ['slug' => 'main-gallery'],
            [
                'title_hi' => 'मुख्य फोटो गैलरी',
                'title_en' => 'Main Photo Gallery',
                'is_published' => true,
            ]
        );

        \App\Models\GalleryItem::create([
            'gallery_id' => $gallery->id,
            'item_type' => 'image',
            'media_path' => $request->image_url,
            'caption_hi' => $request->caption_hi,
            'caption_en' => $request->caption_en,
        ]);

        ActivityLog::log('Gallery CMS', 'create', 'Uploaded Gallery Photo: ' . $request->caption_hi);
        return back()->with('success', 'Gallery photo uploaded successfully.');
    }

    public function deleteGalleryItem($id)
    {
        $item = \App\Models\GalleryItem::findOrFail($id);
        $item->delete();
        ActivityLog::log('Gallery CMS', 'delete', 'Deleted Gallery Item #' . $id);
        return back()->with('success', 'Gallery photo deleted successfully.');
    }
}
