<?php

namespace App\Http\Controllers;

use App\Services\FileContentExtractor;
use App\Services\OpenRouterService;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWord\Shared\Html;
use Mpdf\Mpdf;

class OpenRouterChatController extends Controller
{
    protected $openRouterService;

    public function __construct(OpenRouterService $openRouterService)
    {
        $this->openRouterService = $openRouterService;
    }

    public function process(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
            'action' => 'required|in:summarize,format',
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $content = FileContentExtractor::extractTextFromFile($file->getRealPath(), $extension);
        $content = @iconv('UTF-8', 'UTF-8//IGNORE', $content);
        $content = preg_replace('/\n{2,}/', "</p><p>", $content);
        $content = "<p>{$content}</p>";

        $result = $this->openRouterService->getCompletion($request->action, $content);

        if (!$result) {
            return back()->with('error', 'فشل في الاتصال بـ OpenRouter.');
        }

        session(['ai_result' => $result]);
        $direction = preg_match('/[\x{0600}-\x{06FF}]/u', $result) ? 'rtl' : 'ltr';
        $textAlign = $direction === 'rtl' ? 'right' : 'left';
        $result = strip_tags($result);

        $result = html_entity_decode($result, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $result = preg_replace('/[\r\n]+/', "\n", $result); // توحيد الأسطر
        $result = preg_replace('/[ \t]+/', ' ', $result);   // توحيد الفراغات
        $result = trim($result);
        return view('result', compact('result', 'direction', 'textAlign'));
    }

    public function downloadPdf()
    {
        $result = session('ai_result');
        if (!$result) {
            return back()->with('error', 'لا توجد نتيجة للتحميل.');
        }

        $cleanText = $this->prepareText($result);
        $cleanText = nl2br($cleanText);
        $direction = preg_match('/[\x{0600}-\x{06FF}]/u', $cleanText) ? 'rtl' : 'ltr';
        $textAlign = $direction === 'rtl' ? 'right' : 'left';

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'default_font' => 'amiri',
            'orientation' => 'P',
        ]);

        $html = view('pdf.pdf_template', compact('cleanText', 'direction', 'textAlign','result'))->render();

        $mpdf->WriteHTML($html);
        $filePath = storage_path('app/public/summary.pdf');
        $mpdf->Output($filePath, \Mpdf\Output\Destination::FILE);

        return response()->file($filePath);
    }


    public function downloadWord()
    {
        $result = session('ai_result');
        if (!$result) {
            return back()->with('error', 'لا توجد نتيجة للتحميل.');
        }

        $cleanText = $this->prepareText($result);
        $direction = preg_match('/[\x{0600}-\x{06FF}]/u', $cleanText) ? 'rtl' : 'ltr';

        try {
            $phpWord = new PhpWord();

            $section = $phpWord->addSection();

            $rtlParagraphStyle = [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT,
                'rtl' => true,
            ];
            $ltrParagraphStyle = [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT,
                'rtl' => false,
            ];

            $paragraphs = explode("\n", $cleanText);
            foreach ($paragraphs as $paragraphText) {
                $section->addText(
                    htmlspecialchars($paragraphText),
                    [],
                    $direction === 'rtl' ? $rtlParagraphStyle : $ltrParagraphStyle
                );
            }

            $fileName = 'result_' . time() . '.docx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Disposition: attachment;filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');

            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save('php://output');
        } catch (\Exception $e) {
            return back()->with('error', 'خطأ في توليد ملف Word: ' . $e->getMessage());
        }
        return response()->noContent();
    }

    private function prepareText($text)
    {
        $text = strip_tags($text);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $text = trim($text);
        $text = preg_replace('/[\r\n]+/', "\n", $text);
        return @iconv('UTF-8', 'UTF-8//IGNORE', $text);
    }
}
