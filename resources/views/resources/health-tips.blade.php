@extends('layouts.app')
@section('title', 'Health Tips')

@section('content')
<div class="page-hero">
    <div class="container">
        <a href="{{ route('resources.index') }}" class="d-inline-flex align-items-center gap-1 mb-3 text-muted text-decoration-none" style="font-size:.875rem">
            <i class="bi bi-arrow-left"></i> Wellness Hub
        </a>
        <h1 class="mb-2"><i class="bi bi-lightbulb-fill text-brand me-2"></i>Daily Health Tips</h1>
        <p class="lead">Small consistent habits that lead to extraordinary long-term health</p>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-8">

            @php
            $tips = [
                ['icon'=>'bi-droplet','title'=>'Hydrate First Thing','body'=>'Drink a glass of water immediately upon waking. Your body is dehydrated after 6–8 hours of sleep. Rehydrating first thing kick-starts your metabolism, flushes toxins, and improves mental clarity for the day ahead.'],
                ['icon'=>'bi-moon-stars','title'=>'Prioritize Sleep Quality','body'=>'Aim for 7–9 hours of sleep per night. Keep a consistent sleep schedule — even on weekends. Use blackout curtains, keep the room cool (16–19°C), and avoid screens 60 minutes before bed. Quality sleep is the single most powerful recovery tool available to you.'],
                ['icon'=>'bi-walking','title'=>'Move Every Hour','body'=>'Sitting for prolonged periods increases the risk of cardiovascular disease. Set a timer to stand, stretch, or walk for 2–5 minutes every hour. Even light movement improves circulation and reduces inflammation markers.'],
                ['icon'=>'bi-person-walking','title'=>'Practice Deep Breathing','body'=>'Stress is the root cause of many chronic diseases. Practice box breathing: inhale 4 seconds, hold 4, exhale 4, hold 4. Do this 5 times when stressed. It activates the parasympathetic nervous system and lowers cortisol in minutes.'],
                ['icon'=>'bi-egg-fried','title'=>'Eat the Rainbow','body'=>'Different coloured fruits and vegetables provide different phytonutrients and antioxidants. Aim to have at least 3 different colours of vegetables at each meal. This maximises the variety of nutrients your body receives.'],
                ['icon'=>'bi-brightness-high','title'=>'Get Morning Sunlight','body'=>'Exposure to natural light within 30–60 minutes of waking sets your circadian rhythm, boosts serotonin, and dramatically improves the quality of your sleep at night. Even 5–10 minutes outside is highly beneficial.'],
                ['icon'=>'bi-people','title'=>'Nurture Social Connections','body'=>'Research shows that loneliness is as damaging to health as smoking 15 cigarettes a day. Regular meaningful social contact reduces inflammation, improves immune function, and increases longevity. Invest in your relationships.'],
                ['icon'=>'bi-phone-mute','title'=>'Reduce Screen Time','body'=>'Limit recreational screen time to under 2 hours per day where possible. Use screen-time tracking apps to monitor usage. Replace scrolling with activities that involve real-world engagement, creativity, or physical activity.'],
            ];
            @endphp

            @foreach($tips as $i => $tip)
            <div class="tip-card d-flex gap-3 align-items-start">
                <div class="tip-number">{{ $i + 1 }}</div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span><i class="bi {{ $tip['icon'] }} text-brand"></i></span>
                        <h6 class="mb-0 fw-700">{{ $tip['title'] }}</h6>
                    </div>
                    <p class="text-muted small mb-0">{{ $tip['body'] }}</p>
                </div>
            </div>
            @endforeach

        </div>

        <div class="col-lg-4">
            <div class="sidebar-card">
                <h6>Quick Reference</h6>
                <ul class="list-unstyled" style="font-size:.85rem">
                    <li class="py-1" style="border-bottom:1px solid var(--border-color)"><i class="bi bi-droplet me-2"></i> 8 glasses water daily</li>
                    <li class="py-1" style="border-bottom:1px solid var(--border-color)"><i class="bi bi-moon me-2"></i> 7–9 hours sleep</li>
                    <li class="py-1" style="border-bottom:1px solid var(--border-color)"><i class="bi bi-walking me-2"></i> 8,000+ steps / day</li>
                    <li class="py-1" style="border-bottom:1px solid var(--border-color)"><i class="bi bi-egg me-2"></i> 5+ fruit & veg servings</li>
                    <li class="py-1"><i class="bi bi-person-walking me-2"></i> 10 min mindfulness</li>
                </ul>
            </div>
            <div class="sidebar-card mt-3">
                <h6>More Resources</h6>
                <a href="{{ route('resources.meditation') }}" class="d-block text-decoration-none py-1" style="font-size:.875rem;color:var(--text-muted)">🧘 Meditation Guide →</a>
                <a href="{{ route('resources.fitness') }}" class="d-block text-decoration-none py-1" style="font-size:.875rem;color:var(--text-muted)">🏋️ Fitness Routines →</a>
                <a href="{{ route('resources.nutrition') }}" class="d-block text-decoration-none py-1" style="font-size:.875rem;color:var(--text-muted)">🥗 Nutrition Guide →</a>
            </div>
        </div>
    </div>
</div>
@endsection
