@extends('layouts.app')
@section('title', 'Fitness Guide')

@section('content')
<div class="page-hero">
    <div class="container">
        <a href="{{ route('resources.index') }}" class="d-inline-flex align-items-center gap-1 mb-3 text-muted text-decoration-none" style="font-size:.875rem">
            <i class="bi bi-arrow-left"></i> Wellness Hub
        </a>
        <h1 class="mb-2"><i class="bi bi-activity text-danger me-2"></i>Fitness Guide</h1>
        <p class="lead">Build strength, endurance, and vitality — at any fitness level</p>
    </div>
</div>

<div class="container py-4">

    {{-- Level pills --}}
    <div class="d-flex gap-2 mb-4 flex-wrap">
        <span class="tag-pill" style="border-color:var(--brand-green);color:var(--brand-green)"><i class="bi bi-circle-fill me-1"></i> Beginner</span>
        <span class="tag-pill" style="border-color:#ffd60a;color:#ffd60a"><i class="bi bi-circle-fill me-1"></i> Intermediate</span>
        <span class="tag-pill" style="border-color:#e85d73;color:#e85d73"><i class="bi bi-circle-fill me-1"></i> Advanced</span>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">

            @php
            $routines = [
                ['level'=>'Beginner','color'=>'var(--brand-green)','icon'=>'bi-walking','title'=>'30-Day Walking Plan',
                 'desc'=>'The simplest, most sustainable fitness habit. Start with 15 min/day in Week 1 and build to 45 min brisk walks by Week 4. Walk outdoors when possible for added Vitamin D benefits.',
                 'exercises'=>['Week 1: 15 min leisurely walk, daily','Week 2: 20 min moderate walk, 5×/week','Week 3: 30 min brisk walk, 5×/week','Week 4: 45 min brisk walk with hills, 5×/week']],
                ['level'=>'Beginner','color'=>'var(--brand-green)','icon'=>'bi-person-bounding-box','title'=>'Bodyweight Basics',
                 'desc'=>'No gym needed. These foundational exercises build total-body strength using only your bodyweight.',
                 'exercises'=>['10 Push-ups (knees OK) × 3 sets','15 Squats × 3 sets','20 Glute bridges × 3 sets','30-sec Plank × 3 sets','10 Lunges each side × 3 sets']],
                ['level'=>'Intermediate','color'=>'#ffd60a','icon'=>'bi-lightning-charge','title'=>'HIIT Circuit (20 min)',
                 'desc'=>'High-Intensity Interval Training burns more calories in less time. Perform each exercise for 40 seconds, rest 20 seconds.',
                 'exercises'=>['Jumping Jacks','Burpees','Mountain Climbers','Jump Squats','Push-up to T-Rotation','High Knees — Repeat 3 rounds']],
                ['level'=>'Advanced','color'=>'#e85d73','icon'=>'bi-fire','title'=>'5-Day Strength Split',
                 'desc'=>'Progressive overload programme for building lean muscle. Rest 48h before training the same muscle group again.',
                 'exercises'=>['Monday: Chest + Triceps','Tuesday: Back + Biceps','Wednesday: Active Rest (yoga/walk)','Thursday: Shoulders + Core','Friday: Legs (squats, deadlifts, lunges)']],
            ];
            @endphp

            @foreach($routines as $r)
            <div class="card-wellness p-4 mb-3">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span style="font-size:1.5rem"><i class="bi {{ $r['icon'] }} text-brand"></i></span>
                    <h5 class="mb-0 fw-700">{{ $r['title'] }}</h5>
                    <span class="ms-auto tag-pill" style="border-color:{{ $r['color'] }};color:{{ $r['color'] }}">{{ $r['level'] }}</span>
                </div>
                <p class="text-muted small mb-3">{{ $r['desc'] }}</p>
                <ul class="mb-0 ps-3" style="font-size:.9rem;color:var(--text-primary)">
                    @foreach($r['exercises'] as $ex)
                        <li class="mb-1">{{ $ex }}</li>
                    @endforeach
                </ul>
            </div>
            @endforeach

        </div>

        <div class="col-lg-4">
            <div class="sidebar-card">
                <h6>Fitness Principles</h6>
                @foreach(['Progressive overload — increase challenge weekly','Consistency beats intensity','Rest days are growth days','Warm up before; cool down after','Track your workouts','Pair movement with good nutrition','Sleep 7–9h for maximum recovery'] as $p)
                <div class="d-flex align-items-start gap-2 py-1" style="border-bottom:1px solid var(--border-color);font-size:.85rem">
                    <i class="bi bi-lightning-charge-fill mt-1" style="color:#ffd60a;flex-shrink:0"></i>
                    <span class="text-muted">{{ $p }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
