<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * ResourceController — static wellness resource pages.
 */
class ResourceController extends Controller
{
    /** Wellness resources hub landing page. */
    public function index()
    {
        return view('resources.index');
    }

    /** Health tips page. */
    public function healthTips()
    {
        return view('resources.health-tips');
    }

    /** Meditation guides page. */
    public function meditation()
    {
        return view('resources.meditation');
    }

    /** Fitness suggestions page. */
    public function fitness()
    {
        return view('resources.fitness');
    }

    /** Nutrition guides page. */
    public function nutrition()
    {
        return view('resources.nutrition');
    }
}
