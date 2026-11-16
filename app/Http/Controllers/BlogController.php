<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::where('status', 'published')->latest();

        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $featuredPost = (clone $query)->first();
        $posts = $query->paginate(9);
        $categories = Post::where('status', 'published')->distinct()->pluck('category');

        return view('pages.blog.index', compact('posts', 'featuredPost', 'categories'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->where('status', 'published')->firstOrFail();
        $post->increment('views_count');

        // Only display moderator-approved comments on public articles
        $comments = $post->comments()->where('is_approved', true)->latest()->get();
        $relatedPosts = Post::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->where('category', $post->category)
            ->take(3)
            ->get();

        return view('pages.blog.show', compact('post', 'comments', 'relatedPosts'));
    }

    public function like($id)
    {
        $post = Post::findOrFail($id);

        $sessionKey = "liked_post_{$id}";
        if (session()->has($sessionKey)) {
            return response()->json([
                'success' => true,
                'already_liked' => true,
                'likes_count' => $post->likes_count,
            ]);
        }

        $post->increment('likes_count');
        session()->put($sessionKey, true);

        return response()->json([
            'success' => true,
            'likes_count' => $post->likes_count,
        ]);
    }

    public function comment(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:150',
            'comment' => 'required|string|max:1000',
        ]);

        $post = Post::findOrFail($id);

        // Submissions require moderation approval before becoming publicly visible
        PostComment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'name' => $request->name,
            'email' => $request->email,
            'comment' => $request->comment,
            'is_approved' => false,
        ]);

        return redirect()->back()->with('success', 'आपका विचार सफलतापूर्वक दर्ज कर लिया गया है। व्यवस्थापक द्वारा अनुमोदन के उपरांत यह प्रकाशित होगा। (Comment submitted for moderation.)');
    }
}
