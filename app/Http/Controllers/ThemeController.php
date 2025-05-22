<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function toggle(Request $request)
    {
        $currentTheme = session('theme', 'light');
        $newTheme = $currentTheme === 'light' ? 'dark' : 'light';
        
        session(['theme' => $newTheme]);
        
        // Return JSON response for AJAX requests
        if ($request->ajax()) {
            return response()->json(['theme' => $newTheme]);
        }
        
        return redirect()->back();
    }
} 