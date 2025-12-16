<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $latestFeedback = Feedback::latest('created_at')->limit(5)->get();
        
        return view('welcome', [
            'latestFeedback' => $latestFeedback,
        ]);
    }
}