@extends('layouts.app')
@section('title', 'Nutrition Guide')

@section('content')
<div class="page-hero">
    <div class="container">
        <a href="{{ route('resources.index') }}" class="d-inline-flex align-items-center gap-1 mb-3 text-muted text-decoration-none" style="font-size:.875rem">
            <i class="bi bi-arrow-left"></i> Wellness Hub
        </a>
        <h1 class="mb-2">🥗 Nutrition Guide</h1>
        <p class="lead">Fuel your body with knowledge and nourishing food choices</p>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-8">

            {{-- Macros section --}}
            <h5 class="fw-700 mb-3">Understanding Macronutrients</h5>
            <div class="row g-3 mb-4">
                @foreach([
                    ['🍗','Protein','Builds and repairs muscle tissue. Essential for immune function and enzyme production. Aim for 0.8–1.6g per kg of body weight daily. Best sources: chicken, fish, legumes, tofu, eggs, Greek yoghurt.','#e85d73'],
                    ['🌾','Carbohydrates','Your body\'s primary fuel source. Prefer complex carbs (oats, brown rice, sweet potato, vegetables) over refined sugars. Carbs are not the enemy — quality matters.','#ffd60a'],
                    ['🥑','Healthy Fats','Critical for hormone production, brain health, and vitamin absorption. Include olive oil, avocado, nuts, seeds, and fatty fish. Limit trans fats and excessive saturated fat.','var(--brand-teal)'],
                ] as $m)
                <div class="col-md-4">
                    <div class="card-wellness p-3 h-100 text-center">
                        <span style="font-size:2rem">{{ $m[0] }}</span>
                        <h6 class="fw-700 mt-2 mb-1" style="color:{{ $m[3] }}">{{ $m[1] }}</h6>
                        <p class="text-muted small mb-0">{{ $m[2] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Eating strategies --}}
            <h5 class="fw-700 mb-3">Smart Eating Strategies</h5>
            @php
            $strategies = [
                ['🍽️','Eat Mindfully','Slow down. Chew each bite 20–30 times. Put your fork down between bites. It takes 20 minutes for fullness signals to reach the brain — eating slowly prevents overeating.'],
                ['📅','Meal Prep on Sundays','Cooking in batches removes the daily decision fatigue around food. Having nutritious meals ready means you are far less likely to reach for processed food when hungry.'],
                ['🚫','Minimise Ultra-Processed Foods','Foods with more than 5 artificial ingredients are ultra-processed. They are engineered to override satiety signals. Cook whole foods at home as often as possible.'],
                ['🌈','The 80/20 Rule','Eat nutritiously 80% of the time and allow yourself flexibility 20% of the time. This prevents restrictive thinking and is sustainable long-term.'],
                ['⏰','Consider Time-Restricted Eating','Eating within a 10–12 hour window (e.g. 8am–8pm) allows your gut to rest and has been linked to improved metabolic health, sleep, and digestion.'],
            ];
            @endphp
            @foreach($strategies as $s)
            <div class="tip-card d-flex gap-3">
                <span style="font-size:1.5rem;line-height:1">{{ $s[0] }}</span>
                <div>
                    <h6 class="fw-700 mb-1">{{ $s[1] }}</h6>
                    <p class="text-muted small mb-0">{{ $s[2] }}</p>
                </div>
            </div>
            @endforeach

        </div>

        <div class="col-lg-4">
            <div class="sidebar-card mb-3">
                <h6>Daily Plate Guide</h6>
                @foreach([
                    ['🥦','Vegetables','½ of your plate'],
                    ['🍗','Lean Protein','¼ of your plate'],
                    ['🌾','Complex Carbs','¼ of your plate'],
                    ['💧','Water','6–8 glasses/day'],
                    ['🥑','Healthy Fat','1–2 servings/day'],
                ] as $p)
                <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid var(--border-color);font-size:.85rem">
                    <span>{{ $p[0] }} {{ $p[1] }}</span>
                    <span class="text-muted">{{ $p[2] }}</span>
                </div>
                @endforeach
            </div>

            <div class="sidebar-card">
                <h6>Foods to Prioritise</h6>
                <p class="text-muted small">
                    Leafy greens • Berries • Wild salmon • Nuts & seeds • Legumes •
                    Whole grains • Olive oil • Fermented foods (yoghurt, kefir, kimchi) •
                    Green tea • Dark chocolate (70%+)
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
