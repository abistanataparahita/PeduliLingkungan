<?php

namespace App\Http\Controllers;

use App\Http\Middleware\EnsureUserNotBanned;
use App\Models\ForumLike;
use App\Models\ForumPost;
use App\Models\ForumReply;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ForumController extends Controller
{
    public function index(Request $request): View
    {
        $query = ForumPost::with('user')
            ->latest();

        if ($category = $request->string('category')->toString()) {
            $query->where('category', $category);
        }

        if ($search = $request->string('q')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(10)->withQueryString();

        return view('forum.index', compact('posts', 'category', 'search'));
    }

    public function show(ForumPost $post): View
    {
        $post->increment('views');

        $post->load([
            'user',
            'replies.user',
            'replies.children.user',
        ]);

        $user = Auth::user();
        $liked = false;

        if ($user) {
            $liked = $post->likes()->where('user_id', $user->id)->exists();
        }

        return view('forum.show', compact('post', 'liked'));
    }

    public function create(): View
    {
        return view('forum.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
        ]);

        $user = $request->user();

        $slug = Str::slug($data['title']);
        if (ForumPost::where('slug', $slug)->exists()) {
            $slug .= '-' . Str::random(6);
        }

        $body = strip_tags($data['body'], '<p><br><strong><em><ul><ol><li><a>');

        $post = new ForumPost();
        $post->fill([
            'user_id' => $user->id,
            'title' => $data['title'],
            'slug' => $slug,
            'body' => $body,
            'category' => $data['category'] ?? null,
            'location' => $data['location'] ?? null,
        ]);

        if ($request->hasFile('image')) {
            $post->image = $request->file('image')->store('forum/posts', 'public');
        }

        $post->save();

        return redirect()->route('forum.show', $post)->with('success', 'Diskusi berhasil dibuat.');
    }

    public function reply(Request $request, ForumPost $post): RedirectResponse
    {
        if ($post->status === 'closed') {
            return back()->with('error', 'Diskusi ini sudah ditutup.');
        }

        $data = $request->validate([
            'body' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
            'parent_id' => ['nullable', 'integer', 'exists:forum_replies,id'],
        ]);

        $user = $request->user();

        $body = strip_tags($data['body'], '<p><br><strong><em><ul><ol><li><a>');

        $reply = new ForumReply();
        $reply->fill([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'body' => $body,
            'parent_id' => $data['parent_id'] ?? null,
            'is_admin_reply' => $user->can('admin'),
        ]);

        if ($request->hasFile('image')) {
            $reply->image = $request->file('image')->store('forum/replies', 'public');
        }

        $reply->save();

        return back()->with('success', 'Balasan terkirim.');
    }

    public function like(Request $request, ForumPost $post): RedirectResponse
    {
        $user = $request->user();

        $likeable = $post;

        if ($replyId = $request->input('reply_id')) {
            $likeable = ForumReply::findOrFail($replyId);
        }

        $existing = ForumLike::where('user_id', $user->id)
            ->where('likeable_id', $likeable->id)
            ->where('likeable_type', get_class($likeable))
            ->first();

        if ($existing) {
            $existing->delete();
        } else {
            ForumLike::create([
                'user_id' => $user->id,
                'likeable_id' => $likeable->id,
                'likeable_type' => get_class($likeable),
            ]);
        }

        return back();
    }

    public function destroy(Request $request, ForumPost $post): RedirectResponse
    {
        $user = $request->user();

        if ($post->user_id !== $user->id) {
            abort(403);
        }

        $post->delete();

        return redirect()->route('forum.index')->with('success', 'Post berhasil dihapus.');
    }
}

