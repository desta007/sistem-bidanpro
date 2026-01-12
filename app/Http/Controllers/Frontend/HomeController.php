<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Queue;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('frontend.pages.home');
    }

    public function services()
    {
        $services = Service::where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        return view('frontend.pages.services', compact('services'));
    }

    public function about()
    {
        return view('frontend.pages.about');
    }

    public function queue()
    {
        $queues = Queue::with('patient')
            ->whereDate('queue_date', today())
            ->whereIn('status', ['waiting', 'called', 'examining'])
            ->orderBy('queue_number')
            ->get();

        $currentNumber = $queues->where('status', 'called')->first()?->queue_number
            ?? $queues->where('status', 'examining')->first()?->queue_number
            ?? 0;

        $waitingCount = $queues->where('status', 'waiting')->count();

        return view('frontend.pages.queue', compact('queues', 'currentNumber', 'waitingCount'));
    }
}
