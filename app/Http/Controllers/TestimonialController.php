<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function create(): View
    {
        return view('testimonials.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'quote' => ['required', 'string', 'max:500'],
        ]);

        Testimonial::create([
            'name' => $data['name'],
            'role' => $data['role'],
            'quote' => $data['quote'],
            'avatar' => null,
            'is_active' => false,
            'order_index' => 0,
        ]);

        return redirect()
            ->to(route('home') . '#testimonials')
            ->with('success', 'Terima kasih! Testimoni kamu sudah terkirim dan akan tampil setelah ditinjau admin.');
    }
}

