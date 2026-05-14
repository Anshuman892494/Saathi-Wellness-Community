<?php

namespace App\Http\Controllers;

use App\Services\GroqService;
use Illuminate\Http\Request;

class AiController extends Controller
{
    protected $groq;

    public function __construct(GroqService $groq)
    {
        $this->groq = $groq;
    }

    /**
     * Show the Nutrition Assistant page.
     */
    public function nutritionIndex()
    {
        return view('ai.nutrition');
    }

    /**
     * Analyze food description for nutrition via Groq (Llama 3.1).
     */
    public function analyzeNutrition(Request $request)
    {
        $request->validate([
            'food' => 'required|string|max:500',
        ]);

        $analysis = $this->groq->analyzeNutrition($request->food);

        return response()->json([
            'analysis' => $analysis,
        ]);
    }
}
