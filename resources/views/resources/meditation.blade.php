@extends('layouts.app')
@section('title', 'Meditation Guides')

@section('content')
<div class="page-hero">
    <div class="container">
        <a href="{{ route('resources.index') }}" class="d-inline-flex align-items-center gap-1 mb-3 text-muted text-decoration-none" style="font-size:.875rem">
            <i class="bi bi-arrow-left"></i> Wellness Hub
        </a>
        <h1 class="mb-2">🧘 Meditation Guides</h1>
        <p class="lead">Cultivate inner peace and mental clarity through mindful practice</p>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-8">

            @php
            $guides = [
                [
                    'title' => '5-Minute Breathing Meditation',
                    'level' => 'Beginner',
                    'duration' => '5 min',
                    'icon' => '🌬️',
                    'steps' => [
                        'Find a comfortable seated position. Close your eyes.',
                        'Take a slow breath in through your nose for 4 counts.',
                        'Hold your breath gently for 2 counts.',
                        'Exhale slowly through your mouth for 6 counts.',
                        'Repeat this cycle for 5 minutes. When thoughts arise, gently return focus to your breath.',
                    ]
                ],
                [
                    'title' => 'Body Scan Relaxation',
                    'level' => 'Beginner',
                    'duration' => '15 min',
                    'icon' => '🌊',
                    'steps' => [
                        'Lie down in a comfortable position and close your eyes.',
                        'Start at the top of your head — notice any sensations without judgment.',
                        'Slowly move your attention down: face, neck, shoulders, arms, chest, stomach, hips, legs, feet.',
                        'Breathe into any area of tension. Visualise tension melting away with each exhale.',
                        'Once you reach your feet, take 3 deep breaths and slowly open your eyes.',
                    ]
                ],
                [
                    'title' => 'Loving-Kindness (Metta) Meditation',
                    'level' => 'Intermediate',
                    'duration' => '10 min',
                    'icon' => '💚',
                    'steps' => [
                        'Sit comfortably and close your eyes. Take 3 deep breaths.',
                        'Picture yourself. Repeat silently: "May I be happy. May I be healthy. May I be at peace."',
                        'Now bring to mind someone you love. Send them the same wishes.',
                        'Extend this to a neutral person, then to someone you find difficult.',
                        'Finally, expand your circle of compassion to all living beings everywhere.',
                    ]
                ],
                [
                    'title' => 'Mindful Walking',
                    'level' => 'Beginner',
                    'duration' => '10–20 min',
                    'icon' => '🌿',
                    'steps' => [
                        'Choose a quiet path — indoors or outdoors.',
                        'Walk at a slower-than-normal pace. Feel each foot as it lifts and lands.',
                        'Engage all your senses: What do you see? Hear? Smell? Feel?',
                        'If your mind wanders, gently bring attention back to the sensation of walking.',
                        'Finish by standing still for 1 minute, breathing deeply and expressing gratitude.',
                    ]
                ],
            ];
            @endphp

            @foreach($guides as $guide)
            <div class="card-wellness p-4 mb-3">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span style="font-size:2rem">{{ $guide['icon'] }}</span>
                    <div>
                        <h5 class="mb-0 fw-700">{{ $guide['title'] }}</h5>
                        <div class="d-flex gap-2 mt-1">
                            <span class="tag-pill">{{ $guide['level'] }}</span>
                            <span class="tag-pill"><i class="bi bi-clock me-1"></i>{{ $guide['duration'] }}</span>
                        </div>
                    </div>
                </div>
                <ol class="ps-3 mb-0" style="font-size:.9rem;color:var(--text-muted)">
                    @foreach($guide['steps'] as $step)
                        <li class="mb-2">{{ $step }}</li>
                    @endforeach
                </ol>
            </div>
            @endforeach

        </div>

        <div class="col-lg-4">
            <div class="sidebar-card">
                <h6>Benefits of Meditation</h6>
                @foreach(['Reduces stress & anxiety','Improves focus & memory','Better sleep quality','Lowers blood pressure','Increases self-awareness','Builds emotional resilience','Reduces pain perception'] as $b)
                <div class="d-flex align-items-center gap-2 py-1" style="border-bottom:1px solid var(--border-color);font-size:.85rem">
                    <i class="bi bi-check-circle-fill" style="color:var(--brand-green)"></i>{{ $b }}
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
