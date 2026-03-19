<?php

namespace App\Http\Controllers;

use App\Models\ForumPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UserProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();

        $totalPosts = $user->forumPosts()->count();
        $totalReplies = $user->forumReplies()->count();
        $totalLikesReceived = $user->forumPosts()->withCount('likes')->get()->sum('likes_count');

        $posts = $user->forumPosts()
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $tab = $request->get('tab', request()->old('tab', 'posts'));

        return view('profile.show', [
            'user' => $user,
            'posts' => $posts,
            'totalPosts' => $totalPosts,
            'totalReplies' => $totalReplies,
            'totalLikesReceived' => $totalLikesReceived,
            'tab' => $tab,
        ]);
    }

    public function edit(Request $request): RedirectResponse
    {
        return redirect()->route('profile', ['tab' => 'settings']);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:500'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        $request->user()->update($data);

        return redirect()->route('profile', ['tab' => 'settings'])
            ->with('status', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('profile', ['tab' => 'settings'])
            ->with('status', 'Password berhasil diubah.');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
        ]);

        $user = $request->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return redirect()->route('profile')
            ->with('status', 'Foto profil berhasil diubah.');
    }
}
