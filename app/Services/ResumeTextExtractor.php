<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser as PdfParser;

class ResumeTextExtractor
{
    public function extractFromStoragePath(string $path, string $disk = 'public'): string
    {
        if (! Storage::disk($disk)->exists($path)) {
            throw new \RuntimeException("Resume file not found: {$path}");
        }

        $fullPath = Storage::disk($disk)->path($path);
        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

        return match ($extension) {
            'pdf' => $this->fromPdf($fullPath),
            'doc', 'docx' => $this->fromWord($fullPath),
            'txt' => (string) file_get_contents($fullPath),
            default => $this->fromPdf($fullPath),
        };
    }

    protected function fromPdf(string $fullPath): string
    {
        try {
            $parser = new PdfParser();
            $pdf = $parser->parseFile($fullPath);
            $text = trim($pdf->getText() ?? '');

            if ($text === '') {
                throw new \RuntimeException('No text extracted from PDF.');
            }

            return $text;
        } catch (\Throwable $e) {
            Log::warning('PDF parse failed: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function fromWord(string $fullPath): string
    {
        try {
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($fullPath);
            $text = '';
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    $text .= $this->extractElementText($element) . "\n";
                }
            }

            $text = trim($text);
            if ($text === '') {
                throw new \RuntimeException('No text extracted from Word document.');
            }

            return $text;
        } catch (\Throwable $e) {
            Log::warning('Word parse failed: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function extractElementText($element): string
    {
        if (method_exists($element, 'getText')) {
            $t = $element->getText();
            if (is_string($t)) {
                return $t;
            }
            if (is_object($t) && method_exists($t, 'getText')) {
                return (string) $t->getText();
            }
        }

        if (method_exists($element, 'getElements')) {
            $out = '';
            foreach ($element->getElements() as $child) {
                $out .= $this->extractElementText($child) . ' ';
            }

            return $out;
        }

        return '';
    }
}
