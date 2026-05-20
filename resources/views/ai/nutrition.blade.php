@extends('layouts.app')
@section('title', __('AI Nutrition Assistant'))

@section('content')
<div class="page-hero" style="padding:3rem 0 2rem">
    <div class="container">
        <div class="d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:rgba(45,170,111,0.1); width:60px; height:60px; font-size:2rem;">
                <i class="bi bi-leaf text-brand"></i>
            </div>
            <div>
                <h1 class="mb-1" style="font-size:2rem">{{ __('AI Nutrition Assistant') }}</h1>
                <p class="lead" style="font-size:1rem">{{ __('Get instant nutritional insights for your meals using Groq Llama 3.1') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card-wellness p-4 ai-glow mb-4">
                <h5 class="fw-700 mb-3">{{ __('Describe your meal') }}</h5>
                <p class="text-muted small mb-3">{{ __('Tell us what you ate (e.g., "A bowl of oats with blueberries and honey") and we\'ll estimate the nutrients.') }}</p>
                
                <div class="mb-3">
                    <textarea id="food-input" class="form-control" rows="3" placeholder="{{ __('Enter food description...') }}"></textarea>
                </div>
                
                <button id="analyze-nutrition-btn" class="btn btn-primary w-100 py-2">
                    <span id="btn-text"><i class="bi bi-search me-2"></i>{{ __('Analyze Nutrition') }}</span>
                    <span id="btn-loader" class="loading-spinner d-none"></span>
                </button>
            </div>

            <div id="nutrition-result" class="ai-suggestion-box d-none">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-info-circle-fill text-brand"></i>
                    <h6 class="mb-0 fw-700">{{ __('Analysis Results') }}</h6>
                </div>
                <div class="result-content"></div>
                <div class="mt-3 p-2 bg-brand-soft rounded small" style="background:rgba(45,170,111,0.05); border-left: 3px solid var(--brand-green);">
                    <i class="bi bi-exclamation-triangle me-1"></i> <strong>{{ __('Disclaimer:') }}</strong> {{ __('These are AI-generated estimates. For medical advice, please consult a nutritionist.') }}
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="sidebar-card">
                <h6>{{ __('Why use this?') }}</h6>
                <ul class="list-unstyled small text-muted">
                    <li class="mb-2"><i class="bi bi-check2-circle text-brand me-2"></i> {{ __('Track your daily macro-nutrients.') }}</li>
                    <li class="mb-2"><i class="bi bi-check2-circle text-brand me-2"></i> {{ __('Understand the health impact of meals.') }}</li>
                    <li class="mb-2"><i class="bi bi-check2-circle text-brand me-2"></i> {{ __('Get quick dietary tips instantly.') }}</li>
                </ul>
            </div>
            
            <div class="sidebar-card">
                <h6>{{ __('Recent Tips') }}</h6>
                <div class="wellness-tip-card p-2 small">
                    "{{ __('Protein at breakfast helps keep you full longer and stabilizes blood sugar.') }}"
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
                let analysis = data.analysis;
                
                if (typeof analysis === 'string') {
                    try {
                        analysis = JSON.parse(analysis);
                    } catch (e) {
                        resultContent.innerHTML = `<div class="card bg-dark border-secondary p-4 rounded-3 text-white mb-4 shadow-sm">${analysis}</div>`;
                        resultBox.classList.remove('d-none');
                        return;
                    }
                }
                
                const totalMacros = (analysis.protein || 0) + (analysis.carbs || 0) + (analysis.fats || 0);
                
                let pPct = 0;
                let cPct = 0;
                let fPct = 0;
                if (totalMacros > 0) {
                    pPct = Math.round((analysis.protein / totalMacros) * 100);
                    cPct = Math.round((analysis.carbs / totalMacros) * 100);
                    fPct = 100 - pPct - cPct;
                }

                let insightsHtml = '';
                if (Array.isArray(analysis.insights)) {
                    analysis.insights.forEach(insight => {
                        insightsHtml += `<li class="mb-2 d-flex align-items-start"><i class="bi bi-patch-check-fill text-brand me-2 mt-1"></i><span>${insight}</span></li>`;
                    });
                } else if (analysis.insights) {
                    insightsHtml = `<li class="mb-2 d-flex align-items-start"><i class="bi bi-patch-check-fill text-brand me-2 mt-1"></i><span>${analysis.insights}</span></li>`;
                } else {
                    insightsHtml = `<li class="mb-2 d-flex align-items-start"><i class="bi bi-patch-check-fill text-brand me-2 mt-1"></i><span>No insights available.</span></li>`;
                }

                resultContent.innerHTML = `
                    <div class="card bg-dark border-secondary p-4 rounded-3 text-white mb-4 shadow-sm">
                        <h5 class="text-center mb-4 fw-700 text-brand" style="color:var(--brand-green) !important;">${food}</h5>
                        
                        <div class="row g-3 mb-4 text-center">
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-3 h-100" style="background: rgba(45, 170, 111, 0.1); border: 1px solid rgba(45, 170, 111, 0.2);">
                                    <div class="text-muted small mb-1"><i class="bi bi-fire text-brand me-1"></i>Calories</div>
                                    <h3 class="fw-700 text-brand mb-0" style="color:var(--brand-green) !important;">${analysis.calories || 0} <span class="fs-6">kcal</span></h3>
                                </div>
                            </div>
                            <div class="col-12 col-md-8">
                                <div class="p-3 rounded-3 h-100" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08);">
                                    <div class="row text-center h-100 align-items-center">
                                        <div class="col-4">
                                            <div class="text-muted small">Protein</div>
                                            <div class="fw-700 text-info mt-1">${analysis.protein || 0}g</div>
                                        </div>
                                        <div class="col-4 border-start border-secondary">
                                            <div class="text-muted small">Carbs</div>
                                            <div class="fw-700 text-warning mt-1">${analysis.carbs || 0}g</div>
                                        </div>
                                        <div class="col-4 border-start border-secondary">
                                            <div class="text-muted small">Fats</div>
                                            <div class="fw-700 text-danger mt-1">${analysis.fats || 0}g</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6 class="fw-600 text-white mb-2"><i class="bi bi-bar-chart-fill text-brand me-2"></i>Macronutrient Ratios</h6>
                            <div class="progress" style="height: 12px; background: rgba(255,255,255,0.08); border-radius: 6px; overflow: hidden;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: ${pPct}%" aria-valuenow="${pPct}" aria-valuemin="0" aria-valuemax="100" title="Protein: ${pPct}%"></div>
                                <div class="progress-bar bg-warning" role="progressbar" style="width: ${cPct}%" aria-valuenow="${cPct}" aria-valuemin="0" aria-valuemax="100" title="Carbs: ${cPct}%"></div>
                                <div class="progress-bar bg-danger" role="progressbar" style="width: ${fPct}%" aria-valuenow="${fPct}" aria-valuemin="0" aria-valuemax="100" title="Fats: ${fPct}%"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2 small text-muted">
                                <span><span class="badge bg-info me-1" style="width:10px; height:10px; padding:0; border-radius:50%; display:inline-block; vertical-align:middle;"></span>Protein (${pPct}%)</span>
                                <span><span class="badge bg-warning me-1" style="width:10px; height:10px; padding:0; border-radius:50%; display:inline-block; vertical-align:middle;"></span>Carbs (${cPct}%)</span>
                                <span><span class="badge bg-danger me-1" style="width:10px; height:10px; padding:0; border-radius:50%; display:inline-block; vertical-align:middle;"></span>Fats (${fPct}%)</span>
                            </div>
                        </div>

                        <div class="border-top border-secondary pt-3 mt-4">
                            <h6 class="fw-600 text-white mb-3"><i class="bi bi-lightbulb-fill text-brand me-2"></i>Key Dietary Insights</h6>
                            <ul class="list-unstyled mb-0 text-muted">
                                ${insightsHtml}
                            </ul>
                        </div>
                    </div>
                `;
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
