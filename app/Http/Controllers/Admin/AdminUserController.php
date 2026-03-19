<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumLike;
use App\Models\ForumPost;
use App\Models\ForumReply;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    protected string $adminEmail = 'admin@pedulilingkungan.id';

    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();
        $search = $request->string('q')->toString();
        $roleFilter = $request->string('role')->toString();

        $query = User::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status === 'active') {
            $query->where('is_banned', false);
        } elseif ($status === 'banned') {
            $query->where('is_banned', true);
        }

        if (in_array($roleFilter, ['admin', 'user'], true)) {
            $query->where('role', $roleFilter);
        }

        $users = $query->withCount([
            'forumPosts',
            'forumReplies',
        ])->paginate(15)->withQueryString();

        $stats = [
            'total' => User::count(),
            'active' => User::where('is_banned', false)->count(),
            'banned' => User::where('is_banned', true)->count(),
        ];

        return view('admin.users.index', compact('users', 'status', 'search', 'roleFilter', 'stats'));
    }

    public function show(User $user): View
    {
        $posts = ForumPost::where('user_id', $user->id)
            ->latest()
            ->withCount('replies')
            ->get();

        $stats = [
            'posts' => $posts->count(),
            'replies' => ForumReply::where('user_id', $user->id)->count(),
            'likes_received' => ForumLike::where(function ($q) use ($user) {
                $q->where('likeable_type', ForumPost::class)
                    ->whereIn('likeable_id', ForumPost::where('user_id', $user->id)->pluck('id'));
            })->orWhere(function ($q) use ($user) {
                $q->where('likeable_type', ForumReply::class)
                    ->whereIn('likeable_id', ForumReply::where('user_id', $user->id)->pluck('id'));
            })->count(),
        ];

        return view('admin.users.show', compact('user', 'posts', 'stats'));
    }

    public function ban(User $user): RedirectResponse
    {
        if ($user->email === $this->adminEmail) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun admin utama.');
        }

        $user->update(['is_banned' => true]);

        return back()->with('success', 'User berhasil dinonaktifkan.');
    }

    public function unban(User $user): RedirectResponse
    {
        $user->update(['is_banned' => false]);

        return back()->with('success', 'User berhasil diaktifkan kembali.');
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $request->validate(['role' => ['required', 'in:admin,user']]);

        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Kamu tidak dapat mengubah role akun sendiri.');
        }

        $user->update(['role' => $request->string('role')->toString()]);

        $label = $request->input('role') === 'admin' ? 'Admin' : 'User';

        return back()->with('success', "Role berhasil diubah menjadi {$label}.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Kamu tidak dapat menghapus akun sendiri.');
        }

        if ($user->email === $this->adminEmail) {
            return back()->with('error', 'Tidak dapat menghapus akun admin utama.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}

