<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display landing page.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'counselor');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $counselors = $query->get();

        return view('landing.index', compact('counselors'));
    }
}
