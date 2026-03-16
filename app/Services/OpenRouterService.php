<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterService
{
    /**
     * Get AI completion for the given content and action.
     */
    public function getCompletion(string $action, string $content): ?string
    {
        $prompt = $this->buildPrompt($action, $content);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.openrouter.api_key'),
                'Content-Type' => 'application/json',
            ])
            ->timeout(60)
            ->retry(3, 2000)
            ->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
            ]);

            if ($response->failed()) {
                Log::error('OpenRouter API request failed: ' . $response->body());
                return null;
            }

            return $response->json('choices.0.message.content', 'No response from AI.');
        } catch (\Exception $e) {
            Log::error('OpenRouter service error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Build the prompt based on the action.
     */
    private function buildPrompt(string $action, string $content): string
    {
        return $action === 'summarize'
            ? <<<EOT
You are an expert document analyst. Process this extracted content from a Word/PDF file.

TASK: Create a comprehensive summary with clear visual hierarchy.

CRITICAL RULES:
1. Extract and condense main ideas without losing key information
2. Create logical section headings based on content themes
3. Maintain factual accuracy - never add information not in the source
4. Remove all extraction artifacts (page numbers, headers, footers, repeated text)
5. Preserve important details: names, dates, numbers, key facts

FORMATTING STRUCTURE:
[TITLE] 🔵 - Document main theme (1 per document)
[SECTION] 🟣 - Major topics (2-5 sections)
✔️ - Key points under each section
➤ - Supporting details or sub-points
⭐ - Conclusions or important highlights

EXAMPLE OUTPUT:
[TITLE] 🔵 Quarterly Sales Performance Report

[SECTION] 🟣 Revenue Overview
✔️ Total revenue increased 23% to $4.2M
✔️ North region contributed 45% of growth
➤ Key driver: new product line launched in Q2
⭐ Exceeded annual target by 8%

[SECTION] 🟣 Operational Challenges
✔️ Supply chain delays affected delivery times
✔️ Customer satisfaction score dropped to 7.8/10
➤ Action plan: invested in local warehousing

NOW PROCESS THIS CONTENT:
{$content}

OUTPUT ONLY THE FORMATTED SUMMARY - NO PREAMBLE OR EXPLANATIONS.
EOT
            : <<<EOT
You are a professional document editor. Reformat this extracted Word/PDF content into a polished, well-structured document.

TASK: Clean, reorganize, and style the text while preserving ALL original content and meaning.

CRITICAL RULES:
1. DO NOT summarize or shorten - keep complete information
2. Fix extraction errors: broken sentences, spacing issues, encoding problems
3. Remove artifacts: page numbers, headers, footers, duplicate paragraphs
4. Create logical section headings where the content naturally divides
5. Improve readability through proper formatting and hierarchy
6. Correct obvious grammar/spelling errors from OCR/extraction
7. Preserve all data: names, dates, numbers, technical terms, references

FORMATTING STRUCTURE:
[TITLE] 🔵 - Main document title
[SECTION] 🟣 - Major section headings
✔️ - Individual points or items
➤ - Sub-points or supporting information
⭐ - Important notes or conclusions

EXAMPLE OUTPUT:
[TITLE] 🔵 Project Implementation Guidelines

[SECTION] 🟣 Phase 1: Planning and Preparation
✔️ Conduct stakeholder interviews to identify requirements and expectations
✔️ Develop detailed project timeline with milestones and deliverables
✔️ Allocate resources including team members, budget, and tools
➤ Timeline: 3-4 weeks for completion
➤ Budget allocation: 30% of total project cost

[SECTION] 🟣 Phase 2: Execution
✔️ Implement core system features according to specifications
✔️ Conduct weekly progress reviews with project team
✔️ Document all technical decisions and architectural choices
➤ Expected duration: 8-10 weeks
⭐ Critical success factor: maintaining clear communication channels

NOW PROCESS THIS CONTENT:
{$content}

OUTPUT ONLY THE FORMATTED DOCUMENT - NO PREAMBLE OR EXPLANATIONS.
EOT;
    }
}
