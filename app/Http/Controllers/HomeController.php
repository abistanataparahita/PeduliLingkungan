<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Banner;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::query()
            ->where('is_active', true)
            ->orderBy('order_index')
            ->take(4)
            ->get();

        $bannerMeta = $banners->map(function (Banner $banner) {
            return [
                // Tampilkan kartu kanan untuk banner default, atau custom yang punya konten teks
                'show_card' => (bool) $banner->is_default
                    || (bool) $banner->title
                    || (bool) $banner->subtitle
                    || (bool) $banner->button_text,
            ];
        });

        $events = Event::query()
            ->where('is_active', true)
            ->whereDate('event_date', '>=', now()->toDateString())
            ->orderByDesc('is_featured')
            ->orderBy('event_date')
            ->take(3)
            ->get();

        $testimonials = Testimonial::query()
            ->where('is_active', true)
            ->orderBy('order_index')
            ->take(3)
            ->get();

        $galleries = Gallery::query()
            ->where('is_featured', true)
            ->orderBy('order_index')
            ->orderByDesc('activity_date')
            ->take(6)
            ->get();

        $articles = Article::query()
            ->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        $popupEvent = Event::query()
            ->where('has_popup', true)
            ->where('is_active', true)
            ->whereDate('event_date', '>=', now()->toDateString())
            ->orderBy('event_date')
            ->first();

        $navbarEvent = Event::where('show_in_navbar', true)
            ->where('is_active', true)
            ->first();

        $products = Product::query()
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        return view('home', [
            'banners' => $banners,
            'bannerMeta' => $bannerMeta,
            'events' => $events,
            'testimonials' => $testimonials,
            'galleries' => $galleries,
            'articles' => $articles,
            'popupEvent' => $popupEvent,
            'navbarEvent' => $navbarEvent,
            'products' => $products,
        ]);
    }
}

