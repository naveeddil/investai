<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExplainRiskRequest;
use App\Services\RiskEstimator;
use App\Services\RiskExplainer;
use Illuminate\Http\JsonResponse;
use RuntimeException;

class RiskExplanationController extends Controller
{
    /**
     * Return a plain-English AI explanation of the risk calculator's output.
     */
    public function __invoke(ExplainRiskRequest $request, RiskExplainer $explainer): JsonResponse
    {
        if ((string) config('services.anthropic.key') === '') {
            return response()->json(['message' => 'AI explanations are not switched on for this site. The calculation without AI is unaffected.'], 503);
        }

        $allocation = array_map('floatval', $request->validated('allocation'));
        $years = (int) $request->validated('years');
        $tolerance = (int) $request->validated('tolerance');

        $metrics = RiskEstimator::estimate($allocation, $years, $tolerance);

        try {
            $explanation = $explainer->explain($allocation, $years, $tolerance, $metrics);
        } catch (RuntimeException) {
            return response()->json(['message' => 'The AI explanation is unavailable right now. The calculation above still stands.'], 502);
        }

        return response()->json(['explanation' => $explanation]);
    }
}
