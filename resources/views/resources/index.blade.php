@extends('layouts.app')
@section('title', 'Wellness Hub')
@section('meta_description', 'Explore health tips, meditation guides, fitness advice, and nutrition resources')

@section('content')
<div class="page-hero">
    <div class="container text-center">
        <h1 class="mb-2"><i class="bi bi-flower1 text-brand me-2"></i>Wellness Hub</h1>
        <p class="lead">Your curated library for health, mindfulness, and holistic well-being</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">

        <div class="col-md-6 col-lg-3">
            <a href="{{ route('resources.health-tips') }}" class="text-decoration-none">
                <div class="resource-card">
                    <span class="resource-icon text-brand"><i class="bi bi-lightbulb"></i></span>
                    <h5 class="fw-700 mb-2">Health Tips</h5>
                    <p class="text-muted small mb-0">Evidence-based daily habits for a longer, healthier life.</p>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-3">
            <a href="{{ route('resources.meditation') }}" class="text-decoration-none">
                <div class="resource-card">
                    <span class="resource-icon text-info"><i class="bi bi-person-walking"></i></span>
                    <h5 class="fw-700 mb-2">Meditation</h5>
                    <p class="text-muted small mb-0">Guided mindfulness practices to calm your mind and reduce stress.</p>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-3">
            <a href="{{ route('resources.fitness') }}" class="text-decoration-none">
                <div class="resource-card">
                    <span class="resource-icon text-danger"><i class="bi bi-activity"></i></span>
                    <h5 class="fw-700 mb-2">Fitness Guide</h5>
                    <p class="text-muted small mb-0">Workout routines and movement tips for every fitness level.</p>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-3">
            <a href="{{ route('resources.nutrition') }}" class="text-decoration-none">
                <div class="resource-card">
                    <span class="resource-icon text-warning"><i class="bi bi-cup-straw"></i></span>
                    <h5 class="fw-700 mb-2">Nutrition</h5>
                    <p class="text-muted small mb-0">Smart eating strategies and food science made simple.</p>
                </div>
            </a>
        </div>

    </div>

    {{-- Quote banner --}}
    <div class="mt-5 p-4 text-center" style="background:linear-gradient(135deg,rgba(45,170,111,.1),rgba(23,163,184,.08));border:1px solid rgba(45,170,111,.2);border-radius:16px">
        <p class="mb-1" style="font-size:1.2rem;font-style:italic;color:var(--text-primary)">
            "Health is not about the weight you lose, but about the life you gain."
        </p>
        <small class="text-muted">— Dr. Josh Axe</small>
    </div>
</div>
@endsection
