<?php

namespace App\Services;

use PhpOffice\PhpWord\IOFactory;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Facades\Log;

class FileContentExtractor
{

    public static function extractTextFromFile(string $filePath, string $extension): string
    {
        $extension = strtolower($extension);
        $content = '';
        if ($extension === 'pdf') {
            try {
                $content = (new Pdf(config('services.pdf_to_text.path')))
                    ->setPdf($filePath)
                    ->text();
            } catch (\Exception $e) {
                Log::error("PDF parsing failed with Spatie\\PdfToText for {$filePath}: " . $e->getMessage());
                return '';
            }
        } elseif (in_array($extension, ['doc', 'docx'])) {
            try {
                $phpWord = IOFactory::load($filePath);
                $text = '';

                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getText')) {
                            $text .= $element->getText() . "\n";
                        }
                    }
                }

                $content = $text;
            } catch (\Exception $e) {
                Log::error("Word document parsing failed for {$filePath}: " . $e->getMessage());
                return '';
            }
        } else {
            $content = @file_get_contents($filePath);
            if ($content === false) {
                Log::error("Failed to read content from plain file: {$filePath}");
                return '';
            }
        }

        $detectionOrder = mb_detect_order();
        $sourceEncoding = $detectionOrder
            ? (is_array($detectionOrder) ? implode(',', $detectionOrder) : $detectionOrder)
            : 'auto';

        $content = mb_convert_encoding($content, 'UTF-8', $sourceEncoding);
        $content = str_replace("\0", '', $content);
        $content = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $content);
        $content = strip_tags($content);
        $content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $content = iconv('UTF-8', 'UTF-8//IGNORE', $content);

        return trim($content);
    }
}
