<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ArticleCategory;
use App\Models\AtithiVishramEnquiry;
use App\Models\Cause;
use App\Models\ContactInquiry;
use App\Models\Destination;
use App\Models\Donation;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Models\HomepageSetting;
use App\Models\LeadershipProfile;
use App\Models\MediaFile;
use App\Models\NavigationMenuItem;
use App\Models\NewsletterSubscriber;
use App\Models\OrganizationSetting;
use App\Models\PageSeoSetting;
use App\Models\PoojaBooking;
use App\Models\PoojaService;
use App\Models\PoojaTimeSlot;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\SevaProject;
use App\Models\TempleBookingEnquiry;
use App\Models\User;
use App\Models\VolunteerApplication;
use App\Services\EmailNotificationService;
use Database\Seeders\NavigationMenuSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

        $postsCount = Post::count();
        $publishedPostsCount = Post::where('status', 'published')->count();
        $draftPostsCount = Post::where('status', 'draft')->count();
        $totalViews = Post::sum('views_count');

        // Services & Bookings Metrics
        $poojaBookingsCount = PoojaBooking::count();
        $poojaPendingCount = PoojaBooking::where('status', 'pending')->count();
        $atithiCount = AtithiVishramEnquiry::count();
        $atithiNewCount = AtithiVishramEnquiry::where('status', 'new')->count();
        $templeBookingsCount = TempleBookingEnquiry::count();
        $templeBookingsNewCount = TempleBookingEnquiry::where('status', 'new')->count();

        $topPosts = Post::orderByDesc('views_count')->take(6)->get();
        $recentDonations = Donation::with(['user', 'cause'])->latest()->take(8)->get();
        $recentPoojaBookings = PoojaBooking::with('service')->latest()->take(8)->get();
        $recentAtithiEnquiries = AtithiVishramEnquiry::latest()->take(8)->get();
        $recentTempleBookings = TempleBookingEnquiry::latest()->take(8)->get();
        $activityLogs = ActivityLog::latest()->take(15)->get();

        return view('admin.dashboard', compact(
            'totalDonations',
            'donationsCount',
            'usersCount',
            'sevasCount',
            'destinationsCount',
            'eventsCount',
            'postsCount',
            'publishedPostsCount',
            'draftPostsCount',
            'totalViews',
            'poojaBookingsCount',
            'poojaPendingCount',
            'atithiCount',
            'atithiNewCount',
            'templeBookingsCount',
            'templeBookingsNewCount',
            'topPosts',
            'recentDonations',
            'recentPoojaBookings',
            'recentAtithiEnquiries',
            'recentTempleBookings',
            'activityLogs'
        ));
    }

    /**
     * Export verified and historical donations as CSV for 80G tax audits & filings.
     */
    public function exportDonationsCsv(Request $request)
    {
        $query = Donation::with(['user', 'cause', 'receipt']);

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('financial_year')) {
            $fy = (string) $request->query('financial_year');
            if (preg_match('/^(\d{4})-(\d{4})$/', $fy, $parts)) {
                $start = $parts[1].'-04-01 00:00:00';
                $end = $parts[2].'-03-31 23:59:59';
                $query->whereBetween('created_at', [$start, $end]);
            }
        }

        $donations = $query->latest('created_at')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="donations_80g_export_'.date('Y-m-d_His').'.csv"',
        ];

        ActivityLog::log(auth()->user()?->name ?? 'Admin', 'export_csv', 'Exported '.$donations->count().' donations for 80G filing.');

        $sanitizeCsvCell = static function (mixed $value): string {
            $str = (string) $value;
            if ($str !== '' && preg_match('/^[=+\-@\t\r]/', $str)) {
                return "'".$str;
            }

            return $str;
        };

        $callback = function () use ($donations, $sanitizeCsvCell) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF"); // UTF-8 BOM for Microsoft Excel

            fputcsv($file, [
                'Receipt Number',
                'Date',
                'Donor Name',
                'Donor Email',
                'Donor Phone',
                'Amount (INR)',
                'Cause / Seva',
                'Donation Type',
                'Payment Gateway',
                'Payment ID',
                'Status',
            ]);

            foreach ($donations as $donation) {
                fputcsv($file, [
                    $sanitizeCsvCell($donation->receipt_number ?: ($donation->receipt?->receipt_number ?: 'N/A')),
                    $sanitizeCsvCell($donation->paid_at ? $donation->paid_at->format('Y-m-d H:i') : $donation->created_at->format('Y-m-d H:i')),
                    $sanitizeCsvCell($donation->donor_name ?: ($donation->user?->name ?: 'Devotee Supporter')),
                    $sanitizeCsvCell($donation->donor_email ?: ($donation->user?->email ?: 'N/A')),
                    $sanitizeCsvCell($donation->donor_phone ?: ($donation->user?->phone ?: 'N/A')),
                    number_format($donation->amount, 2, '.', ''),
                    $sanitizeCsvCell($donation->cause?->title ?: 'General Seva'),
                    $sanitizeCsvCell($donation->donation_type ?: 'General'),
                    $sanitizeCsvCell($donation->payment_gateway ?: 'Razorpay'),
                    $sanitizeCsvCell($donation->gateway_payment_id ?: 'N/A'),
                    $sanitizeCsvCell(ucfirst($donation->status)),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function flushCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            ActivityLog::log('System Admin', 'cache_flush', 'Flushed server cache & compiled view blades.');

            return response()->json(['success' => true, 'message' => 'Server cache and views flushed successfully.']);
        } catch (\Throwable $e) {
            Log::error('Cache flush failed: '.$e->getMessage());

            return response()->json(['success' => false, 'message' => 'Failed to flush server cache.'], 500);
        }
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
            'target_amount' => 'nullable|numeric|min:1|max:999999999.99',
        ], [
            'target_amount.max' => 'लक्ष्य राशि ₹99,99,99,999 से अधिक नहीं हो सकती।',
        ]);

        $causeId = $request->cause_id;
        $targetAmount = $request->filled('target_amount') ? (float) $request->target_amount : 500000.0;

        if (empty($causeId)) {
            $cause = Cause::firstOrCreate(
                ['title' => $request->title_hi],
                [
                    'slug' => str()->slug($request->title_en ?: $request->title_hi).'-'.time(),
                    'description' => $request->summary_hi,
                    'target_amount' => $targetAmount,
                    'minimum_amount' => 51,
                    'image_path' => $request->featured_image,
                    'status' => 'active',
                    'allow_donations_after_target' => true,
                    'is_archived' => false,
                ]
            );
            $causeId = $cause->id;
        }

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
            'cause_id' => $causeId,
            'target_amount' => $targetAmount,
            'is_featured' => $request->boolean('is_featured'),
            'is_published' => $request->boolean('is_published'),
        ]);

        ActivityLog::log('Seva CMS', 'create', 'Created Seva Campaign: '.$request->title_en);

        return back()->with('success', 'Seva campaign created successfully.');
    }

    public function updateSeva(Request $request, $id)
    {
        $seva = SevaProject::findOrFail($id);
        $request->validate([
            'title_hi' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'category' => 'required|string',
            'summary_hi' => 'required|string',
            'description_hi' => 'required|string',
            'featured_image' => 'required|string',
            'target_amount' => 'nullable|numeric|min:1|max:999999999.99',
        ], [
            'target_amount.max' => 'लक्ष्य राशि ₹99,99,99,999 से अधिक नहीं हो सकती।',
        ]);

        $causeId = $request->cause_id ?: $seva->cause_id;
        $targetAmount = $request->filled('target_amount') ? (float) $request->target_amount : ($seva->target_amount ?? 500000.0);

        if (empty($causeId)) {
            $cause = Cause::firstOrCreate(
                ['title' => $request->title_hi],
                [
                    'slug' => str()->slug($request->title_en ?: $request->title_hi).'-'.time(),
                    'description' => $request->summary_hi,
                    'target_amount' => $targetAmount,
                    'minimum_amount' => 51,
                    'image_path' => $request->featured_image,
                    'status' => 'active',
                    'allow_donations_after_target' => true,
                    'is_archived' => false,
                ]
            );
            $causeId = $cause->id;
        } elseif ($cause = Cause::find($causeId)) {
            $cause->update(['target_amount' => $targetAmount]);
        }

        $seva->update([
            'title_hi' => $request->title_hi,
            'title_en' => $request->title_en,
            'slug' => str()->slug($request->title_en),
            'category' => $request->category,
            'summary_hi' => $request->summary_hi,
            'summary_en' => $request->summary_en ?? $request->summary_hi,
            'description_hi' => $request->description_hi,
            'description_en' => $request->description_en ?? $request->description_hi,
            'featured_image' => $request->featured_image,
            'cause_id' => $causeId,
            'target_amount' => $targetAmount,
            'is_featured' => $request->boolean('is_featured'),
            'is_published' => $request->boolean('is_published'),
        ]);

        ActivityLog::log('Seva CMS', 'update', 'Updated Seva Campaign: '.$seva->title_en);

        return back()->with('success', 'Seva campaign updated successfully.');
    }

    public function deleteSeva($id)
    {
        $seva = SevaProject::findOrFail($id);
        $name = $seva->title_en;
        $seva->delete();
        ActivityLog::log('Seva CMS', 'delete', 'Deleted Seva Project: '.$name);

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

        ActivityLog::log('Destinations CMS', 'create', 'Created Sacred Spot: '.$request->name_en);

        return back()->with('success', 'Chitrakoot destination created successfully.');
    }

    public function updateDestination(Request $request, $id)
    {
        $dest = Destination::findOrFail($id);
        $request->validate([
            'name_hi' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'tagline_hi' => 'required|string',
            'description_hi' => 'required|string',
            'featured_image' => 'required|string',
        ]);

        $dest->update([
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

        ActivityLog::log('Destinations CMS', 'update', 'Updated Sacred Spot: '.$dest->name_en);

        return back()->with('success', 'Chitrakoot destination updated successfully.');
    }

    public function deleteDestination($id)
    {
        $dest = Destination::findOrFail($id);
        $name = $dest->name_en;
        $dest->delete();
        ActivityLog::log('Destinations CMS', 'delete', 'Deleted Sacred Spot: '.$name);

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
        if ($request->user()->role !== 'super_admin') {
            abort(403, 'अनधिकृत प्रवेश: केवल मुख्य व्यवस्थापक (Super Admin) ही उपयोक्ता भूमिकाओं में परिवर्तन कर सकते हैं।');
        }

        $user = User::findOrFail($id);
        $request->validate([
            'role' => 'required|in:super_admin,content_admin,membership_admin,donation_admin,media_editor,viewer,donor,member',
            'approval_status' => 'required|in:pending,approved,rejected',
        ]);

        if ((int) $user->id === (int) $request->user()->id && ($request->role !== 'super_admin' || $request->approval_status !== 'approved')) {
            return back()->withErrors(['role' => 'आप अपनी स्वयं की Super Admin भूमिका या स्वीकृति स्थिति को अवनत नहीं कर सकते।']);
        }

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

        $data = $request->only([
            'org_name',
            'site_tagline',
            'meta_description',
            'logo_path',
            'favicon_path',
            'default_og_image',
            'ga4_measurement_id',
            'ga4_custom_script',
            'ga4_report_url',
            'google_site_verification',
            'instagram_url',
            'facebook_url',
            'youtube_url',
            'whatsapp_url',
            'donation_preset_amounts',
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
            'privacy_policy_content',
        ]);

        // Preserve existing secrets if omitted or submitted blank
        if (empty($data['razorpay_key_secret'])) {
            unset($data['razorpay_key_secret']);
        }
        if (empty($data['smtp_password'])) {
            unset($data['smtp_password']);
        }

        $data['is_80g_enabled'] = $request->boolean('is_80g_enabled');

        $request->validate([
            'email' => 'nullable|email|max:180',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
            'instagram_url' => 'nullable|url|max:500',
            'facebook_url' => 'nullable|url|max:500',
            'youtube_url' => 'nullable|url|max:500',
            'whatsapp_url' => 'nullable|url|max:500',
            'ga4_report_url' => 'nullable|url|max:500',
            'logo_file' => 'nullable|file|mimes:jpeg,jpg,png,webp,svg,ico,gif|max:10240',
            'favicon_file' => 'nullable|file|mimes:jpeg,jpg,png,webp,svg,ico,gif|max:5120',
            'default_og_image_file' => 'nullable|file|mimes:jpeg,jpg,png,webp,svg,gif|max:10240',
        ]);

        $allowedExtensions = ['jpeg', 'jpg', 'png', 'webp', 'svg', 'ico', 'gif'];
        foreach (['logo_file', 'favicon_file', 'default_og_image_file'] as $field) {
            if ($request->hasFile($field)) {
                $ext = strtolower($request->file($field)->getClientOriginalExtension());
                if (! in_array($ext, $allowedExtensions)) {
                    return back()->withErrors([$field => "The {$field} must be a valid image file (PNG, WebP, JPG, SVG, or ICO)."]);
                }
            }
        }

        $destinationDir = public_path('uploads');
        if (! File::isDirectory($destinationDir)) {
            File::makeDirectory($destinationDir, 0755, true, true);
        }

        if ($request->hasFile('logo_file')) {
            $file = $request->file('logo_file');
            $ext = strtolower($file->getClientOriginalExtension()) ?: 'png';
            $filename = 'site-logo-'.time().'.'.$ext;
            $file->move($destinationDir, $filename);
            if ($ext === 'svg') {
                $this->sanitizeSvgFile($destinationDir.'/'.$filename);
            }
            $data['logo_path'] = '/uploads/'.$filename;

            MediaFile::create([
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'file_path' => '/uploads/'.$filename,
                'file_type' => 'image',
                'mime_type' => $file->getClientMimeType() ?: 'image/png',
                'file_size' => filesize($destinationDir.'/'.$filename),
            ]);
        }

        if ($request->hasFile('favicon_file')) {
            $file = $request->file('favicon_file');
            $ext = strtolower($file->getClientOriginalExtension()) ?: 'ico';
            $filename = 'site-favicon-'.time().'.'.$ext;
            $file->move($destinationDir, $filename);
            if ($ext === 'svg') {
                $this->sanitizeSvgFile($destinationDir.'/'.$filename);
            }
            $data['favicon_path'] = '/uploads/'.$filename;

            MediaFile::create([
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'file_path' => '/uploads/'.$filename,
                'file_type' => 'image',
                'mime_type' => $file->getClientMimeType() ?: 'image/x-icon',
                'file_size' => filesize($destinationDir.'/'.$filename),
            ]);
        }

        if ($request->hasFile('default_og_image_file')) {
            $file = $request->file('default_og_image_file');
            $ext = strtolower($file->getClientOriginalExtension()) ?: 'jpg';
            $filename = 'og-image-'.time().'.'.$ext;
            $file->move($destinationDir, $filename);
            if ($ext === 'svg') {
                $this->sanitizeSvgFile($destinationDir.'/'.$filename);
            }
            $data['default_og_image'] = '/uploads/'.$filename;

            MediaFile::create([
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'file_path' => '/uploads/'.$filename,
                'file_type' => 'image',
                'mime_type' => $file->getClientMimeType() ?: 'image/jpeg',
                'file_size' => filesize($destinationDir.'/'.$filename),
            ]);
        }

        $settings->update($data);

        ActivityLog::log('Settings CMS', 'update', 'Updated organization details, branding, SEO, and gateway credentials.');

        return back()->with('success', 'Site settings, logo, SEO, and gateway credentials updated successfully.');
    }

    // --- BLOGS CMS ---
    public function blogs(Request $request)
    {
        $query = Post::latest();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title_hi', 'like', "%{$q}%")
                    ->orWhere('title_en', 'like', "%{$q}%")
                    ->orWhere('tags', 'like', "%{$q}%");
            });
        }

        $posts = $query->paginate(10);
        $totalCount = Post::count();
        $publishedCount = Post::where('status', 'published')->count();
        $draftsCount = Post::where('status', 'draft')->count();
        $categories = Post::distinct()->pluck('category')->filter();

        return view('admin.blogs', compact('posts', 'totalCount', 'publishedCount', 'draftsCount', 'categories'));
    }

    public function createBlog()
    {
        $post = new Post([
            'status' => 'published',
            'author_name' => 'श्री कामदगिरि पीठम्',
            'category' => 'Spiritual',
        ]);
        $categories = ArticleCategory::where('is_active', true)->orderBy('sort_order')->get();
        if ($categories->isEmpty()) {
            $categories = Post::distinct()->pluck('category')->filter();
        }
        $causes = Cause::all();
        $galleryItems = GalleryItem::latest()->take(20)->get();
        $mediaFiles = MediaFile::where('file_type', 'image')->latest()->take(30)->get();

        return view('admin.blog-editor', compact('post', 'categories', 'causes', 'galleryItems', 'mediaFiles'));
    }

    public function editBlog($id)
    {
        $post = Post::findOrFail($id);
        $categories = ArticleCategory::where('is_active', true)->orderBy('sort_order')->get();
        if ($categories->isEmpty()) {
            $categories = Post::distinct()->pluck('category')->filter();
        }
        $causes = Cause::all();
        $galleryItems = GalleryItem::latest()->take(20)->get();
        $mediaFiles = MediaFile::where('file_type', 'image')->latest()->take(30)->get();

        return view('admin.blog-editor', compact('post', 'categories', 'causes', 'galleryItems', 'mediaFiles'));
    }

    public function storeBlog(Request $request)
    {
        if ($request->filled('post_id') && intval($request->post_id) > 0) {
            return $this->updateBlog($request, intval($request->post_id));
        }

        $request->validate([
            'title_hi' => 'required|string|max:255',
            'content_hi' => 'required|string',
        ]);

        $slugBase = $request->filled('slug') ? $request->slug : ($request->title_en ?: $request->title_hi);
        $slug = Str::slug($slugBase);
        if (empty($slug)) {
            $slug = 'article-'.time();
        }
        if (Post::where('slug', $slug)->exists()) {
            $slug .= '-'.time();
        }

        $category = $request->category_new ?: ($request->category ?: 'Spiritual');
        if ($request->filled('category_new')) {
            $catSlug = Str::slug($request->category_new) ?: 'cat-'.time();
            ArticleCategory::firstOrCreate(
                ['slug' => $catSlug],
                [
                    'name_hi' => $request->category_new,
                    'name_en' => $request->category_new,
                    'description' => 'Category auto-created from article editor',
                    'color' => '#8f1719',
                    'sort_order' => (ArticleCategory::max('sort_order') ?: 0) + 1,
                    'is_active' => true,
                ]
            );
            $category = $catSlug;
        }

        $post = Post::create([
            'title_hi' => $request->title_hi,
            'title_en' => $request->title_en ?? $request->title_hi,
            'slug' => $slug,
            'category' => $category,
            'excerpt' => $request->excerpt,
            'content_hi' => $request->content_hi,
            'content_en' => $request->content_en ?? $request->content_hi,
            'featured_image' => $request->featured_image,
            'featured_image_caption' => $request->featured_image_caption,
            'featured_image_alt' => $request->featured_image_alt,
            'video_url' => $request->video_url,
            'tags' => $request->tags,
            'focus_keyword' => $request->focus_keyword,
            'seo_score' => intval($request->seo_score ?? 0),
            'is_hero' => $request->boolean('is_hero'),
            'author_name' => $request->author_name ?? 'श्री कामदगिरि पीठम्',
            'status' => $request->status ?? 'published',
            'published_at' => $request->status === 'published' ? now() : null,
        ]);

        ActivityLog::log('Blog CMS', 'create', 'Created Blog Article: '.$post->title_hi);

        return redirect()->route('admin.blogs')->with('success', 'Blog article published successfully.');
    }

    public function updateBlog(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $request->validate([
            'title_hi' => 'required|string|max:255',
            'content_hi' => 'required|string',
        ]);

        $slug = $post->slug;
        if ($request->filled('slug') && $request->slug !== $post->slug) {
            $slug = Str::slug($request->slug);
            if (Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                $slug .= '-'.time();
            }
        }

        $category = $request->category_new ?: ($request->category ?: $post->category);
        if ($request->filled('category_new')) {
            $catSlug = Str::slug($request->category_new) ?: 'cat-'.time();
            ArticleCategory::firstOrCreate(
                ['slug' => $catSlug],
                [
                    'name_hi' => $request->category_new,
                    'name_en' => $request->category_new,
                    'description' => 'Category auto-created from article editor',
                    'color' => '#8f1719',
                    'sort_order' => (ArticleCategory::max('sort_order') ?: 0) + 1,
                    'is_active' => true,
                ]
            );
            $category = $catSlug;
        }

        $post->update([
            'title_hi' => $request->title_hi,
            'title_en' => $request->title_en ?? $request->title_hi,
            'slug' => $slug,
            'category' => $category,
            'excerpt' => $request->excerpt,
            'content_hi' => $request->content_hi,
            'content_en' => $request->content_en ?? $request->content_hi,
            'featured_image' => $request->featured_image,
            'featured_image_caption' => $request->featured_image_caption,
            'featured_image_alt' => $request->featured_image_alt,
            'video_url' => $request->video_url,
            'tags' => $request->tags,
            'focus_keyword' => $request->focus_keyword,
            'seo_score' => intval($request->seo_score ?? $post->seo_score),
            'is_hero' => $request->boolean('is_hero'),
            'author_name' => $request->author_name ?? $post->author_name,
            'status' => $request->status ?? $post->status,
            'published_at' => ($request->status === 'published' && ! $post->published_at) ? now() : $post->published_at,
        ]);

        ActivityLog::log('Blog CMS', 'update', 'Updated Blog Article #'.$post->id.' ('.$post->title_hi.')');

        return redirect()->route('admin.blogs')->with('success', 'Article updated successfully.');
    }

    public function autosaveBlog(Request $request)
    {
        $postId = intval($request->post_id ?? 0);
        $title = trim($request->title_hi ?? '');
        $content = trim($request->content_hi ?? '');
        $excerpt = trim($request->excerpt ?? '');

        if (empty($title) && empty($content)) {
            return response()->json(['success' => false, 'message' => 'Empty payload']);
        }

        if ($postId > 0) {
            $post = Post::find($postId);
            if ($post) {
                $post->update([
                    'title_hi' => $title ?: $post->title_hi,
                    'content_hi' => $content ?: $post->content_hi,
                    'excerpt' => $excerpt ?: $post->excerpt,
                    'seo_score' => intval($request->seo_score ?? $post->seo_score),
                    'focus_keyword' => $request->focus_keyword ?? $post->focus_keyword,
                    'category' => $request->category ?: $post->category,
                    'featured_image' => $request->featured_image ?: $post->featured_image,
                ]);
            }
        } else {
            $slug = 'draft-'.time().'-'.rand(100, 999);
            $post = Post::create([
                'title_hi' => $title ?: 'अप्रकाशित आलेख (Untitled Draft)',
                'title_en' => 'Draft '.date('d M Y H:i'),
                'slug' => $slug,
                'category' => $request->category ?: 'Spiritual',
                'excerpt' => $excerpt,
                'content_hi' => $content ?: '<p>प्रारूप विवरण...</p>',
                'featured_image' => $request->featured_image,
                'author_name' => auth()->user()?->name ?? 'श्री कामदगिरि पीठम्',
                'status' => 'draft',
                'seo_score' => intval($request->seo_score ?? 0),
                'focus_keyword' => $request->focus_keyword,
            ]);
            $postId = $post->id;
        }

        return response()->json([
            'success' => true,
            'post_id' => $postId,
            'saved_at' => now()->format('H:i:s'),
        ]);
    }

    public function deleteBlog($id)
    {
        $post = Post::findOrFail($id);
        $title = $post->title_hi;
        $post->delete();
        ActivityLog::log('Blog CMS', 'delete', 'Deleted Blog Article: '.$title);

        return back()->with('success', 'Blog article deleted successfully.');
    }

    // --- CATEGORIES & TOPICS (TAGS) CMS ---
    public function categories(Request $request)
    {
        if (ArticleCategory::count() === 0) {
            $defaultCategories = [
                [
                    'name_hi' => 'आध्यात्मिक एवं दर्शन',
                    'name_en' => 'Spiritual & Philosophy',
                    'slug' => 'Spiritual',
                    'description' => 'चित्रकूट के पवित्र संत विचार, रामकथा दर्शन एवं सनातन संस्कृति',
                    'color' => '#8f1719',
                    'sort_order' => 1,
                    'is_active' => true,
                ],
                [
                    'name_hi' => 'गौ सेवा एवं संरक्षण',
                    'name_en' => 'Gaushala & Cow Care',
                    'slug' => 'Gaushala',
                    'description' => 'कामदगिरि गौशाला, नंदी सेवा, जैविक कृषि एवं पंचगव्य चिकित्सा',
                    'color' => '#086c60',
                    'sort_order' => 2,
                    'is_active' => true,
                ],
                [
                    'name_hi' => 'गुरुकुल एवं शिक्षा',
                    'name_en' => 'Education & Gurukul',
                    'slug' => 'Education',
                    'description' => 'संस्कृत गुरुकुल, वेद पाठ, निशुल्क विद्यादान एवं छात्र कल्याण',
                    'color' => '#b87333',
                    'sort_order' => 3,
                    'is_active' => true,
                ],
                [
                    'name_hi' => 'चित्रकूट तीर्थ एवं परिक्रमा',
                    'name_en' => 'Pilgrimage & Holy Sites',
                    'slug' => 'Pilgrimage',
                    'description' => 'कामदगिरि परिक्रमा, रामघाट, मंदाकिनी आरती, गुप्त गोदावरी एवं प्रमुख तीर्थ',
                    'color' => '#f4a900',
                    'sort_order' => 4,
                    'is_active' => true,
                ],
                [
                    'name_hi' => 'संस्कृति एवं विरासत',
                    'name_en' => 'Heritage & Culture',
                    'slug' => 'Heritage',
                    'description' => 'धार्मिक उत्सव, रामलीला, मेला एवं चित्रकूट का ऐतिहासिक वैभव',
                    'color' => '#593a28',
                    'sort_order' => 5,
                    'is_active' => true,
                ],
            ];

            foreach ($defaultCategories as $def) {
                ArticleCategory::create($def);
            }
        }

        $categories = ArticleCategory::orderBy('sort_order')->orderBy('name_en')->get();

        $rawTags = Post::whereNotNull('tags')->pluck('tags')->toArray();
        $topicCounts = [];
        foreach ($rawTags as $tagLine) {
            $split = preg_split('/[\r\n,]+/', $tagLine);
            foreach ($split as $t) {
                $trimmed = trim($t);
                if (! empty($trimmed)) {
                    $topicCounts[$trimmed] = ($topicCounts[$trimmed] ?? 0) + 1;
                }
            }
        }
        arsort($topicCounts);

        $selectedCategory = $request->query('category');
        $selectedTopic = $request->query('topic');

        $articlesQuery = Post::latest();
        if ($selectedCategory) {
            $articlesQuery->where('category', $selectedCategory);
        }
        if ($selectedTopic) {
            $articlesQuery->where('tags', 'like', "%{$selectedTopic}%");
        }
        $filteredArticles = ($selectedCategory || $selectedTopic) ? $articlesQuery->paginate(15) : null;

        $totalCategories = $categories->count();
        $totalArticlesCategorized = Post::whereNotNull('category')->where('category', '!=', '')->count();
        $totalTopics = count($topicCounts);
        $activeCategories = $categories->where('is_active', true)->count();

        return view('admin.categories', compact(
            'categories',
            'topicCounts',
            'filteredArticles',
            'selectedCategory',
            'selectedTopic',
            'totalCategories',
            'totalArticlesCategorized',
            'totalTopics',
            'activeCategories'
        ));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name_hi' => 'required|string|max:150',
            'name_en' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:article_categories,slug',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:30',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        $slug = ! empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name_en']);
        if (ArticleCategory::where('slug', $slug)->exists()) {
            $slug .= '-'.time();
        }

        $category = ArticleCategory::create([
            'name_hi' => $validated['name_hi'],
            'name_en' => $validated['name_en'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'color' => $validated['color'] ?? '#8f1719',
            'sort_order' => $validated['sort_order'] ?? ((ArticleCategory::max('sort_order') ?: 0) + 1),
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        ActivityLog::log('Category CMS', 'create_category', "Created article category: {$category->name_en}");

        return back()->with('success', "Category '{$category->name_en}' created successfully.");
    }

    public function updateCategory(Request $request, $id)
    {
        $category = ArticleCategory::findOrFail($id);

        $validated = $request->validate([
            'name_hi' => 'required|string|max:150',
            'name_en' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:article_categories,slug,'.$category->id,
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:30',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        $oldSlug = $category->slug;
        $newSlug = ! empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name_en']);

        $category->update([
            'name_hi' => $validated['name_hi'],
            'name_en' => $validated['name_en'],
            'slug' => $newSlug,
            'description' => $validated['description'] ?? null,
            'color' => $validated['color'] ?? '#8f1719',
            'sort_order' => $validated['sort_order'] ?? $category->sort_order,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        if ($oldSlug !== $newSlug) {
            Post::where('category', $oldSlug)->update(['category' => $newSlug]);
        }

        ActivityLog::log('Category CMS', 'update_category', "Updated article category: {$category->name_en}");

        return back()->with('success', "Category '{$category->name_en}' updated successfully.");
    }

    public function deleteCategory(Request $request, $id)
    {
        $category = ArticleCategory::findOrFail($id);
        $name = $category->name_en;
        $slug = $category->slug;

        $reassignTo = $request->input('reassign_to');
        if (! empty($reassignTo) && $reassignTo !== $slug) {
            Post::where('category', $slug)->update(['category' => $reassignTo]);
        } else {
            Post::where('category', $slug)->update(['category' => 'Spiritual']);
        }

        $category->delete();

        ActivityLog::log('Category CMS', 'delete_category', "Deleted article category: {$name}");

        return back()->with('success', "Category '{$name}' deleted successfully. Associated articles reassigned.");
    }

    public function renameTopic(Request $request)
    {
        $validated = $request->validate([
            'old_tag' => 'required|string',
            'new_tag' => 'required|string',
        ]);

        $old = trim($validated['old_tag']);
        $new = trim($validated['new_tag']);

        if ($old !== $new) {
            $posts = Post::where('tags', 'like', "%{$old}%")->get();
            $count = 0;
            foreach ($posts as $post) {
                $tags = array_map('trim', explode(',', $post->tags));
                $modified = false;
                foreach ($tags as $idx => $t) {
                    if (strcasecmp($t, $old) === 0) {
                        $tags[$idx] = $new;
                        $modified = true;
                    }
                }
                if ($modified) {
                    $post->tags = implode(', ', array_unique($tags));
                    $post->save();
                    $count++;
                }
            }

            ActivityLog::log('Topic CMS', 'rename_tag', "Renamed topic '{$old}' to '{$new}' across {$count} articles.");

            return back()->with('success', "Topic '{$old}' renamed to '{$new}' across {$count} articles.");
        }

        return back()->with('info', 'No changes made.');
    }

    public function deleteTopic(Request $request)
    {
        $validated = $request->validate([
            'tag' => 'required|string',
        ]);

        $tag = trim($validated['tag']);
        $posts = Post::where('tags', 'like', "%{$tag}%")->get();
        $count = 0;
        foreach ($posts as $post) {
            $tags = array_map('trim', explode(',', $post->tags));
            $newTags = array_filter($tags, fn ($t) => strcasecmp($t, $tag) !== 0);
            if (count($tags) !== count($newTags)) {
                $post->tags = count($newTags) > 0 ? implode(', ', $newTags) : null;
                $post->save();
                $count++;
            }
        }

        ActivityLog::log('Topic CMS', 'delete_tag', "Deleted topic tag '{$tag}' from {$count} articles.");

        return back()->with('success', "Topic '{$tag}' removed from {$count} articles.");
    }

    public function comments(Request $request)
    {
        $status = $request->query('status', 'all');
        $query = PostComment::with('post', 'user')->latest();

        if ($status === 'approved') {
            $query->where('is_approved', true);
        } elseif ($status === 'pending') {
            $query->where('is_approved', false);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('comment', 'like', "%{$q}%");
            });
        }

        $comments = $query->paginate(20);
        $totalComments = PostComment::count();
        $approvedComments = PostComment::where('is_approved', true)->count();
        $pendingComments = PostComment::where('is_approved', false)->count();

        return view('admin.comments', compact('comments', 'status', 'totalComments', 'approvedComments', 'pendingComments'));
    }

    public function approveComment($id)
    {
        $comment = PostComment::findOrFail($id);
        $comment->update(['is_approved' => true]);

        EmailNotificationService::sendCommentApproved($comment);
        ActivityLog::log('Comments CMS', 'approve', "Approved comment #{$id} by {$comment->name}");

        return back()->with('success', 'टिप्पणी स्वीकृत कर दी गई है। (Comment approved successfully)');
    }

    public function rejectComment($id)
    {
        $comment = PostComment::findOrFail($id);
        $comment->update(['is_approved' => false]);
        ActivityLog::log('Comments CMS', 'reject', "Unapproved comment #{$id} by {$comment->name}");

        return back()->with('success', 'टिप्पणी अस्वीकृत कर दी गई है। (Comment unapproved successfully)');
    }

    public function deleteComment($id)
    {
        $comment = PostComment::findOrFail($id);
        $comment->delete();
        ActivityLog::log('Comments CMS', 'delete', "Deleted comment #{$id}");

        return back()->with('success', 'टिप्पणी हटा दी गई है। (Comment deleted successfully)');
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
            'target_amount' => 'required|numeric|min:100|max:999999999.99',
            'image_path' => 'required|string',
        ], [
            'target_amount.max' => 'लक्ष्य राशि ₹99,99,99,999 से अधिक नहीं हो सकती।',
        ]);

        Cause::create([
            'title' => $request->title,
            'slug' => str()->slug($request->title).'-'.time(),
            'description' => $request->description,
            'target_amount' => $request->target_amount,
            'minimum_amount' => 100,
            'image_path' => $request->image_path,
            'status' => 'active',
        ]);

        ActivityLog::log('Causes CMS', 'create', 'Created Cause: '.$request->title);

        return back()->with('success', 'Donation cause created successfully.');
    }

    public function updateCause(Request $request, $id)
    {
        $cause = Cause::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:100|max:999999999.99',
            'image_path' => 'required|string',
        ], [
            'target_amount.max' => 'लक्ष्य राशि ₹99,99,99,999 से अधिक नहीं हो सकती।',
        ]);

        $cause->update([
            'title' => $request->title,
            'description' => $request->description,
            'target_amount' => $request->target_amount,
            'image_path' => $request->image_path,
            'status' => $request->input('status', 'active'),
        ]);

        ActivityLog::log('Causes CMS', 'update', 'Updated Cause: '.$cause->title);

        return back()->with('success', 'Donation cause updated successfully.');
    }

    public function deleteCause($id)
    {
        $cause = Cause::findOrFail($id);
        $cause->delete();
        ActivityLog::log('Causes CMS', 'delete', 'Deleted Cause #'.$id);

        return back()->with('success', 'Donation cause deleted successfully.');
    }

    // --- GALLERY CMS ---
    public function gallery()
    {
        $items = GalleryItem::latest()->get();

        return view('admin.gallery', compact('items'));
    }

    public function storeGalleryItem(Request $request)
    {
        $request->validate([
            'caption_hi' => 'required|string|max:255',
            'caption_en' => 'required|string|max:255',
            'image_url' => 'required|string',
        ]);

        $gallery = Gallery::firstOrCreate(
            ['slug' => 'main-gallery'],
            [
                'title_hi' => 'मुख्य फोटो गैलरी',
                'title_en' => 'Main Photo Gallery',
                'is_published' => true,
            ]
        );

        GalleryItem::create([
            'gallery_id' => $gallery->id,
            'item_type' => 'image',
            'media_path' => $request->image_url,
            'caption_hi' => $request->caption_hi,
            'caption_en' => $request->caption_en,
        ]);

        ActivityLog::log('Gallery CMS', 'create', 'Uploaded Gallery Photo: '.$request->caption_hi);

        return back()->with('success', 'Gallery photo uploaded successfully.');
    }

    public function deleteGalleryItem($id)
    {
        $item = GalleryItem::findOrFail($id);
        $item->delete();
        ActivityLog::log('Gallery CMS', 'delete', 'Deleted Gallery Item #'.$id);

        return back()->with('success', 'Gallery photo deleted successfully.');
    }

    // --- HOMEPAGE LAYOUT & COMPONENTS EDITOR (SCREENSHOT 2) ---
    public function homepageLayout()
    {
        $settings = HomepageSetting::getAll();
        $sevasCount = SevaProject::count();
        $destinationsCount = Destination::count();
        $eventsCount = Event::count();
        $causesCount = Cause::count();
        $allSevas = SevaProject::orderBy('title_hi')->get();
        $allDestinations = Destination::orderBy('name_hi')->get();
        $allCauses = Cause::orderBy('title')->get();
        $allPoojas = PoojaService::orderBy('title_hi')->get();
        $galleryItems = MediaFile::where('file_type', 'image')->latest()->take(24)->get();
        $videoItems = MediaFile::where('file_type', 'video')->latest()->take(10)->get();
        $upcomingEvents = Event::upcoming()->get();
        $allEvents = Event::orderBy('event_date', 'asc')->get();

        return view('admin.homepage-layout', compact(
            'settings',
            'sevasCount',
            'destinationsCount',
            'eventsCount',
            'causesCount',
            'allSevas',
            'allDestinations',
            'allCauses',
            'allPoojas',
            'galleryItems',
            'videoItems',
            'upcomingEvents',
            'allEvents'
        ));
    }

    public function updateHomepageLayout(Request $request)
    {
        // Enforce strict file validation and size boundaries to prevent arbitrary file upload / RCE
        $request->validate([
            'hero_video_file' => 'nullable|file|mimes:mp4,webm,mov,ogg,mkv|max:102400',
            'hero_bell_audio_file' => 'nullable|file|mimes:mp3,wav,ogg,m4a,aac|max:20480',
            'hero_photo_file' => 'nullable|file|mimes:jpeg,jpg,png,webp,svg|max:10240',
            'about_photo_1_file' => 'nullable|file|mimes:jpeg,jpg,png,webp,svg|max:10240',
            'about_photo_2_file' => 'nullable|file|mimes:jpeg,jpg,png,webp,svg|max:10240',
            'donation_image_file' => 'nullable|file|mimes:jpeg,jpg,png,webp,svg|max:10240',
            'pooja_banner_file' => 'nullable|file|mimes:jpeg,jpg,png,webp,svg|max:10240',
            'custom_section_image_file' => 'nullable|file|mimes:jpeg,jpg,png,webp,svg|max:10240',
        ]);

        $destinationDir = public_path('uploads');
        if (! File::isDirectory($destinationDir)) {
            File::makeDirectory($destinationDir, 0755, true, true);
        }

        // Handle direct video file upload if provided
        if ($request->hasFile('hero_video_file')) {
            $file = $request->file('hero_video_file');
            $ext = strtolower($file->guessExtension() ?: $file->getClientOriginalExtension());
            $allowedVideoExts = ['mp4', 'webm', 'mov', 'ogg', 'mkv'];
            if (! in_array($ext, $allowedVideoExts, true)) {
                return back()->withErrors(['hero_video_file' => 'Invalid video format. Allowed: mp4, webm, mov, ogg, mkv.']);
            }

            $filename = 'hero-video-'.time().'.'.$ext;
            $file->move($destinationDir, $filename);
            $videoUrl = '/uploads/'.$filename;
            HomepageSetting::set('hero_video_url', $videoUrl);

            MediaFile::create([
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $videoUrl,
                'file_type' => 'video',
                'mime_type' => $file->getClientMimeType() ?: 'video/mp4',
                'file_size' => filesize($destinationDir.'/'.$filename),
            ]);
        }

        // Handle direct bell audio chime file upload if provided (.mp3, .wav, .ogg, etc.)
        if ($request->hasFile('hero_bell_audio_file')) {
            $file = $request->file('hero_bell_audio_file');
            $ext = strtolower($file->guessExtension() ?: $file->getClientOriginalExtension());
            $allowedAudioExts = ['mp3', 'wav', 'ogg', 'm4a', 'aac'];
            if (! in_array($ext, $allowedAudioExts, true)) {
                return back()->withErrors(['hero_bell_audio_file' => 'Invalid audio format. Allowed: mp3, wav, ogg, m4a, aac.']);
            }

            $filename = 'temple-bell-'.time().'.'.$ext;
            $file->move($destinationDir, $filename);
            $audioUrl = '/uploads/'.$filename;
            HomepageSetting::set('hero_bell_audio_url', $audioUrl);

            MediaFile::create([
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $audioUrl,
                'file_type' => 'audio',
                'mime_type' => $file->getClientMimeType() ?: 'audio/mpeg',
                'file_size' => filesize($destinationDir.'/'.$filename),
            ]);
        }

        // Handle image / media uploads
        $imageUploads = [
            'hero_photo_file' => 'hero_photo_url',
            'about_photo_1_file' => 'about_photo_1_url',
            'about_photo_2_file' => 'about_photo_2_url',
            'env_section_image_file' => 'env_section_image_url',
            'donation_image_file' => 'donation_image_url',
            'pooja_banner_file' => 'pooja_banner_url',
            'custom_section_image_file' => 'custom_section_image_url',
        ];

        $allowedImageExts = ['jpeg', 'jpg', 'png', 'webp', 'svg'];

        foreach ($imageUploads as $fileKey => $settingKey) {
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                $ext = strtolower($file->guessExtension() ?: $file->getClientOriginalExtension());
                if (! in_array($ext, $allowedImageExts, true)) {
                    return back()->withErrors([$fileKey => "The {$fileKey} must be a valid image file (PNG, WebP, JPG, or SVG)."]);
                }

                $filename = str_replace('_file', '', $fileKey).'-'.time().'.'.$ext;
                $file->move($destinationDir, $filename);
                if ($ext === 'svg') {
                    $this->sanitizeSvgFile($destinationDir.'/'.$filename);
                }
                $url = '/uploads/'.$filename;
                HomepageSetting::set($settingKey, $url);

                MediaFile::create([
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'file_path' => $url,
                    'file_type' => 'image',
                    'mime_type' => $file->getClientMimeType() ?: 'image/jpeg',
                    'file_size' => filesize($destinationDir.'/'.$filename),
                ]);
            }
        }

        // Section Enable/Disable Toggles (if unchecked in form, set to '0')
        $toggleFields = [
            'section_hero_enabled',
            'section_aarti_enabled',
            'section_about_enabled',
            'section_env_enabled',
            'section_missions_enabled',
            'section_donation_enabled',
            'section_pooja_enabled',
            'section_places_enabled',
            'section_events_enabled',
            'section_custom_enabled',
            'aarti_show_pooja_packages',
            'volunteer_registration_enabled',
        ];

        if ($request->has('is_layout_form')) {
            foreach ($toggleFields as $toggle) {
                HomepageSetting::set($toggle, $request->has($toggle) ? '1' : '0');
            }
        } else {
            foreach ($toggleFields as $toggle) {
                if ($request->has($toggle)) {
                    HomepageSetting::set($toggle, $request->boolean($toggle) ? '1' : '0');
                }
            }
        }

        // Save all other submitted settings
        $fields = [
            // Hero Section
            'hero_eyebrow',
            'hero_headline',
            'hero_subtitle',
            'hero_story',
            'hero_bg_type',
            'hero_overlay_dim',
            'hero_texture_style',
            'hero_video_url',
            'hero_photo_url',
            'hero_primary_btn_text',
            'hero_primary_btn_link',
            'hero_secondary_btn_text',
            'hero_secondary_btn_link',
            'hero_third_btn_text',
            'hero_third_btn_link',
            'bell_default_count',
            'hero_bell_audio_url',

            // Aarti & Hawan Timings
            'aarti_strip_title',
            'aarti_strip_subtitle',
            'aarti_1_name',
            'aarti_1_time',
            'aarti_1_desc',
            'aarti_2_name',
            'aarti_2_time',
            'aarti_2_desc',
            'aarti_3_name',
            'aarti_3_time',
            'aarti_3_desc',
            'aarti_4_name',
            'aarti_4_time',
            'aarti_4_desc',
            'aarti_5_name',
            'aarti_5_time',
            'aarti_5_desc',
            'aarti_6_name',
            'aarti_6_time',
            'aarti_6_desc',
            'aarti_hawan_title',
            'aarti_hawan_time',
            'aarti_hawan_place',
            'aarti_hawan_desc',

            // About Deity & Heritage
            'trust_eyebrow',
            'trust_tagline',
            'trust_heading',
            'trust_lead',
            'trust_desc',
            'trust_p2',
            'about_photo_1_url',
            'about_photo_1_caption',
            'about_photo_2_url',
            'about_photo_2_caption',
            'about_btn_text',
            'about_btn_link',
            'stat_years',
            'stat_years_title',
            'stat_years_sub',
            'stat_students',
            'stat_students_title',
            'stat_students_sub',
            'stat_cows',
            'stat_cows_title',
            'stat_cows_sub',
            'stat_trees',
            'stat_trees_title',
            'stat_trees_sub',

            // Save the Environment Section (Image-Based)
            'section_env_enabled',
            'env_section_badge',
            'env_section_title',
            'env_section_subtitle',
            'env_section_desc',
            'env_section_image_url',
            'env_pillar_1',
            'env_pillar_2',
            'env_pillar_3',
            'env_btn_text',
            'env_btn_link',
            'email_newsletter_title',
            'email_newsletter_desc',

            // Core Dharma Missions
            'seva_section_eyebrow',
            'seva_section_title',
            'seva_section_subtitle',
            'mission_1_title',
            'mission_1_tag',
            'mission_1_desc',
            'mission_1_link',
            'mission_2_title',
            'mission_2_tag',
            'mission_2_desc',
            'mission_2_link',
            'mission_3_title',
            'mission_3_tag',
            'mission_3_desc',
            'mission_3_link',
            'mission_4_title',
            'mission_4_tag',
            'mission_4_desc',
            'mission_4_link',

            // Sacred Contribution / Donation
            'donation_eyebrow',
            'donation_heading',
            'donation_desc',
            'donation_image_url',
            'donation_badge_1',
            'donation_badge_2',
            'donation_btn_text',
            'donation_btn_link',
            'donation_preset_1',
            'donation_preset_2',
            'donation_preset_3',
            'donation_preset_4',

            // Online Pooja & Sacred Sankalp Sanctuary
            'pooja_section_eyebrow',
            'pooja_section_tagline',
            'pooja_section_title',
            'pooja_section_desc',
            'pooja_banner_url',
            'process_ribbon_heading',
            'process_step_1_title',
            'process_step_1_desc',
            'process_step_2_title',
            'process_step_2_desc',
            'process_step_3_title',
            'process_step_3_desc',

            // Places
            'places_section_eyebrow',
            'places_section_title',
            'places_section_subtitle',

            // Events & Broadcast
            'events_section_eyebrow',
            'events_section_title',
            'events_section_subtitle',
            'whatsapp_broadcast_title',
            'whatsapp_broadcast_desc',
            'whatsapp_broadcast_link',

            // Custom Repeatable Section
            'custom_section_eyebrow',
            'custom_section_title',
            'custom_section_desc',
            'custom_section_image_url',
            'custom_section_badge',
            'custom_section_btn_text',
            'custom_section_btn_link',
            'custom_section_highlight_1',
            'custom_section_highlight_2',
            'custom_section_highlight_3',

            // Pooja & Hawan Offerings Section
            'pooja_section_eyebrow',
            'pooja_section_title',
            'pooja_section_subtitle',
            'pooja_cta_text',
            'section_pooja_enabled',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                HomepageSetting::set($field, $request->input($field));
            }
        }

        // Granular card selection lists (admin picks exactly which cards show on homepage)
        if ($request->has('is_layout_form')) {
            HomepageSetting::set('selected_seva_ids', json_encode(array_map('intval', (array) $request->input('selected_seva_ids', []))));
            HomepageSetting::set('selected_destination_ids', json_encode(array_map('intval', (array) $request->input('selected_destination_ids', []))));
            HomepageSetting::set('selected_cause_ids', json_encode(array_map('intval', (array) $request->input('selected_cause_ids', []))));
            HomepageSetting::set('selected_pooja_ids', json_encode(array_map('intval', (array) $request->input('selected_pooja_ids', []))));
        } else {
            if ($request->has('selected_seva_ids')) {
                HomepageSetting::set('selected_seva_ids', json_encode(array_map('intval', (array) $request->input('selected_seva_ids', []))));
            }
            if ($request->has('selected_destination_ids')) {
                HomepageSetting::set('selected_destination_ids', json_encode(array_map('intval', (array) $request->input('selected_destination_ids', []))));
            }
            if ($request->has('selected_cause_ids')) {
                HomepageSetting::set('selected_cause_ids', json_encode(array_map('intval', (array) $request->input('selected_cause_ids', []))));
            }
            if ($request->has('selected_pooja_ids')) {
                HomepageSetting::set('selected_pooja_ids', json_encode(array_map('intval', (array) $request->input('selected_pooja_ids', []))));
            }
        }

        // Dynamic Aarti Time Slots Repeater (Normal Days)
        if ($request->has('aarti_slots') && is_array($request->input('aarti_slots'))) {
            $slots = [];
            foreach ($request->input('aarti_slots') as $slot) {
                $name = trim($slot['name'] ?? '');
                $time = trim($slot['time'] ?? '');
                if ($name !== '' || $time !== '') {
                    $slots[] = [
                        'name' => $name,
                        'time' => $time,
                        'desc' => trim($slot['desc'] ?? ''),
                        'is_live' => ! empty($slot['is_live_pulse']),
                        'is_live_pulse' => ! empty($slot['is_live_pulse']),
                    ];
                }
            }
            HomepageSetting::set('aarti_slots_json', json_encode($slots, JSON_UNESCAPED_UNICODE));

            // Sync first 6 slots into legacy keys for backward compatibility
            for ($i = 1; $i <= 6; $i++) {
                $idx = $i - 1;
                HomepageSetting::set("aarti_{$i}_name", $slots[$idx]['name'] ?? '');
                HomepageSetting::set("aarti_{$i}_time", $slots[$idx]['time'] ?? '');
                HomepageSetting::set("aarti_{$i}_desc", $slots[$idx]['desc'] ?? '');
            }
        }

        // Dynamic Aarti Time Slots Repeater (Amavasya & Melas)
        if ($request->has('aarti_amavasya_slots') && is_array($request->input('aarti_amavasya_slots'))) {
            $amavasyaSlots = [];
            foreach ($request->input('aarti_amavasya_slots') as $slot) {
                $name = trim($slot['name'] ?? '');
                $time = trim($slot['time'] ?? '');
                if ($name !== '' || $time !== '') {
                    $amavasyaSlots[] = [
                        'name' => $name,
                        'time' => $time,
                        'desc' => trim($slot['desc'] ?? ''),
                        'is_live' => ! empty($slot['is_live_pulse']),
                        'is_live_pulse' => ! empty($slot['is_live_pulse']),
                    ];
                }
            }
            HomepageSetting::set('aarti_amavasya_slots_json', json_encode($amavasyaSlots, JSON_UNESCAPED_UNICODE));
        }

        HomepageSetting::clearCache();
        Artisan::call('view:clear');

        ActivityLog::log('Homepage CMS', 'update', 'Updated homepage layout, granular cards, and hero section parameters.');

        return back()->with('success', 'Homepage layout, cards selection, and component settings updated successfully.');
    }

    // --- ABOUT US PAGE CMS EDITOR ---
    public function aboutPage()
    {
        $settings = HomepageSetting::getAll();
        $orgSettings = OrganizationSetting::getSettings();

        return view('admin.about', compact('settings', 'orgSettings'));
    }

    public function updateAboutPage(Request $request)
    {
        $request->validate([
            'about_photo_file' => 'nullable|file|mimes:jpeg,jpg,png,webp,svg|max:10240',
            'about_photo_url' => 'nullable|string|max:500',
            'trust_tagline' => 'nullable|string|max:255',
            'trust_lead' => 'nullable|string',
            'trust_eyebrow' => 'nullable|string|max:255',
            'trust_heading' => 'nullable|string|max:255',
            'trust_desc' => 'nullable|string',
            'trust_p2' => 'nullable|string',
            'stat_years' => 'nullable|string|max:50',
            'stat_years_title' => 'nullable|string|max:255',
            'stat_trees' => 'nullable|string|max:50',
            'stat_trees_title' => 'nullable|string|max:255',
            'stat_cows' => 'nullable|string|max:50',
            'stat_cows_title' => 'nullable|string|max:255',
            'stat_students' => 'nullable|string|max:50',
            'stat_students_title' => 'nullable|string|max:255',
            'about_mission_eyebrow' => 'nullable|string|max:255',
            'about_mission_heading' => 'nullable|string|max:255',
            'about_mission_quote' => 'nullable|string',
            'mission_1_title' => 'nullable|string|max:255',
            'mission_1_desc' => 'nullable|string',
            'mission_1_tag' => 'nullable|string|max:255',
            'mission_2_title' => 'nullable|string|max:255',
            'mission_2_desc' => 'nullable|string',
            'mission_2_tag' => 'nullable|string|max:255',
            'mission_3_title' => 'nullable|string|max:255',
            'mission_3_desc' => 'nullable|string',
            'mission_3_tag' => 'nullable|string|max:255',
            'mission_4_title' => 'nullable|string|max:255',
            'mission_4_desc' => 'nullable|string',
            'mission_4_tag' => 'nullable|string|max:255',
            'pledge_title' => 'nullable|string|max:255',
            'pledge_desc' => 'nullable|string',
        ]);

        $photoUrl = $request->input('about_photo_url');
        if ($request->hasFile('about_photo_file')) {
            $file = $request->file('about_photo_file');
            $ext = strtolower($file->guessExtension() ?: $file->getClientOriginalExtension());
            if (! in_array($ext, ['jpeg', 'jpg', 'png', 'webp', 'svg'], true)) {
                return back()->withInput()->withErrors(['about_photo_file' => 'The image must be a valid PNG, JPG, WebP, or SVG.']);
            }
            $filename = 'about-'.time().'.'.$ext;
            $destinationDir = public_path('uploads');
            if (! File::isDirectory($destinationDir)) {
                File::makeDirectory($destinationDir, 0755, true, true);
            }
            $file->move($destinationDir, $filename);
            if ($ext === 'svg') {
                $this->sanitizeSvgFile($destinationDir.'/'.$filename);
            }
            $photoUrl = '/uploads/'.$filename;
        }

        if ($photoUrl) {
            HomepageSetting::set('about_photo_1_url', $photoUrl);
        }

        // Handle 4 Environmental Mission Pillar Images
        for ($i = 1; $i <= 4; $i++) {
            $imgKey = "mission_{$i}_image_url";
            $fileKey = "mission_{$i}_image_file";
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                $ext = strtolower($file->guessExtension() ?: $file->getClientOriginalExtension());
                if (in_array($ext, ['jpeg', 'jpg', 'png', 'webp', 'svg'], true)) {
                    $filename = "mission-{$i}-".time().'.'.$ext;
                    $file->move(public_path('uploads'), $filename);
                    if ($ext === 'svg') {
                        $this->sanitizeSvgFile(public_path('uploads/'.$filename));
                    }
                    HomepageSetting::set($imgKey, '/uploads/'.$filename);
                }
            } elseif ($request->filled($imgKey)) {
                HomepageSetting::set($imgKey, $request->input($imgKey));
            }
        }

        $textFields = [
            'trust_tagline',
            'trust_lead',
            'trust_eyebrow',
            'trust_heading',
            'trust_desc',
            'trust_p2',
            'stat_years',
            'stat_years_title',
            'stat_trees',
            'stat_trees_title',
            'stat_cows',
            'stat_cows_title',
            'stat_students',
            'stat_students_title',
            'about_mission_eyebrow',
            'about_mission_heading',
            'about_mission_quote',
            'mission_1_title',
            'mission_1_desc',
            'mission_1_tag',
            'mission_1_image_url',
            'mission_2_title',
            'mission_2_desc',
            'mission_2_tag',
            'mission_2_image_url',
            'mission_3_title',
            'mission_3_desc',
            'mission_3_tag',
            'mission_3_image_url',
            'mission_4_title',
            'mission_4_desc',
            'mission_4_tag',
            'mission_4_image_url',
            'pledge_title',
            'pledge_desc',
        ];

        foreach ($textFields as $field) {
            if ($request->has($field)) {
                HomepageSetting::set($field, $request->input($field));
            }
        }

        HomepageSetting::clearCache();
        Artisan::call('view:clear');

        ActivityLog::log('About CMS', 'update', 'Updated About Us page narrative, mission pillars, and sacred stats.');

        return redirect()->route('admin.about')->with('success', 'About Us page content updated successfully.');
    }

    // --- SERVER MEDIA GALLERY MANAGER (SCREENSHOT 4) ---
    public function mediaGallery(Request $request)
    {
        $tab = $request->get('tab', 'all');
        $query = MediaFile::latest();

        if ($tab === 'photos') {
            $query->where('file_type', 'image');
        } elseif ($tab === 'videos') {
            $query->where('file_type', 'video');
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('filename', 'like', "%{$q}%")
                    ->orWhere('original_name', 'like', "%{$q}%");
            });
        }

        $media = $query->paginate(24);
        $totalCount = MediaFile::count();
        $photosCount = MediaFile::where('file_type', 'image')->count();
        $videosCount = MediaFile::where('file_type', 'video')->count();

        return view('admin.media-gallery', compact('media', 'totalCount', 'photosCount', 'videosCount', 'tab'));
    }

    public function uploadMedia(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,jpg,png,webp,svg,gif,mp4,webm,mov,avi,mkv|max:102400', // max 100MB
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->guessExtension() ?: $file->getClientOriginalExtension());
        $mime = $file->getMimeType() ?: ($file->getClientMimeType() ?: 'application/octet-stream');
        $isVideo = str_starts_with($mime, 'video/') || in_array($extension, ['mp4', 'webm', 'mov', 'avi', 'mkv'], true);

        $destinationDir = public_path('uploads');
        if (! File::isDirectory($destinationDir)) {
            File::makeDirectory($destinationDir, 0755, true, true);
        }

        $safeBase = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
        if (empty($safeBase)) {
            $safeBase = 'media-'.time();
        }

        $dimensions = null;

        if ($isVideo) {
            $finalFilename = $safeBase.'-'.time().'.'.$extension;
            $file->move($destinationDir, $finalFilename);
            $fileType = 'video';
            $finalPath = '/uploads/'.$finalFilename;
            $fileSize = filesize($destinationDir.'/'.$finalFilename);
        } else {
            $fileType = 'image';

            // Check if client canvas already converted to WebP
            if ($extension === 'webp' || $mime === 'image/webp') {
                $finalFilename = $safeBase.'-'.time().'.webp';
                $file->move($destinationDir, $finalFilename);
                $finalPath = '/uploads/'.$finalFilename;
                $fileSize = filesize($destinationDir.'/'.$finalFilename);
                $imgInfo = @getimagesize($destinationDir.'/'.$finalFilename);
                if ($imgInfo) {
                    $dimensions = $imgInfo[0].'x'.$imgInfo[1];
                }
            } else {
                // If GD imagewebp is available on server, convert to WebP
                if (function_exists('imagewebp') && in_array($extension, ['jpg', 'jpeg', 'png'])) {
                    $tempPath = $file->getRealPath();
                    $finalFilename = $safeBase.'-'.time().'.webp';
                    $destPath = $destinationDir.'/'.$finalFilename;

                    $srcImg = null;
                    if ($extension === 'png') {
                        $srcImg = @imagecreatefrompng($tempPath);
                        if ($srcImg) {
                            imagepalettetotruecolor($srcImg);
                            imagealphablending($srcImg, true);
                            imagesavealpha($srcImg, true);
                        }
                    } else {
                        $srcImg = @imagecreatefromjpeg($tempPath);
                    }

                    if ($srcImg) {
                        imagewebp($srcImg, $destPath, 82);
                        imagedestroy($srcImg);
                        $finalPath = '/uploads/'.$finalFilename;
                        $fileSize = filesize($destPath);
                        $mime = 'image/webp';
                        $imgInfo = @getimagesize($destPath);
                        if ($imgInfo) {
                            $dimensions = $imgInfo[0].'x'.$imgInfo[1];
                        }
                    } else {
                        // Fallback: store original
                        $finalFilename = $safeBase.'-'.time().'.'.$extension;
                        $file->move($destinationDir, $finalFilename);
                        $finalPath = '/uploads/'.$finalFilename;
                        $fileSize = filesize($destinationDir.'/'.$finalFilename);
                    }
                } else {
                    // Direct storage without GD
                    $finalFilename = $safeBase.'-'.time().'.'.$extension;
                    $file->move($destinationDir, $finalFilename);
                    if ($extension === 'svg') {
                        $this->sanitizeSvgFile($destinationDir.'/'.$finalFilename);
                    }
                    $finalPath = '/uploads/'.$finalFilename;
                    $fileSize = filesize($destinationDir.'/'.$finalFilename);
                    $imgInfo = @getimagesize($destinationDir.'/'.$finalFilename);
                    if ($imgInfo) {
                        $dimensions = $imgInfo[0].'x'.$imgInfo[1];
                    }
                }
            }
        }

        $mediaRecord = MediaFile::create([
            'filename' => $finalFilename,
            'original_name' => $originalName,
            'file_path' => $finalPath,
            'file_type' => $fileType,
            'mime_type' => $mime,
            'file_size' => $fileSize,
            'dimensions' => $dimensions,
        ]);

        ActivityLog::log('Media CMS', 'upload', "Uploaded {$fileType}: {$finalFilename}");

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'media' => $mediaRecord,
                'url' => asset($finalPath),
                'relative_path' => $finalPath,
            ]);
        }

        return back()->with('success', "Media file '{$finalFilename}' uploaded successfully.");
    }

    public function deleteMedia($id)
    {
        $media = MediaFile::findOrFail($id);
        $targetDir = realpath(public_path('uploads'));
        $rawPath = public_path(ltrim($media->file_path, '/'));
        $filePath = realpath($rawPath);

        // Security: Ensure path traversal cannot delete files outside public/uploads
        if ($filePath && $targetDir && str_starts_with($filePath, $targetDir) && File::exists($filePath)) {
            File::delete($filePath);
        }

        $filename = $media->filename;
        $media->delete();

        ActivityLog::log('Media CMS', 'delete', "Deleted Media: {$filename}");

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Media deleted successfully.']);
        }

        return back()->with('success', "Media '{$filename}' deleted successfully.");
    }

    public function batchDeleteMedia(Request $request)
    {
        $ids = $request->input('ids', []);
        $deletedCount = 0;
        $targetDir = realpath(public_path('uploads'));

        foreach ($ids as $id) {
            $media = MediaFile::find($id);
            if ($media) {
                $rawPath = public_path(ltrim($media->file_path, '/'));
                $filePath = realpath($rawPath);
                if ($filePath && $targetDir && str_starts_with($filePath, $targetDir) && File::exists($filePath)) {
                    File::delete($filePath);
                }
                $media->delete();
                $deletedCount++;
            }
        }

        ActivityLog::log('Media CMS', 'batch_delete', "Batch deleted {$deletedCount} media files.");

        return response()->json(['success' => true, 'deleted_count' => $deletedCount]);
    }

    public function apiMediaList(Request $request)
    {
        $query = MediaFile::latest();

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('file_type', $request->type);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('filename', 'like', "%{$q}%")
                    ->orWhere('original_name', 'like', "%{$q}%");
            });
        }

        $items = $query->take(60)->get();

        return response()->json([
            'success' => true,
            'items' => $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'filename' => $item->filename,
                    'url' => asset($item->file_path),
                    'relative_path' => $item->file_path,
                    'file_type' => $item->file_type,
                    'formatted_size' => $item->formatted_size,
                    'dimensions' => $item->dimensions,
                ];
            }),
        ]);
    }

    // --- EMAIL SMTP & TEMPLATES MANAGER ---
    public function emailTemplates(Request $request)
    {
        $category = $request->query('category', 'all');
        $search = $request->query('search');

        $query = EmailTemplate::query();
        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $templates = $query->orderBy('category', 'asc')->orderBy('id', 'asc')->get();
        $settings = OrganizationSetting::getSettings();
        $recentLogs = EmailLog::latest()->take(25)->get();

        $stats = [
            'total_templates' => EmailTemplate::count(),
            'active_templates' => EmailTemplate::where('is_active', true)->count(),
            'disabled_templates' => EmailTemplate::where('is_active', false)->count(),
            'is_sending_enabled' => $settings->isEmailSendingEnabled(),
            'is_smtp_configured' => $settings->isSmtpConfigured(),
            'logs_total' => EmailLog::count(),
            'logs_sent' => EmailLog::where('status', 'sent')->count(),
            'logs_failed' => EmailLog::where('status', 'failed')->count(),
            'logs_simulated' => EmailLog::where('status', 'simulated')->count(),
            'logs_stopped' => EmailLog::where('status', 'stopped')->count(),
        ];

        return view('admin.email-templates', compact('templates', 'settings', 'recentLogs', 'stats', 'category', 'search'));
    }

    public function storeEmailTemplate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:100|regex:/^[a-z0-9_-]+$/|unique:email_templates,slug',
            'category' => 'required|string|in:donations,auth,services,inquiries,volunteers,newsletter,general',
            'subject' => 'required|string|max:255',
            'content_html' => 'required|string',
            'description' => 'nullable|string|max:500',
            'placeholders_hint' => 'nullable|string|max:255',
            'is_active' => 'nullable',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_system'] = false;

        $template = EmailTemplate::create($validated);

        ActivityLog::log('Email CMS', 'create_template', "Created new email template: {$template->name} ({$template->slug})");

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => "New email template '{$template->name}' created successfully."]);
        }

        return back()->with('success', "Email template '{$template->name}' created successfully.");
    }

    public function previewEmailTemplate($id)
    {
        $template = EmailTemplate::findOrFail($id);

        $sampleData = [
            '{name}' => 'Aditya Sharma',
            '{email}' => 'aditya.sharma@example.com',
            '{otp}' => '749216',
            '{amount}' => '2,100.00',
            '{cause}' => 'कामदगिरि गौ सेवा एवं संरक्षण प्रकल्प',
            '{receipt_id}' => 'SKP-'.date('Ymd').'-REC-0842',
            '{receipt_number}' => 'SKP-'.date('Ymd').'-REC-0842',
            '{date}' => now()->format('d M Y'),
            '{site_name}' => 'Shri Kamadgiri Peetham Sewa Trust',
            '{site_url}' => url('/'),
            '{month}' => now()->format('F Y'),
            '{year}' => date('Y'),
            '{payment_id}' => 'pay_Kamadgiri991248',
            '{payment_url}' => url('/dashboard'),
            '{receipt_url}' => url('/receipt/SKP-'.date('Ymd').'-REC-0842'),
            '{post_title}' => 'चित्रकूट माहात्म्य: कामदगिरि परिक्रमा का आध्यात्मिक एवं वैज्ञानिक महत्व',
            '{post_excerpt}' => 'कामदगिरि पर्वत की परिक्रमा केवल एक धार्मिक अनुष्ठान नहीं, अपितु मन, शरीर और आत्मा के शुद्धिकरण का दिव्य अनुभव है।',
            '{article_url}' => url('/blog'),
            '{booking_id}' => 'CKT-TOUR-9912',
            '{booking_number}' => 'SKP-PB-'.date('Ymd').'-84920',
            '{service_name}' => 'कामदगिरि नित्य अभिषेक एवं विशेष रुद्राभिषेक',
            '{booking_date}' => now()->addDays(2)->format('d M Y'),
            '{time_slot}' => 'प्रातः 07:00 AM (मंगला दर्शन)',
            '{gotra}' => 'कश्यप',
            '{nakshatra}' => 'रोहिणी',
            '{sankalp_purpose}' => 'पारिवारिक सुख, शांति एवं आरोग्य',
            '{prasad_address}' => '108, रामघाट रोड, चित्रकूट धाम',
            '{admin_notes}' => 'आपका संकल्प वैदिक आचार्यों द्वारा विधिपूर्वक संपन्न कराया जाएगा।',
            '{seva_areas}' => 'गौसेवा एवं गुरुकुल अन्नक्षेत्र',
            '{city}' => 'चित्रकूट धाम',
            '{state}' => 'उत्तर प्रदेश',
            '{phone}' => '+91 94150 00000',
            '{applicant_id}' => 'VOL-0012',
            '{payment_status}' => 'VERIFIED (PAID)',
            '{enquiry_number}' => 'AVE-'.date('Ymd').'-0492',
            '{check_in_date}' => now()->addDays(5)->format('d M Y'),
            '{check_out_date}' => now()->addDays(7)->format('d M Y'),
            '{devotees}' => '4',
            '{rooms}' => '2',
            '{room_preference}' => 'Air-Cooled Family Suite (परिवार कक्ष)',
            '{purpose}' => 'कामदगिरि परिक्रमा एवं दर्शन (Pilgrimage)',
            '{special_requests}' => 'वरिष्ठ नागरिक हेतु भूतल कक्ष (Ground floor room preferred)',
            '{status}' => 'आधिकारिक रूप से स्वीकृत (APPROVED)',
            '{event_type}' => 'श्री रामचरितमानस नवाह्न पारायण एवं हवन',
            '{proposed_date}' => now()->addDays(14)->format('d M Y'),
            '{gathering}' => '150',
            '{duration}' => '3 दिवस',
            '{requirements}' => 'यज्ञशाला एवं अन्नक्षेत्र सहयोग व्यवस्था',
            '{subject}' => 'चित्रकूट धाम दर्शन एवं आश्रम विश्राम के संबंध में',
            '{message}' => 'हम 4 पारिवारिक सदस्य कामदगिरि परिक्रमा दर्शन हेतु आ रहे हैं। कृपया विश्राम एवं अभिषेक की व्यवस्था का मार्गदर्शन करें।',
            '{reply_message}' => 'जय कामतानाथ! आपके आगमन की तिथि पर विश्राम गृह में व्यवस्था उपलब्ध है। मंदिर कार्यालय द्वारा आपके लिए आचार्य जी का समय निर्धारित कर दिया गया है।',
        ];

        $renderedSubject = str_replace(array_keys($sampleData), array_values($sampleData), $template->subject);
        $renderedHtml = str_replace(array_keys($sampleData), array_values($sampleData), $template->content_html);

        return response()->json([
            'success' => true,
            'template' => $template,
            'rendered_subject' => $renderedSubject,
            'rendered_html' => $renderedHtml,
        ]);
    }

    public function updateEmailTemplate(Request $request, $id)
    {
        $template = EmailTemplate::findOrFail($id);
        $request->validate([
            'name' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'category' => 'nullable|string|in:donations,auth,services,inquiries,volunteers,newsletter,general',
            'description' => 'nullable|string|max:500',
            'content_html' => 'required|string',
        ]);

        $data = [
            'subject' => $request->subject,
            'content_html' => $request->content_html,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->filled('name')) {
            $data['name'] = $request->name;
        }
        if ($request->filled('category')) {
            $data['category'] = $request->category;
        }
        if ($request->has('description')) {
            $data['description'] = $request->description;
        }

        $template->update($data);

        ActivityLog::log('Email CMS', 'update', "Updated Email Template: {$template->name}");

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => "Email template '{$template->name}' updated successfully."]);
        }

        return back()->with('success', "Email template '{$template->name}' updated successfully.");
    }

    public function destroyEmailTemplate($id)
    {
        $template = EmailTemplate::findOrFail($id);

        if ($template->is_system) {
            return response()->json([
                'success' => false,
                'message' => 'System notification templates cannot be permanently removed. You can disable/stop them instead.',
            ], 422);
        }

        $name = $template->name;
        $template->delete();

        ActivityLog::log('Email CMS', 'delete_template', "Deleted custom email template: {$name}");

        return response()->json([
            'success' => true,
            'message' => "Template '{$name}' deleted successfully.",
        ]);
    }

    public function toggleEmailSending(Request $request)
    {
        $settings = OrganizationSetting::getSettings();
        $newState = ! $settings->isEmailSendingEnabled();

        $settings->update([
            'is_email_sending_enabled' => $newState,
        ]);

        $statusText = $newState ? 'Active (Live Dispatches Enabled)' : 'Stopped (All automatic emails paused)';
        ActivityLog::log('Email CMS', 'toggle_service', "Toggled Email Sending Service to: {$statusText}");

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_enabled' => $newState,
                'message' => $newState
                    ? 'Email sending service is now ACTIVE. Automated notifications will dispatch normally.'
                    : 'Email sending service is now STOPPED. All automated emails and dispatches are paused.',
            ]);
        }

        return back()->with('success', "Email sending service updated: {$statusText}");
    }

    public function toggleTemplateStatus($id)
    {
        $template = EmailTemplate::findOrFail($id);
        $template->is_active = ! $template->is_active;
        $template->save();

        $statusText = $template->is_active ? 'Enabled' : 'Disabled';
        ActivityLog::log('Email CMS', 'toggle_template', "Toggled template {$template->name} to: {$statusText}");

        return response()->json([
            'success' => true,
            'is_active' => $template->is_active,
            'message' => "Template '{$template->name}' is now {$statusText}.",
        ]);
    }

    public function updateSmtpConfig(Request $request)
    {
        $settings = OrganizationSetting::getSettings();
        $data = $request->only([
            'smtp_host',
            'smtp_port',
            'smtp_username',
            'smtp_encryption',
            'smtp_from_address',
            'smtp_from_name',
        ]);

        if ($request->filled('smtp_password')) {
            $data['smtp_password'] = $request->smtp_password;
        }

        $isConfigured = ! empty($request->smtp_host) && ! empty($request->smtp_username);
        $data['is_smtp_configured'] = $isConfigured;

        $settings->update($data);
        EmailNotificationService::configureRuntimeMailer($settings);

        ActivityLog::log('Email CMS', 'smtp_update', 'Updated outgoing SMTP server credentials. Live SMTP status: '.($isConfigured ? 'Active' : 'Pending'));

        $msg = $isConfigured
            ? 'SMTP server configuration saved successfully. Real outgoing SMTP delivery is active.'
            : 'SMTP server settings saved (host details pending).';

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => $msg]);
        }

        return back()->with('success', $msg);
    }

    public function testSmtpConnection(Request $request)
    {
        $request->validate([
            'test_email' => 'nullable|email',
            'smtp_host' => 'nullable|string',
            'smtp_port' => 'nullable|integer',
            'smtp_username' => 'nullable|string',
            'smtp_password' => 'nullable|string',
            'smtp_encryption' => 'nullable|string',
            'template_slug' => 'nullable|string',
        ]);

        $settings = OrganizationSetting::getSettings();

        $host = $request->filled('smtp_host') ? $request->smtp_host : $settings->smtp_host;
        $port = $request->filled('smtp_port') ? (int) $request->smtp_port : (int) ($settings->smtp_port ?: 587);
        $username = $request->filled('smtp_username') ? $request->smtp_username : $settings->smtp_username;
        $password = $request->filled('smtp_password') ? $request->smtp_password : $settings->smtp_password;
        $encryption = $request->has('smtp_encryption') ? $request->smtp_encryption : $settings->smtp_encryption;
        $testEmail = $request->test_email;
        $templateSlug = $request->template_slug ?: 'donation_receipt';

        $diagnostics = EmailNotificationService::testSmtpConnectionDetailed(
            $host,
            $port,
            $username,
            $password,
            $encryption,
            $testEmail,
            $templateSlug
        );

        if ($diagnostics['success']) {
            ActivityLog::log('Email CMS', 'test_smtp', "SMTP diagnostic test completed for {$host}:{$port}".($testEmail ? " with test email to {$testEmail}" : ''));
        }

        return response()->json($diagnostics);
    }

    public function emailLogs(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = EmailLog::latest();
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('recipient_email', 'like', "%{$search}%")
                    ->orWhere('recipient_name', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('template_slug', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(25);

        return response()->json([
            'success' => true,
            'logs' => $logs,
        ]);
    }

    public function clearEmailLogs()
    {
        EmailLog::truncate();
        ActivityLog::log('Email CMS', 'clear_logs', 'Cleared all email outbox delivery logs.');

        return response()->json([
            'success' => true,
            'message' => 'Email delivery logs cleared successfully.',
        ]);
    }

    // --- NAVIGATION MENU CMS ---
    public function navigationMenu()
    {
        $menuTree = NavigationMenuItem::whereNull('parent_id')
            ->with([
                'children' => function ($q) {
                    $q->orderBy('sort_order', 'asc');
                },
            ])
            ->orderBy('sort_order', 'asc')
            ->get();

        $parentOptions = NavigationMenuItem::whereNull('parent_id')
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('admin.navigation-menu', compact('menuTree', 'parentOptions'));
    }

    public function storeNavigationMenuItem(Request $request)
    {
        $validated = $request->validate([
            'label_en' => 'required|string|max:150',
            'label_hi' => 'nullable|string|max:150',
            'url' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:navigation_menu_items,id',
            'target' => 'required|in:_self,_blank',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;
        if (! isset($validated['sort_order']) || $validated['sort_order'] === null) {
            $validated['sort_order'] = NavigationMenuItem::where('parent_id', $validated['parent_id'] ?? null)->max('sort_order') + 1;
        }

        NavigationMenuItem::create($validated);

        ActivityLog::log('Navigation CMS', 'create_menu_item', "Added navigation menu item: {$validated['label_en']}");

        return back()->with('success', "Menu item '{$validated['label_en']}' added successfully.");
    }

    public function updateNavigationMenuItem(Request $request, $id)
    {
        $item = NavigationMenuItem::findOrFail($id);

        $validated = $request->validate([
            'label_en' => 'required|string|max:150',
            'label_hi' => 'nullable|string|max:150',
            'url' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:navigation_menu_items,id',
            'target' => 'required|in:_self,_blank',
            'sort_order' => 'required|integer',
            'is_active' => 'nullable',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        // Prevent setting itself as parent
        if (isset($validated['parent_id']) && (int) $validated['parent_id'] === (int) $item->id) {
            $validated['parent_id'] = null;
        }

        $item->update($validated);

        ActivityLog::log('Navigation CMS', 'update_menu_item', "Updated navigation menu item: {$item->label_en}");

        return back()->with('success', "Menu item '{$item->label_en}' updated successfully.");
    }

    public function deleteNavigationMenuItem($id)
    {
        $item = NavigationMenuItem::findOrFail($id);
        $label = $item->label_en;
        $item->delete();

        ActivityLog::log('Navigation CMS', 'delete_menu_item', "Deleted navigation menu item: {$label}");

        return back()->with('success', "Menu item '{$label}' and any sub-items were deleted.");
    }

    public function resetNavigationMenu()
    {
        NavigationMenuItem::truncate();
        (new NavigationMenuSeeder)->run();

        ActivityLog::log('Navigation CMS', 'reset_menu', 'Reset navigation menu structure to pilgrimage defaults.');

        return back()->with('success', 'Navigation menu structure reset to default pilgrimage & trust layout.');
    }

    // --- POOJA & TIMETABLE CMS ---
    public function poojas()
    {
        $poojas = PoojaService::orderBy('sort_order')->orderBy('id', 'desc')->get();
        $timeSlots = PoojaTimeSlot::orderBy('sort_order')->orderBy('start_time')->get();
        $totalBookings = PoojaBooking::count();
        $pendingBookings = PoojaBooking::where('status', 'pending')->count();
        $confirmedBookings = PoojaBooking::where('status', 'confirmed')->count();

        $rawNormal = HomepageSetting::get('aarti_slots_json', '');
        $aartiNormalSlots = ! empty($rawNormal) ? (json_decode($rawNormal, true) ?: []) : [
            ['name' => 'प्रातः मंगला आरती', 'time' => '05:00 AM', 'desc' => 'कामदगिरि मुख्य गर्भगृह', 'is_live' => false],
            ['name' => 'ठाकुर जी शृंगार व बालभोग', 'time' => '07:30 AM', 'desc' => 'कामदगिरि मंदिर', 'is_live' => false],
            ['name' => 'राजभोग महाआरती', 'time' => '12:00 PM', 'desc' => 'अन्नकूट एवं भोग अर्पण', 'is_live' => false],
            ['name' => 'श्री कामदगिरि संध्या महाआरती', 'time' => '06:30 PM', 'desc' => 'रामघाट मन्दाकिनी पावन तट', 'is_live' => true],
            ['name' => 'शयन आरती', 'time' => '09:00 PM', 'desc' => 'कपाट विश्राम', 'is_live' => false],
        ];

        $rawAmavasya = HomepageSetting::get('aarti_amavasya_slots_json', '');
        $aartiAmavasyaSlots = ! empty($rawAmavasya) ? (json_decode($rawAmavasya, true) ?: []) : [
            ['name' => 'परिक्रमा मंगला आरती', 'time' => '03:30 AM', 'desc' => 'प्रातः विशेष कामदगिरि परिक्रमा', 'is_live' => false],
            ['name' => 'अखण्ड वैदिक हवन', 'time' => '08:00 AM – 11:30 AM', 'desc' => 'ऋषिकुल यज्ञशाला', 'is_live' => false],
            ['name' => 'मन्दाकिनी महा दीपोत्सव आरती', 'time' => '07:00 PM', 'desc' => 'रामघाट 11,000+ दीप प्रज्ज्वलन', 'is_live' => true],
            ['name' => 'अखण्ड रात्रि महापूजा व जागरण', 'time' => '10:30 PM', 'desc' => 'अमावस्या मध्यरात्रि विशेष अनुष्ठान', 'is_live' => false],
            ['name' => 'भोर आरती व कपाट विश्राम', 'time' => '04:00 AM', 'desc' => 'पर्व समापन एवं आशीर्वाद', 'is_live' => false],
        ];

        $aartiSettings = [
            'aarti_strip_title' => HomepageSetting::get('aarti_strip_title', 'नित्य आरती व दर्शन समय चक्र • Kamadgiri Dham Timings'),
            'aarti_strip_subtitle' => HomepageSetting::get('aarti_strip_subtitle', 'चित्रकूट धाम में नित्य महाआरती व गर्भगृह दर्शन समय सारणी'),
            'aarti_hawan_title' => HomepageSetting::get('aarti_hawan_title', 'दैनिक नवग्रह व रुद्र महाहवन'),
            'aarti_hawan_time' => HomepageSetting::get('aarti_hawan_time', 'नित्य प्रातः 07:30 से 09:00'),
            'aarti_hawan_place' => HomepageSetting::get('aarti_hawan_place', 'श्री कामदगिरि मुख्य यज्ञशाला'),
            'aarti_hawan_desc' => HomepageSetting::get('aarti_hawan_desc', 'वैदिक बटुकों एवं आचार्यों द्वारा यजमानों के नाम-गोत्र से आहुतियां'),
        ];

        $orgSettings = OrganizationSetting::getSettings();

        return view('admin.poojas', compact(
            'poojas',
            'timeSlots',
            'totalBookings',
            'pendingBookings',
            'confirmedBookings',
            'aartiNormalSlots',
            'aartiAmavasyaSlots',
            'aartiSettings',
            'orgSettings'
        ));
    }

    public function togglePoojaPackages(Request $request)
    {
        $orgSettings = OrganizationSetting::getSettings();
        $isEnabled = $request->boolean('is_pooja_packages_enabled');
        $orgSettings->update([
            'is_pooja_packages_enabled' => $isEnabled,
        ]);
        OrganizationSetting::clearCache();

        $statusText = $isEnabled ? 'enabled' : 'disabled';
        ActivityLog::log('Pooja CMS', 'toggle_packages', "Prebuilt Pooja packages feature was {$statusText}.");

        return redirect()->route('admin.poojas')->with('success', "Prebuilt Pooja packages feature has been {$statusText}.".(! $isEnabled ? ' Visitors accessing /pooja or /pooja/{slug} are now automatically redirected to the sacred booking form.' : ''));
    }

    public function updateAartiTimetable(Request $request)
    {
        if ($request->has('aarti_normal_slots') && is_array($request->input('aarti_normal_slots'))) {
            $slots = [];
            foreach ($request->input('aarti_normal_slots') as $slot) {
                $name = trim($slot['name'] ?? '');
                $time = trim($slot['time'] ?? '');
                if ($name !== '' || $time !== '') {
                    $slots[] = [
                        'name' => $name,
                        'time' => $time,
                        'desc' => trim($slot['desc'] ?? ''),
                        'is_live' => ! empty($slot['is_live']),
                        'is_live_pulse' => ! empty($slot['is_live']),
                    ];
                }
            }
            HomepageSetting::set('aarti_slots_json', json_encode($slots, JSON_UNESCAPED_UNICODE));
            for ($i = 1; $i <= 6; $i++) {
                $idx = $i - 1;
                HomepageSetting::set("aarti_{$i}_name", $slots[$idx]['name'] ?? '');
                HomepageSetting::set("aarti_{$i}_time", $slots[$idx]['time'] ?? '');
                HomepageSetting::set("aarti_{$i}_desc", $slots[$idx]['desc'] ?? '');
            }
        }

        if ($request->has('aarti_amavasya_slots') && is_array($request->input('aarti_amavasya_slots'))) {
            $amavasyaSlots = [];
            foreach ($request->input('aarti_amavasya_slots') as $slot) {
                $name = trim($slot['name'] ?? '');
                $time = trim($slot['time'] ?? '');
                if ($name !== '' || $time !== '') {
                    $amavasyaSlots[] = [
                        'name' => $name,
                        'time' => $time,
                        'desc' => trim($slot['desc'] ?? ''),
                        'is_live' => ! empty($slot['is_live']),
                        'is_live_pulse' => ! empty($slot['is_live']),
                    ];
                }
            }
            HomepageSetting::set('aarti_amavasya_slots_json', json_encode($amavasyaSlots, JSON_UNESCAPED_UNICODE));
        }

        if ($request->filled('aarti_strip_title')) {
            HomepageSetting::set('aarti_strip_title', $request->input('aarti_strip_title'));
        }
        if ($request->filled('aarti_strip_subtitle')) {
            HomepageSetting::set('aarti_strip_subtitle', $request->input('aarti_strip_subtitle'));
        }
        if ($request->filled('aarti_hawan_title')) {
            HomepageSetting::set('aarti_hawan_title', $request->input('aarti_hawan_title'));
        }
        if ($request->filled('aarti_hawan_time')) {
            HomepageSetting::set('aarti_hawan_time', $request->input('aarti_hawan_time'));
        }
        if ($request->filled('aarti_hawan_place')) {
            HomepageSetting::set('aarti_hawan_place', $request->input('aarti_hawan_place'));
        }
        if ($request->filled('aarti_hawan_desc')) {
            HomepageSetting::set('aarti_hawan_desc', $request->input('aarti_hawan_desc'));
        }

        HomepageSetting::clearCache();
        Artisan::call('view:clear');

        ActivityLog::log('Aarti CMS', 'update_timetable', 'Updated temple aarti schedules for normal days and amavasya night.');

        return back()->with('success', 'मंदिर महाआरती एवं दर्शन समय-सारणी (नित्य दिवस व अमावस्या महापर्व) सफलतापूर्वक सुरक्षित की गई।');
    }

    public function storePooja(Request $request)
    {
        $validated = $request->validate([
            'id' => 'nullable|exists:pooja_services,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'service_type' => 'nullable|string|in:pooja,hawan,anushthan',
            'tagline' => 'nullable|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0|max:99999999.99',
            'duration' => 'nullable|string|max:100',
            'priest_assigned' => 'nullable|string|max:255',
            'samagri_included' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'image_url' => 'nullable|string|max:500',
            'timings_raw' => 'nullable|string',
            'benefits_raw' => 'nullable|string',
            'includes_raw' => 'nullable|string',
            'is_active' => 'nullable',
        ]);

        $slug = ! empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);

        // Parse array fields from newline or comma strings
        $timings = ! empty($validated['timings_raw'])
            ? array_values(array_filter(array_map('trim', preg_split('/[\r\n,]+/', $validated['timings_raw']))))
            : ['07:00 AM - 08:30 AM', '05:30 PM - 07:00 PM'];

        $benefits = ! empty($validated['benefits_raw'])
            ? array_values(array_filter(array_map('trim', preg_split('/[\r\n]+/', $validated['benefits_raw']))))
            : [];

        $data = [
            'title_hi' => $validated['name'],
            'title_en' => $validated['tagline'] ?? $validated['name'],
            'slug' => $slug,
            'service_type' => $validated['service_type'] ?? 'pooja',
            'summary_hi' => $validated['tagline'] ?? Str::limit(strip_tags($validated['description']), 160),
            'description_hi' => $validated['description'],
            'suggested_donation_amount' => $validated['price'],
            'duration_minutes' => intval(preg_replace('/[^0-9]/', '', $validated['duration'] ?? '45')) ?: 45,
            'timings_display' => is_array($timings) ? implode(', ', $timings) : ($validated['timings_raw'] ?? 'नित्य प्रातः 07:00 AM व सायं 06:00 PM'),
            'benefits_hi' => $benefits,
            'samagri_included' => $validated['samagri_included'] ?? null,
            'priest_assigned' => $validated['priest_assigned'] ?? 'श्री कामदगिरि पीठम् के वैदिक आचार्य',
            'featured_image' => $validated['image_url'] ?? '/images/chitrakoot-hero.webp',
            'is_active' => $request->has('is_active') ? true : false,
        ];

        if (! empty($validated['id'])) {
            $pooja = PoojaService::findOrFail($validated['id']);
            $pooja->update($data);
            ActivityLog::log('Pooja CMS', 'update_pooja', "Updated Pooja service: {$pooja->name}");
            $message = "Pooja/Hawan package '{$pooja->name}' updated successfully.";
        } else {
            $pooja = PoojaService::create($data);
            ActivityLog::log('Pooja CMS', 'create_pooja', "Created new Pooja service: {$pooja->name}");
            $message = "Pooja/Hawan package '{$pooja->name}' created successfully.";
        }

        return back()->with('success', $message);
    }

    public function deletePooja($id)
    {
        $pooja = PoojaService::findOrFail($id);
        $name = $pooja->name;
        $pooja->delete();

        ActivityLog::log('Pooja CMS', 'delete_pooja', "Deleted Pooja service: {$name}");

        return back()->with('success', "Pooja/Hawan service '{$name}' deleted successfully.");
    }

    public function storePoojaSlot(Request $request)
    {
        $validated = $request->validate([
            'id' => 'nullable|exists:pooja_time_slots,id',
            'slot_name' => 'required|string|max:255',
            'start_time' => 'required|string|max:10',
            'end_time' => 'required|string|max:10',
            'service_type' => 'nullable|string|in:all,pooja,hawan',
            'max_capacity' => 'nullable|integer|min:1|max:100',
            'is_active' => 'nullable',
            'sort_order' => 'nullable|integer',
        ]);

        $data = [
            'slot_name' => $validated['slot_name'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'service_type' => $validated['service_type'] ?? 'all',
            'max_capacity' => intval($validated['max_capacity'] ?? 5),
            'is_active' => $request->has('is_active') ? true : false,
            'sort_order' => intval($validated['sort_order'] ?? 0),
        ];

        if (! empty($validated['id'])) {
            $slot = PoojaTimeSlot::findOrFail($validated['id']);
            $slot->update($data);
            $msg = "Temple time slot '{$slot->slot_name}' updated successfully.";
        } else {
            $slot = PoojaTimeSlot::create($data);
            $msg = "New temple time slot '{$slot->slot_name}' added successfully.";
        }

        ActivityLog::log('Pooja CMS', 'manage_slot', $msg);

        return back()->with('success', $msg);
    }

    public function deletePoojaSlot($id)
    {
        $slot = PoojaTimeSlot::findOrFail($id);
        $name = $slot->slot_name;
        $slot->delete();

        ActivityLog::log('Pooja CMS', 'delete_slot', "Deleted time slot: {$name}");

        return back()->with('success', "Time slot '{$name}' deleted successfully.");
    }

    public function poojaBookings(Request $request)
    {
        $query = PoojaBooking::with('pooja')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('booking_number', 'like', "%{$q}%")
                    ->orWhere('devotee_name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('gotra', 'like', "%{$q}%");
            });
        }

        $bookings = $query->paginate(20);
        $totalCount = PoojaBooking::count();
        $pendingCount = PoojaBooking::where('status', 'pending')->count();
        $quotedCount = PoojaBooking::where('status', 'quoted')->count();
        $confirmedCount = PoojaBooking::where('status', 'confirmed')->count();
        $completedCount = PoojaBooking::where('status', 'completed')->count();

        return view('admin.pooja-bookings', compact('bookings', 'totalCount', 'pendingCount', 'quotedCount', 'confirmedCount', 'completedCount'));
    }

    public function confirmPoojaBooking($id)
    {
        $booking = PoojaBooking::findOrFail($id);
        $booking->update([
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_id' => $booking->payment_id ?: ('ADMIN-OFFLINE-'.now()->format('YmdHis')),
        ]);

        // Send official confirmation email
        EmailNotificationService::sendPoojaBookingConfirmed($booking);

        ActivityLog::log('Pooja CMS', 'confirm_booking', "Confirmed booking #{$booking->booking_number} for {$booking->devotee_name}");

        return back()->with('success', "Booking #{$booking->booking_number} has been officially CONFIRMED. Confirmation email successfully sent to {$booking->email}.");
    }

    public function sendPoojaQuotation(Request $request, $id)
    {
        $booking = PoojaBooking::findOrFail($id);

        $validated = $request->validate([
            'quotation_amount' => 'required|numeric|min:1|max:99999999.99',
            'quotation_hours' => 'nullable|integer|min:1|max:12',
            'quotation_notes' => 'nullable|string|max:1000',
        ], [
            'quotation_amount.max' => 'स्वीकृत दक्षिणा/कोटेशन राशि ₹9,99,99,999 से अधिक नहीं हो सकती (Quotation amount cannot exceed ₹99,999,999).',
            'quotation_amount.min' => 'कोटेशन राशि कम से कम ₹1 होनी चाहिए।',
            'quotation_hours.max' => 'कोटेशन भुगतान लिंक की अधिकतम वैधता 12 घंटे तक ही सीमित हो सकती है (Maximum validity is 12 hours).',
        ]);

        $token = Str::random(48);
        $hours = min(12, max(1, (int) ($validated['quotation_hours'] ?? 6)));

        $booking->update([
            'quotation_amount' => $validated['quotation_amount'],
            'amount' => $validated['quotation_amount'],
            'quotation_notes' => $validated['quotation_notes'] ?? null,
            'quotation_token' => $token,
            'quoted_at' => now(),
            'quotation_expires_at' => now()->addHours($hours),
            'status' => 'quoted',
        ]);

        EmailNotificationService::sendPoojaQuotation($booking);

        ActivityLog::log('Pooja CMS', 'send_quotation', "Sent quotation ₹{$booking->quotation_amount} for booking #{$booking->booking_number} to {$booking->email}");

        return back()->with('success', "पूजा संकल्प #{$booking->booking_number} हेतु ₹{$booking->quotation_amount} का कोटेशन एवं रेज़रपे भुगतान लिंक {$booking->email} पर सफलतापूर्वक भेज दिया गया है।");
    }

    public function updatePoojaBookingStatus(Request $request, $id)
    {
        $booking = PoojaBooking::findOrFail($id);

        $validated = $request->validate([
            'booking_status' => 'required|in:pending,contacted,quoted,confirmed,completed,cancelled',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $previousStatus = $booking->status;
        $booking->update([
            'status' => $validated['booking_status'],
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        if ($booking->status === 'confirmed' && $previousStatus !== 'confirmed') {
            EmailNotificationService::sendPoojaBookingConfirmed($booking);
        } elseif ($booking->status === 'completed' && $previousStatus !== 'completed') {
            EmailNotificationService::sendPoojaBookingCompleted($booking);
        }

        ActivityLog::log('Pooja CMS', 'update_booking_status', "Updated booking #{$booking->booking_number} to {$validated['booking_status']}");

        return back()->with('success', "Booking #{$booking->booking_number} status updated to {$validated['booking_status']} and devotee notified.");
    }

    // --- VOLUNTEER REGISTRATION MANAGER ---
    public function volunteers(Request $request)
    {
        $query = VolunteerApplication::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('full_name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%")
                    ->orWhere('skills', 'like', "%{$q}%");
            });
        }

        $applications = $query->paginate(20);
        $totalApplications = VolunteerApplication::count();
        $pendingCount = VolunteerApplication::where('status', 'pending')->count();
        $reviewedCount = VolunteerApplication::where('status', 'reviewed')->count();
        $approvedCount = VolunteerApplication::where('status', 'approved')->count();
        $volunteerRegistrationEnabled = HomepageSetting::get('volunteer_registration_enabled', '1') === '1';

        return view('admin.volunteers', compact(
            'applications',
            'totalApplications',
            'pendingCount',
            'reviewedCount',
            'approvedCount',
            'volunteerRegistrationEnabled'
        ));
    }

    public function toggleVolunteers(Request $request)
    {
        $current = HomepageSetting::get('volunteer_registration_enabled', '1') === '1';
        $newVal = $current ? '0' : '1';
        HomepageSetting::set('volunteer_registration_enabled', $newVal);
        HomepageSetting::clearCache();

        $statusStr = $newVal === '1' ? 'ENABLED' : 'PAUSED';
        ActivityLog::log('Volunteer CMS', 'toggle_registration', "Volunteer registration {$statusStr}.");

        return back()->with('success', "Volunteer registration portal is now {$statusStr}.");
    }

    public function updateVolunteerStatus(Request $request, $id)
    {
        $applicant = VolunteerApplication::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,reviewed,approved,rejected',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $previousStatus = $applicant->status;
        $applicant->update($validated);

        // Send email notifications to volunteer for approval or rejection
        if ($applicant->status === 'approved' && $previousStatus !== 'approved') {
            EmailNotificationService::sendVolunteerApproved($applicant);
        } elseif ($applicant->status === 'rejected' && $previousStatus !== 'rejected') {
            EmailNotificationService::sendVolunteerRejected($applicant);
        }

        $applicantName = $applicant->name ?? $applicant->full_name ?? 'Applicant';
        ActivityLog::log('Volunteer CMS', 'update_applicant_status', "Updated applicant {$applicantName} status to {$applicant->status}");

        return back()->with('success', "Application for {$applicantName} marked as {$applicant->status} and notification dispatched.");
    }

    // --- NEWSLETTER SUBSCRIBERS ---
    public function subscribers(Request $request)
    {
        $query = NewsletterSubscriber::latest();

        if ($request->filled('q')) {
            $query->where('email', 'like', "%{$request->q}%");
        }

        $subscribers = $query->paginate(30);
        $totalSubscribers = NewsletterSubscriber::where('is_active', true)->count();

        return view('admin.subscribers', compact('subscribers', 'totalSubscribers'));
    }

    // --- ALL PAGES SEO EDITING CMS ---
    public function seoSettings()
    {
        $pageSeos = PageSeoSetting::all()->keyBy('page_key');

        $availablePages = [
            'home' => ['title' => 'Home Page', 'route' => 'home', 'url' => '/', 'raea_image' => '/images/og/og-home.png'],
            'about' => ['title' => 'About Us Page', 'route' => 'about', 'url' => '/about', 'raea_image' => '/images/og/og-about.png'],
            'leadership' => ['title' => 'Leadership & Trustees', 'route' => 'leadership', 'url' => '/leadership', 'raea_image' => '/images/og/og-leadership.png'],
            'seva' => ['title' => 'Seva Projects Directory', 'route' => 'seva.index', 'url' => '/seva', 'raea_image' => '/images/og/og-seva.png'],
            'chitrakoot' => ['title' => 'Chitrakoot Sacred Guide', 'route' => 'chitrakoot.index', 'url' => '/chitrakoot', 'raea_image' => '/images/og/og-chitrakoot.png'],
            'pooja' => ['title' => 'Online Pooja Booking', 'route' => 'pooja.index', 'url' => '/pooja', 'raea_image' => '/images/og/og-pooja.png'],
            'events' => ['title' => 'Events & Festivals', 'route' => 'events.index', 'url' => '/events', 'raea_image' => '/images/og/og-events.png'],
            'blog' => ['title' => 'Blog & Articles', 'route' => 'blog.index', 'url' => '/blog', 'raea_image' => '/images/og/og-blog.png'],
            'gallery' => ['title' => 'Sacred Photo & Video Gallery', 'route' => 'gallery.index', 'url' => '/gallery', 'raea_image' => '/images/og/og-gallery.png'],
            'contact' => ['title' => 'Contact Us Page', 'route' => 'contact', 'url' => '/contact', 'raea_image' => '/images/og/og-contact.png'],
            'donate' => ['title' => 'Donation & Causes Portal', 'route' => 'donate', 'url' => '/donate', 'raea_image' => '/images/og/og-donate.png'],
            'volunteer' => ['title' => 'Volunteer Seva Registration', 'route' => 'volunteer', 'url' => '/volunteer', 'raea_image' => '/images/og/og-volunteer.png'],
            'privacy' => ['title' => 'Privacy Policy', 'route' => 'privacy', 'url' => '/privacy-policy', 'raea_image' => '/images/og/og-privacy.png'],
            'services' => ['title' => 'Services & Facilities', 'route' => 'services.index', 'url' => '/services', 'raea_image' => '/images/og/og-services.png'],
            'aarti' => ['title' => 'Daily Aarti Schedule', 'route' => 'services.aarti', 'url' => '/services/aarti', 'raea_image' => '/images/og/og-aarti.png'],
            'atithi_vishram' => ['title' => 'Atithi Vishram Griha', 'route' => 'services.atithiVishram', 'url' => '/services/atithi-vishram', 'raea_image' => '/images/og/og-atithi-vishram.png'],
            'temple_booking' => ['title' => 'Temple & Event Booking', 'route' => 'services.templeBooking', 'url' => '/services/temple-booking', 'raea_image' => '/images/og/og-temple-booking.png'],
        ];

        return view('admin.seo-settings', compact('pageSeos', 'availablePages'));
    }

    public function updateSeoSettings(Request $request)
    {
        $pages = $request->input('pages', []);

        foreach ($pages as $key => $fields) {
            $existing = PageSeoSetting::where('page_key', $key)->first();
            $ogImage = $existing?->og_image;

            // Handle direct file upload for this page
            if ($request->hasFile("pages.{$key}.og_image_file")) {
                $file = $request->file("pages.{$key}.og_image_file");
                $ext = strtolower($file->guessExtension() ?: $file->getClientOriginalExtension());
                if (in_array($ext, ['jpeg', 'jpg', 'png', 'webp', 'svg'], true)) {
                    $filename = 'og-'.$key.'-'.time().'.'.$ext;
                    $destDir = public_path('uploads/og');
                    if (! File::isDirectory($destDir)) {
                        File::makeDirectory($destDir, 0755, true, true);
                    }
                    $file->move($destDir, $filename);
                    if ($ext === 'svg') {
                        $this->sanitizeSvgFile($destDir.'/'.$filename);
                    }
                    $ogImage = '/uploads/og/'.$filename;
                }
            } elseif (! empty($fields['remove_og_image'])) {
                // Explicit removal request
                $ogImage = null;
            } elseif (isset($fields['og_image_url'])) {
                // If text input is provided
                $val = trim($fields['og_image_url']);
                $ogImage = $val !== '' ? $val : null;
            } elseif (isset($fields['og_image'])) {
                $val = trim($fields['og_image']);
                $ogImage = $val !== '' ? $val : null;
            }

            PageSeoSetting::updateOrCreate(
                ['page_key' => $key],
                [
                    'page_title' => $fields['page_title'] ?? ucfirst(str_replace('_', ' ', $key)),
                    'meta_title' => $fields['meta_title'] ?? null,
                    'meta_description' => $fields['meta_description'] ?? null,
                    'meta_keywords' => $fields['meta_keywords'] ?? ($fields['keywords'] ?? null),
                    'canonical_url' => $fields['canonical_url'] ?? null,
                    'og_image' => $ogImage,
                    'no_index' => ! empty($fields['no_index']),
                ]
            );
        }

        PageSeoSetting::clearCache();
        Artisan::call('view:clear');

        ActivityLog::log('SEO CMS', 'update_all_pages_seo', 'Updated meta tags and SEO settings across core pages.');

        return back()->with('success', 'All page SEO titles, meta descriptions, and Open Graph configurations updated successfully.');
    }

    public function updatePageOgImage(Request $request)
    {
        $validated = $request->validate([
            'page_key' => 'required|string',
            'action' => 'required|in:upload,set_url,reset_raea,remove',
            'image_url' => 'nullable|string',
            'image_file' => 'nullable|file|mimes:jpeg,jpg,png,webp,svg|max:5120',
        ]);

        $key = $validated['page_key'];
        $entry = PageSeoSetting::firstOrCreate(
            ['page_key' => $key],
            ['page_title' => ucfirst(str_replace('_', ' ', $key))]
        );

        $raeaMapping = [
            'home' => '/images/og/og-home.png',
            'about' => '/images/og/og-about.png',
            'leadership' => '/images/og/og-leadership.png',
            'seva' => '/images/og/og-seva.png',
            'chitrakoot' => '/images/og/og-chitrakoot.png',
            'pooja' => '/images/og/og-pooja.png',
            'events' => '/images/og/og-events.png',
            'blog' => '/images/og/og-blog.png',
            'gallery' => '/images/og/og-gallery.png',
            'contact' => '/images/og/og-contact.png',
            'donate' => '/images/og/og-donate.png',
            'volunteer' => '/images/og/og-volunteer.png',
            'privacy' => '/images/og/og-privacy.png',
            'services' => '/images/og/og-services.png',
            'aarti' => '/images/og/og-aarti.png',
            'atithi_vishram' => '/images/og/og-atithi-vishram.png',
            'temple_booking' => '/images/og/og-temple-booking.png',
        ];

        if ($validated['action'] === 'remove') {
            $entry->og_image = null;
            $entry->save();
        } elseif ($validated['action'] === 'reset_raea') {
            $entry->og_image = $raeaMapping[$key] ?? '/images/og/og-home.png';
            $entry->save();
        } elseif ($validated['action'] === 'upload' && $request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $ext = strtolower($file->guessExtension() ?: $file->getClientOriginalExtension());
            $filename = 'og-'.$key.'-'.time().'.'.$ext;
            $destinationDir = public_path('uploads/og');
            if (! File::isDirectory($destinationDir)) {
                File::makeDirectory($destinationDir, 0755, true, true);
            }
            $file->move($destinationDir, $filename);
            if ($ext === 'svg') {
                $this->sanitizeSvgFile($destinationDir.'/'.$filename);
            }
            $entry->og_image = '/uploads/og/'.$filename;
            $entry->save();
        } elseif ($validated['action'] === 'set_url') {
            $entry->og_image = $request->input('image_url');
            $entry->save();
        }

        PageSeoSetting::clearCache();
        Artisan::call('view:clear');

        ActivityLog::log('SEO CMS', 'update_page_og_image', "Configured featured image for page: {$key} ({$validated['action']})");

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'page_key' => $key,
                'og_image' => $entry->og_image,
                'og_image_url' => $entry->og_image_url,
                'has_image' => $entry->hasFeaturedImage(),
                'message' => 'Social share image updated successfully.',
            ]);
        }

        return back()->with('success', "Featured social image for {$key} updated successfully.");
    }

    // --- UPCOMING EVENTS & UTSAVS CMS ---
    public function events(Request $request)
    {
        // Auto-expire passed events into archive
        Event::autoArchiveExpired();

        $status = $request->get('status', 'all');
        $q = trim($request->get('q', ''));

        $query = Event::query();

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('title_hi', 'like', "%{$q}%")
                    ->orWhere('title_en', 'like', "%{$q}%")
                    ->orWhere('location_hi', 'like', "%{$q}%");
            });
        }

        if ($status === 'upcoming') {
            $query->upcoming();
        } elseif ($status === 'past' || $status === 'archived') {
            $query->archived();
        } elseif ($status === 'draft') {
            $query->where('is_published', false);
        }

        $events = $query->orderBy('event_date', $status === 'archived' || $status === 'past' ? 'desc' : 'asc')->paginate(15)->withQueryString();

        $totalCount = Event::count();
        $upcomingCount = Event::upcoming()->count();
        $publishedCount = Event::where('is_published', true)->count();
        $pastCount = Event::archived()->count();
        $archivedCount = $pastCount;

        return view('admin.events', compact(
            'events',
            'totalCount',
            'upcomingCount',
            'publishedCount',
            'pastCount',
            'archivedCount',
            'status',
            'q'
        ));
    }

    public function storeEvent(Request $request)
    {
        $titleHi = trim((string) $request->input('title_hi', ''));
        $titleEn = trim((string) $request->input('title_en', ''));

        if ($titleHi === '' && $titleEn === '') {
            return back()->withInput()->withErrors(['title_hi' => 'कार्यक्रम का शीर्षक (Event Title in Hindi or English) अनिवार्य है।']);
        }

        if ($titleHi === '') {
            $titleHi = $titleEn;
        }
        if ($titleEn === '') {
            $titleEn = $titleHi;
        }

        $validated = $request->validate([
            'title_hi' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'event_date' => 'required',
            'event_end_date' => 'nullable',
            'location_hi' => 'nullable|string|max:255',
            'location_en' => 'nullable|string|max:255',
            'summary_hi' => 'nullable|string',
            'summary_en' => 'nullable|string',
            'description_hi' => 'nullable|string',
            'description_en' => 'nullable|string',
            'featured_image' => 'nullable|string',
            'featured_image_file' => 'nullable|image|max:10240',
            'registration_url' => 'nullable|string|max:500',
            'is_published' => 'nullable',
        ]);

        try {
            $eventDate = Carbon::parse($validated['event_date']);
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors(['event_date' => 'प्रारंभ तिथि/समय अमान्य है। (Invalid start date/time format)']);
        }

        $eventEndDate = null;
        if (! empty($validated['event_end_date'])) {
            try {
                $eventEndDate = Carbon::parse($validated['event_end_date']);
                if ($eventEndDate->lt($eventDate)) {
                    return back()->withInput()->withErrors(['event_end_date' => 'समापन तिथि/समय (End date) कार्यक्रम प्रारंभ तिथि के बाद होना चाहिए।']);
                }
            } catch (\Throwable $e) {
                return back()->withInput()->withErrors(['event_end_date' => 'समापन तिथि/समय अमान्य है। (Invalid end date format)']);
            }
        }

        $featuredImageUrl = ! empty($validated['featured_image']) ? $validated['featured_image'] : null;
        if ($request->hasFile('featured_image_file')) {
            $file = $request->file('featured_image_file');
            $ext = strtolower($file->guessExtension() ?: $file->getClientOriginalExtension());
            if (! in_array($ext, ['jpeg', 'jpg', 'png', 'webp', 'svg'], true)) {
                return back()->withInput()->withErrors(['featured_image_file' => 'The event image must be a valid PNG, JPG, WebP, or SVG.']);
            }
            $filename = 'event-'.time().'.'.$ext;
            $destinationDir = public_path('uploads');
            if (! File::isDirectory($destinationDir)) {
                File::makeDirectory($destinationDir, 0755, true, true);
            }
            $file->move($destinationDir, $filename);
            $featuredImageUrl = '/uploads/'.$filename;
        }

        $slugBase = Str::slug($titleEn) ?: Str::slug($titleHi);
        if (empty($slugBase)) {
            $slugBase = 'event-'.time();
        }
        $slug = $slugBase;
        $counter = 1;
        while (Event::where('slug', $slug)->exists()) {
            $slug = $slugBase.'-'.$counter;
            $counter++;
        }

        $event = Event::create([
            'title_hi' => $titleHi,
            'title_en' => $titleEn,
            'slug' => $slug,
            'event_date' => $eventDate,
            'event_end_date' => $eventEndDate,
            'location_hi' => ! empty($validated['location_hi']) ? $validated['location_hi'] : 'चित्रकूट धाम',
            'location_en' => ! empty($validated['location_en']) ? $validated['location_en'] : 'Chitrakoot Dham',
            'summary_hi' => ! empty($validated['summary_hi']) ? $validated['summary_hi'] : null,
            'summary_en' => ! empty($validated['summary_en']) ? $validated['summary_en'] : null,
            'description_hi' => ! empty($validated['description_hi']) ? $validated['description_hi'] : null,
            'description_en' => ! empty($validated['description_en']) ? $validated['description_en'] : null,
            'featured_image' => $featuredImageUrl,
            'registration_url' => ! empty($validated['registration_url']) ? $validated['registration_url'] : null,
            'is_published' => $request->boolean('is_published', true),
        ]);

        ActivityLog::log('Events CMS', 'create', 'Created new religious event: '.$event->title_hi);

        return back()->with('success', 'धार्मिक कार्यक्रम/उत्सव सफलतापूर्वक जोड़ा गया। (Event created successfully)');
    }

    public function updateEvent(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $titleHi = trim((string) $request->input('title_hi', ''));
        $titleEn = trim((string) $request->input('title_en', ''));

        if ($titleHi === '' && $titleEn === '') {
            return back()->withInput()->withErrors(['title_hi' => 'कार्यक्रम का शीर्षक (Event Title in Hindi or English) अनिवार्य है।']);
        }

        if ($titleHi === '') {
            $titleHi = $titleEn;
        }
        if ($titleEn === '') {
            $titleEn = $titleHi;
        }

        $validated = $request->validate([
            'title_hi' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'event_date' => 'required',
            'event_end_date' => 'nullable',
            'location_hi' => 'nullable|string|max:255',
            'location_en' => 'nullable|string|max:255',
            'summary_hi' => 'nullable|string',
            'summary_en' => 'nullable|string',
            'description_hi' => 'nullable|string',
            'description_en' => 'nullable|string',
            'featured_image' => 'nullable|string',
            'featured_image_file' => 'nullable|image|max:10240',
            'registration_url' => 'nullable|string|max:500',
            'is_published' => 'nullable',
        ]);

        try {
            $eventDate = Carbon::parse($validated['event_date']);
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors(['event_date' => 'प्रारंभ तिथि/समय अमान्य है। (Invalid start date/time format)']);
        }

        $eventEndDate = null;
        if (! empty($validated['event_end_date'])) {
            try {
                $eventEndDate = Carbon::parse($validated['event_end_date']);
                if ($eventEndDate->lt($eventDate)) {
                    return back()->withInput()->withErrors(['event_end_date' => 'समापन तिथि/समय (End date) कार्यक्रम प्रारंभ तिथि के बाद होना चाहिए।']);
                }
            } catch (\Throwable $e) {
                return back()->withInput()->withErrors(['event_end_date' => 'समापन तिथि/समय अमान्य है। (Invalid end date format)']);
            }
        }

        $featuredImageUrl = $event->featured_image;
        if ($request->hasFile('featured_image_file')) {
            $file = $request->file('featured_image_file');
            $ext = strtolower($file->guessExtension() ?: $file->getClientOriginalExtension());
            if (! in_array($ext, ['jpeg', 'jpg', 'png', 'webp', 'svg'], true)) {
                return back()->withInput()->withErrors(['featured_image_file' => 'The event image must be a valid PNG, JPG, WebP, or SVG.']);
            }
            $filename = 'event-'.time().'.'.$ext;
            $destinationDir = public_path('uploads');
            if (! File::isDirectory($destinationDir)) {
                File::makeDirectory($destinationDir, 0755, true, true);
            }
            $file->move($destinationDir, $filename);
            $featuredImageUrl = '/uploads/'.$filename;
        } elseif ($request->filled('featured_image')) {
            $featuredImageUrl = $validated['featured_image'];
        }

        $event->update([
            'title_hi' => $titleHi,
            'title_en' => $titleEn,
            'event_date' => $eventDate,
            'event_end_date' => $eventEndDate,
            'location_hi' => ! empty($validated['location_hi']) ? $validated['location_hi'] : $event->location_hi,
            'location_en' => ! empty($validated['location_en']) ? $validated['location_en'] : $event->location_en,
            'summary_hi' => ! empty($validated['summary_hi']) ? $validated['summary_hi'] : $event->summary_hi,
            'summary_en' => ! empty($validated['summary_en']) ? $validated['summary_en'] : $event->summary_en,
            'description_hi' => ! empty($validated['description_hi']) ? $validated['description_hi'] : $event->description_hi,
            'description_en' => ! empty($validated['description_en']) ? $validated['description_en'] : $event->description_en,
            'featured_image' => $featuredImageUrl,
            'registration_url' => ! empty($validated['registration_url']) ? $validated['registration_url'] : $event->registration_url,
            'is_published' => $request->boolean('is_published'),
        ]);

        ActivityLog::log('Events CMS', 'update', 'Updated event: '.$event->title_hi);

        return back()->with('success', 'कार्यक्रम विवरण अद्यतन किया गया। (Event updated successfully)');
    }

    public function deleteEvent(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $title = $event->title_hi;
        $event->delete();

        ActivityLog::log('Events CMS', 'delete', 'Deleted event: '.$title);

        return back()->with('success', 'कार्यक्रम हटा दिया गया। (Event deleted successfully)');
    }

    public function toggleArchiveEvent($id)
    {
        $event = Event::findOrFail($id);
        if ($event->is_archived) {
            $event->unarchive();
            $msg = "कार्यक्रम '{$event->title_hi}' को अभिलेख (Archive) से पुनः सक्रिय किया गया।";
        } else {
            $event->archive();
            $msg = "कार्यक्रम '{$event->title_hi}' को सफलतापूर्वक अभिलेख (Archive) में स्थानांतरित किया गया।";
        }

        ActivityLog::log('Events CMS', 'toggle_archive', $msg);

        return back()->with('success', $msg);
    }

    public function autoArchiveEvents()
    {
        $count = Event::autoArchiveExpired();

        ActivityLog::log('Events CMS', 'auto_archive', "Auto-archived {$count} past events.");

        return back()->with('success', $count > 0
            ? "तिथि समाप्त होने के कारण {$count} कार्यक्रम स्वतः अभिलेख (Archive) में स्थानांतरित किए गए।"
            : 'सभी आगामी कार्यक्रम सक्रिय हैं। कोई समाप्त कार्यक्रम नहीं मिला।');
    }

    // --- CONTACT INQUIRIES CMS ---
    public function contactInquiries(Request $request)
    {
        $query = ContactInquiry::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('subject', 'like', "%{$q}%")
                    ->orWhere('message', 'like', "%{$q}%");
            });
        }

        $inquiries = $query->paginate(20);
        $totalCount = ContactInquiry::count();
        $newCount = ContactInquiry::where('status', 'new')->count();
        $readCount = ContactInquiry::where('is_read', true)->count();

        return view('admin.contact-inquiries', compact('inquiries', 'totalCount', 'newCount', 'readCount'));
    }

    public function toggleContactInquiryStatus(Request $request, $id)
    {
        $inquiry = ContactInquiry::findOrFail($id);
        $newStatus = $inquiry->status === 'replied' ? 'read' : ($inquiry->status === 'new' ? 'read' : 'replied');
        $inquiry->update([
            'status' => $newStatus,
            'is_read' => true,
        ]);

        ActivityLog::log('Contact CMS', 'status_update', "Updated Inquiry #{$inquiry->id} status to {$newStatus}");

        return back()->with('success', "Inquiry #{$inquiry->id} marked as {$newStatus}.");
    }

    public function replyContactInquiry(Request $request, $id)
    {
        $inquiry = ContactInquiry::findOrFail($id);
        $validated = $request->validate([
            'reply_message' => 'required|string|min:5',
        ]);

        EmailNotificationService::sendContactInquiryReply($inquiry, $validated['reply_message']);

        $inquiry->update([
            'status' => 'replied',
            'is_read' => true,
            'notes' => ($inquiry->notes ? $inquiry->notes."\n\n" : '').'['.now()->format('d M Y, h:i A').'] Replied: '.$validated['reply_message'],
        ]);

        ActivityLog::log('Contact CMS', 'reply', "Sent email reply to inquiry #{$id} ({$inquiry->email})");

        return back()->with('success', 'आपके उत्तर का ईमेल भक्त ('.$inquiry->email.') को सफलतापूर्वक भेज दिया गया है।');
    }

    public function deleteContactInquiry(Request $request, $id)
    {
        $inquiry = ContactInquiry::findOrFail($id);
        $inquiry->delete();

        ActivityLog::log('Contact CMS', 'delete', "Deleted Inquiry #{$id} from {$inquiry->name}");

        return back()->with('success', 'संदेश सफलतापूर्वक हटा दिया गया। (Inquiry deleted successfully)');
    }

    // --- LEADERSHIP & TRUSTEES CMS ---
    public function leadership(Request $request)
    {
        $profiles = LeadershipProfile::orderBy('sort_order', 'asc')->get();
        $totalCount = $profiles->count();
        $patronCount = $profiles->where('role_category', 'peethadhishwar')->count();
        $trusteeCount = $profiles->where('role_category', 'trustee')->count();

        return view('admin.leadership', compact('profiles', 'totalCount', 'patronCount', 'trusteeCount'));
    }

    public function storeLeadership(Request $request)
    {
        $validated = $this->validateLeadershipRequest($request);

        $photoPath = $validated['photo_path'] ?? null;
        if ($request->hasFile('photo_file')) {
            $file = $request->file('photo_file');
            $ext = strtolower($file->guessExtension() ?: $file->getClientOriginalExtension());
            if (! in_array($ext, ['jpeg', 'jpg', 'png', 'webp', 'svg'], true)) {
                return back()->withErrors(['photo_file' => 'The photo must be an image file (PNG, JPG, WebP, SVG).']);
            }
            $filename = 'leader-'.time().'.'.$ext;
            $destinationDir = public_path('uploads');
            if (! File::isDirectory($destinationDir)) {
                File::makeDirectory($destinationDir, 0755, true, true);
            }
            $file->move($destinationDir, $filename);
            $photoPath = '/uploads/'.$filename;
        }

        $profile = LeadershipProfile::create([
            'name_hi' => $validated['name_hi'],
            'name_en' => $validated['name_en'] ?? $validated['name_hi'],
            'title_hi' => $validated['title_hi'],
            'title_en' => $validated['title_en'] ?? $validated['title_hi'],
            'role_category' => $validated['role_category'],
            'photo_path' => $photoPath,
            'bio_hi' => $validated['bio_hi'] ?? null,
            'bio_en' => $validated['bio_en'] ?? null,
            'social_links' => $this->buildSocialLinksPayload($request),
            'youtube_playlist_title' => $validated['youtube_playlist_title'] ?? null,
            'youtube_playlist_url' => $validated['youtube_playlist_url'] ?? null,
            'is_youtube_playlist_enabled' => $request->boolean('is_youtube_playlist_enabled'),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active', true),
        ]);

        ActivityLog::log('Leadership CMS', 'create', "Added leadership profile: {$profile->name_hi}");

        return redirect()->route('admin.leadership')->with('success', 'Leadership profile added successfully.');
    }

    public function updateLeadership(Request $request, $id)
    {
        $profile = LeadershipProfile::findOrFail($id);

        $validated = $this->validateLeadershipRequest($request);

        $photoPath = $profile->photo_path;
        if ($request->hasFile('photo_file')) {
            $file = $request->file('photo_file');
            $ext = strtolower($file->guessExtension() ?: $file->getClientOriginalExtension());
            if (! in_array($ext, ['jpeg', 'jpg', 'png', 'webp', 'svg'], true)) {
                return redirect()->route('admin.leadership')->withErrors(['photo_file' => 'The photo must be an image file (PNG, JPG, WebP, SVG).']);
            }
            $filename = 'leader-'.time().'.'.$ext;
            $destinationDir = public_path('uploads');
            if (! File::isDirectory($destinationDir)) {
                File::makeDirectory($destinationDir, 0755, true, true);
            }
            $file->move($destinationDir, $filename);
            $photoPath = '/uploads/'.$filename;
        } elseif ($request->filled('photo_path')) {
            $photoPath = $validated['photo_path'];
        }

        $profile->update([
            'name_hi' => $validated['name_hi'],
            'name_en' => $validated['name_en'] ?? $validated['name_hi'],
            'title_hi' => $validated['title_hi'],
            'title_en' => $validated['title_en'] ?? $validated['title_hi'],
            'role_category' => $validated['role_category'],
            'photo_path' => $photoPath,
            'bio_hi' => $validated['bio_hi'] ?? null,
            'bio_en' => $validated['bio_en'] ?? null,
            'social_links' => $this->buildSocialLinksPayload($request),
            'youtube_playlist_title' => $validated['youtube_playlist_title'] ?? null,
            'youtube_playlist_url' => $validated['youtube_playlist_url'] ?? null,
            'is_youtube_playlist_enabled' => $request->boolean('is_youtube_playlist_enabled'),
            'sort_order' => (int) ($validated['sort_order'] ?? $profile->sort_order),
            'is_active' => $request->boolean('is_active', true),
        ]);

        ActivityLog::log('Leadership CMS', 'update', "Updated leadership profile: {$profile->name_hi}");

        return redirect()->route('admin.leadership')->with('success', 'Leadership profile updated successfully.');
    }

    /**
     * Validate leadership profile create and update requests.
     *
     * @return array<string, mixed>
     */
    private function validateLeadershipRequest(Request $request): array
    {
        return $request->validate([
            'name_hi' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'title_hi' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'role_category' => 'required|string|in:peethadhishwar,trustee,committee,advisor,other',
            'photo_path' => 'nullable|string|max:500',
            'photo_file' => 'nullable|image|mimes:jpeg,jpg,png,webp,svg|max:10240',
            'bio_hi' => 'nullable|string',
            'bio_en' => 'nullable|string',
            'socials' => 'nullable|array',
            'socials.*.url' => 'nullable|url|max:500',
            'socials.*.enabled' => 'nullable',
            'youtube_playlist_title' => 'nullable|string|max:255',
            'youtube_playlist_url' => 'nullable|string|max:500',
            'is_youtube_playlist_enabled' => 'nullable',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);
    }

    /**
     * Normalize social links and per-platform enable flags from request input.
     *
     * @return array<string, array{enabled: bool, url: string}>
     */
    private function buildSocialLinksPayload(Request $request): array
    {
        $rawSocials = $request->input('socials', []);
        $payload = [];

        foreach (array_keys(LeadershipProfile::SOCIAL_PLATFORMS) as $platform) {
            $entry = is_array($rawSocials[$platform] ?? null) ? $rawSocials[$platform] : [];
            $url = trim((string) ($entry['url'] ?? ''));
            $enabled = filter_var($entry['enabled'] ?? false, FILTER_VALIDATE_BOOLEAN);

            $payload[$platform] = [
                'enabled' => $enabled,
                'url' => $url,
            ];
        }

        return $payload;
    }

    public function deleteLeadership(Request $request, $id)
    {
        $profile = LeadershipProfile::findOrFail($id);
        $name = $profile->name_hi;
        $profile->delete();

        ActivityLog::log('Leadership CMS', 'delete', "Deleted leadership profile: {$name}");

        return redirect()->route('admin.leadership')->with('success', "Leadership profile for {$name} deleted successfully.");
    }

    /**
     * Atithi Vishram (Pilgrim Guest House) Enquiries Management.
     */
    public function atithiVishram(Request $request)
    {
        $query = AtithiVishramEnquiry::query();

        if ($request->filled('q')) {
            $q = $request->query('q');
            $query->where(function ($sub) use ($q): void {
                $sub->where('devotee_name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('enquiry_number', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $totalCount = AtithiVishramEnquiry::count();
        $newCount = AtithiVishramEnquiry::where('status', 'new')->count();
        $confirmedCount = AtithiVishramEnquiry::where('status', 'confirmed')->count();
        $cancelledCount = AtithiVishramEnquiry::where('status', 'cancelled')->count();

        $enquiries = $query->latest('created_at')->paginate(20)->withQueryString();

        return view('admin.atithi-vishram', compact(
            'enquiries',
            'totalCount',
            'newCount',
            'confirmedCount',
            'cancelledCount'
        ));
    }

    /**
     * Update Atithi Vishram Enquiry Status & Admin Notes.
     */
    public function updateAtithiVishramStatus(Request $request, $id)
    {
        $enquiry = AtithiVishramEnquiry::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string|in:new,in_review,confirmed,cancelled,completed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $statusChanged = $enquiry->status !== $validated['status'];
        $enquiry->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? $enquiry->admin_notes,
            'is_read' => true,
        ]);

        ActivityLog::log(
            'Atithi Vishram CMS',
            'status_update',
            "Updated enquiry #{$enquiry->enquiry_number} status to {$validated['status']}"
        );

        if ($statusChanged && ! empty($enquiry->email)) {
            EmailNotificationService::sendAtithiVishramStatusUpdate($enquiry);
        }

        return redirect()->back()->with('success', "Enquiry #{$enquiry->enquiry_number} status updated to {$validated['status']}.");
    }

    /**
     * Delete an Atithi Vishram Enquiry.
     */
    public function deleteAtithiVishramEnquiry(Request $request, $id)
    {
        $enquiry = AtithiVishramEnquiry::findOrFail($id);
        $number = $enquiry->enquiry_number;
        $enquiry->delete();

        ActivityLog::log('Atithi Vishram CMS', 'delete', "Deleted guest house enquiry #{$number}");

        return redirect()->back()->with('success', "Enquiry #{$number} deleted successfully.");
    }

    /**
     * Temple Premises & Event Booking Enquiries Management.
     */
    public function templeBookings(Request $request)
    {
        $query = TempleBookingEnquiry::query();

        if ($request->filled('q')) {
            $q = $request->query('q');
            $query->where(function ($sub) use ($q): void {
                $sub->where('devotee_name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('enquiry_number', 'like', "%{$q}%")
                    ->orWhere('event_type', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->query('event_type'));
        }

        $totalCount = TempleBookingEnquiry::count();
        $newCount = TempleBookingEnquiry::where('status', 'new')->count();
        $approvedCount = TempleBookingEnquiry::where('status', 'approved')->count();
        $rejectedCount = TempleBookingEnquiry::where('status', 'rejected')->count();

        $enquiries = $query->latest('created_at')->paginate(20)->withQueryString();

        return view('admin.temple-bookings', compact(
            'enquiries',
            'totalCount',
            'newCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    /**
     * Update Temple Booking Enquiry Status & Admin Notes.
     */
    public function updateTempleBookingStatus(Request $request, $id)
    {
        $enquiry = TempleBookingEnquiry::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string|in:new,contacted,approved,rejected,completed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $statusChanged = $enquiry->status !== $validated['status'];
        $enquiry->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? $enquiry->admin_notes,
            'is_read' => true,
        ]);

        ActivityLog::log(
            'Temple Booking CMS',
            'status_update',
            "Updated booking enquiry #{$enquiry->enquiry_number} status to {$validated['status']}"
        );

        if ($statusChanged && ! empty($enquiry->email)) {
            EmailNotificationService::sendTempleBookingStatusUpdate($enquiry);
        }

        return redirect()->back()->with('success', "Booking enquiry #{$enquiry->enquiry_number} status updated to {$validated['status']}.");
    }

    /**
     * Delete a Temple Booking Enquiry.
     */
    public function deleteTempleBookingEnquiry(Request $request, $id)
    {
        $enquiry = TempleBookingEnquiry::findOrFail($id);
        $number = $enquiry->enquiry_number;
        $enquiry->delete();

        ActivityLog::log('Temple Booking CMS', 'delete', "Deleted temple booking enquiry #{$number}");

        return redirect()->back()->with('success', "Booking enquiry #{$number} deleted successfully.");
    }

    /**
     * Strip dangerous script elements, event handlers, and javascript: URIs from uploaded SVGs.
     */
    private function sanitizeSvgFile(string $path): void
    {
        if (! File::exists($path)) {
            return;
        }

        $svg = File::get($path);
        // Remove <script>, <foreignObject>, and XML processing instructions that could execute scripts
        $svg = preg_replace('/<\s*(script|foreignObject)\b[^>]*>.*?<\s*\/\s*\1\s*>/is', '', $svg) ?? $svg;
        $svg = preg_replace('/<\s*(script|foreignObject)\b[^>]*\/?\s*>/is', '', $svg) ?? $svg;
        // Strip inline event handlers (onload, onerror, onclick, etc.)
        $svg = preg_replace('/\s+on[a-z]+\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/is', '', $svg) ?? $svg;
        // Strip javascript: and data:text/html URIs in href/xlink:href/src
        $svg = preg_replace('/\b(xlink:href|href|src)\s*=\s*(?:"\s*(?:javascript|vbscript|data:text\/html)[^"]*"|\'\s*(?:javascript|vbscript|data:text\/html)[^\']*\')/is', '', $svg) ?? $svg;

        File::put($path, $svg);
    }
}
