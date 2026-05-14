@extends('layouts.app')
@section('title', 'AI Nutrition Assistant')

@section('content')
<div class="page-hero" style="padding:3rem 0 2rem">
    <div class="container">
        <div class="d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:rgba(45,170,111,0.1); width:60px; height:60px; font-size:2rem;">
                <i class="bi bi-apple text-brand"></i>
            </div>
            <div>
                <h1 class="mb-1" style="font-size:2rem">AI Nutrition Assistant</h1>
                <p class="lead" style="font-size:1rem">Get instant nutritional insights for your meals using Groq Llama 3.1</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card-wellness p-4 ai-glow mb-4">
                <h5 class="fw-700 mb-3">Describe your meal</h5>
                <p class="text-muted small mb-3">Tell us what you ate (e.g., "A bowl of oats with blueberries and honey") and we'll estimate the nutrients.</p>
                
                <div class="mb-3">
                    <textarea id="food-input" class="form-control" rows="3" placeholder="Enter food description..."></textarea>
                </div>
                
                <button id="analyze-nutrition-btn" class="btn btn-primary w-100 py-2">
                    <span id="btn-text"><i class="bi bi-search me-2"></i>Analyze Nutrition</span>
                    <span id="btn-loader" class="loading-spinner d-none"></span>
                </button>
            </div>

            <div id="nutrition-result" class="ai-suggestion-box d-none">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-info-circle-fill text-brand"></i>
                    <h6 class="mb-0 fw-700">Analysis Results</h6>
                </div>
                <div class="result-content" style="white-space: pre-line;"></div>
                <div class="mt-3 p-2 bg-brand-soft rounded small" style="background:rgba(45,170,111,0.05); border-left: 3px solid var(--brand-green);">
                    <i class="bi bi-exclamation-triangle me-1"></i> <strong>Disclaimer:</strong> These are AI-generated estimates. For medical advice, please consult a nutritionist.
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="sidebar-card">
                <h6>Why use this?</h6>
                <ul class="list-unstyled small text-muted">
                    <li class="mb-2"><i class="bi bi-check2-circle text-brand me-2"></i> Track your daily macro-nutrients.</li>
                    <li class="mb-2"><i class="bi bi-check2-circle text-brand me-2"></i> Understand the health impact of meals.</li>
                    <li class="mb-2"><i class="bi bi-check2-circle text-brand me-2"></i> Get quick dietary tips instantly.</li>
                </ul>
            </div>
            
            <div class="sidebar-card">
                <h6>Recent Tips</h6>
                <div class="wellness-tip-card p-2 small">
                    "Protein at breakfast helps keep you full longer and stabilizes blood sugar."
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const foodInput = document.getElementById('food-input');
    const analyzeBtn = document.getElementById('analyze-nutrition-btn');
    const btnText = document.getElementById('btn-text');
    const btnLoader = document.getElementById('btn-loader');
    const resultBox = document.getElementById('nutrition-result');
    const resultContent = resultBox.querySelector('.result-content');

    analyzeBtn.addEventListener('click', async function() {
        const food = foodInput.value.trim();
        if (!food) return;

        // UI Loading State
        analyzeBtn.disabled = true;
        btnText.classList.add('d-none');
        btnLoader.classList.remove('d-none');
        resultBox.classList.add('d-none');

        try {
            const response = await fetch('{{ route("ai.analyze-nutrition") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ food: food })
            });

            const data = await response.json();
            
            if (data.analysis) {
                resultContent.innerHTML = data.analysis;
                resultBox.classList.remove('d-none');
            } else if (data.error) {
                alert(data.error);
            }
        } catch (error) {
            console.error('Nutrition Analysis Error:', error);
            alert('Something went wrong. Please try again.');
        } finally {
            analyzeBtn.disabled = false;
            btnText.classList.remove('d-none');
            btnLoader.classList.add('d-none');
        }
    });
});
</script>
@endpush
