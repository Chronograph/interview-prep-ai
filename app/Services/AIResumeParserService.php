<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use OpenAI\Client as OpenAIClient;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

class AIResumeParserService
{
    protected $openai;
    protected $pdfParser;

    public function __construct()
    {
        $this->openai = \OpenAI::client(config('services.openai.api_key'));
        $this->pdfParser = new PdfParser();
        
        // Configure PhpWord for better compatibility
        Settings::setZipClass(Settings::PCLZIP);
    }

    /**
     * Parse a resume file and extract structured data using AI.
     */
    public function parseResume(UploadedFile $file): array
    {
        try {
            // Extract text content from file
            $textContent = $this->extractTextFromFile($file);
            
            if (empty($textContent)) {
                throw new \Exception('Could not extract text from the uploaded file');
            }

            // Use AI to parse the resume content
            $parsedData = $this->parseWithAI($textContent);
            
            return $parsedData;
        } catch (\Exception $e) {
            Log::error('Resume parsing failed', [
                'file_name' => $file->getClientOriginalName(),
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Extract text content from various file types.
     */
    protected function extractTextFromFile(UploadedFile $file): string
    {
        $mimeType = $file->getMimeType();
        $extension = strtolower($file->getClientOriginalExtension());
        
        try {
            switch ($mimeType) {
                case 'application/pdf':
                    return $this->extractFromPdf($file);
                    
                case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                case 'application/msword':
                    return $this->extractFromWord($file);
                    
                case 'text/plain':
                    return file_get_contents($file->getRealPath());
                    
                default:
                    // Try to handle by extension if MIME type detection fails
                    if (in_array($extension, ['pdf'])) {
                        return $this->extractFromPdf($file);
                    } elseif (in_array($extension, ['doc', 'docx'])) {
                        return $this->extractFromWord($file);
                    } elseif (in_array($extension, ['txt'])) {
                        return file_get_contents($file->getRealPath());
                    }
                    
                    throw new \Exception('Unsupported file type: ' . $mimeType);
            }
        } catch (\Exception $e) {
            Log::warning('Text extraction failed, trying fallback methods', [
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $mimeType,
                'error' => $e->getMessage()
            ]);
            
            // Fallback: try to read as plain text
            $content = file_get_contents($file->getRealPath());
            if (!empty($content)) {
                return $content;
            }
            
            throw new \Exception('Could not extract text from file: ' . $e->getMessage());
        }
    }

    /**
     * Extract text from PDF files.
     */
    protected function extractFromPdf(UploadedFile $file): string
    {
        try {
            $pdf = $this->pdfParser->parseFile($file->getRealPath());
            $text = $pdf->getText();
            
            if (empty(trim($text))) {
                throw new \Exception('PDF appears to be empty or contains only images');
            }
            
            return $text;
        } catch (\Exception $e) {
            throw new \Exception('Failed to extract text from PDF: ' . $e->getMessage());
        }
    }

    /**
     * Extract text from Word documents.
     */
    protected function extractFromWord(UploadedFile $file): string
    {
        try {
            $phpWord = IOFactory::load($file->getRealPath());
            $text = '';
            
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $text .= $element->getText() . "\n";
                    } elseif (method_exists($element, 'getElements')) {
                        foreach ($element->getElements() as $childElement) {
                            if (method_exists($childElement, 'getText')) {
                                $text .= $childElement->getText() . "\n";
                            }
                        }
                    }
                }
            }
            
            if (empty(trim($text))) {
                throw new \Exception('Word document appears to be empty');
            }
            
            return $text;
        } catch (\Exception $e) {
            throw new \Exception('Failed to extract text from Word document: ' . $e->getMessage());
        }
    }

    /**
     * Parse resume content using OpenAI.
     */
    protected function parseWithAI(string $content): array
    {
        $prompt = $this->buildParsingPrompt($content);
        
        try {
            $response = $this->openai->chat()->create([
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert resume parser. Extract structured information from resumes and return it as valid JSON. Be thorough but concise.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 2000,
                'temperature' => 0.1
            ]);
            
            $aiResponse = $response->choices[0]->message->content;
            
            // Parse the JSON response
            $parsedData = json_decode($aiResponse, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('AI returned invalid JSON: ' . json_last_error_msg());
            }
            
            return $this->validateAndCleanParsedData($parsedData);
            
        } catch (\Exception $e) {
            Log::error('AI parsing failed', [
                'error' => $e->getMessage(),
                'content_length' => strlen($content)
            ]);
            
            // Return basic fallback data
            return $this->getFallbackData($content);
        }
    }

    /**
     * Build the parsing prompt for AI.
     */
    protected function buildParsingPrompt(string $content): string
    {
        return "Please parse the following resume and extract structured information. Return the data as a JSON object with the following structure:

{
  \"title\": \"Suggested resume title based on content\",
  \"summary\": \"Professional summary or objective\",
  \"skills\": [\"skill1\", \"skill2\", \"skill3\"],
  \"experience\": \"Work experience section as formatted text\",
  \"education\": \"Education section as formatted text\",
  \"certifications\": [\"cert1\", \"cert2\"],
  \"contact_info\": {
    \"email\": \"email@example.com\",
    \"phone\": \"phone number\",
    \"location\": \"city, state\"
  },
  \"experience_years\": 5,
  \"job_titles\": [\"title1\", \"title2\"],
  \"companies\": [\"company1\", \"company2\"],
  \"key_achievements\": [\"achievement1\", \"achievement2\"]
}

Resume content:
\n" . $content;
    }

    /**
     * Validate and clean the parsed data from AI.
     */
    protected function validateAndCleanParsedData(array $data): array
    {
        $cleaned = [
            'title' => $data['title'] ?? 'Untitled Resume',
            'summary' => $data['summary'] ?? '',
            'skills' => is_array($data['skills'] ?? null) ? $data['skills'] : [],
            'experience' => $data['experience'] ?? '',
            'education' => $data['education'] ?? '',
            'certifications' => is_array($data['certifications'] ?? null) ? $data['certifications'] : [],
            'contact_info' => is_array($data['contact_info'] ?? null) ? $data['contact_info'] : [],
            'experience_years' => is_numeric($data['experience_years'] ?? 0) ? (int)$data['experience_years'] : 0,
            'job_titles' => is_array($data['job_titles'] ?? null) ? $data['job_titles'] : [],
            'companies' => is_array($data['companies'] ?? null) ? $data['companies'] : [],
            'key_achievements' => is_array($data['key_achievements'] ?? null) ? $data['key_achievements'] : []
        ];
        
        // Clean up skills array
        $cleaned['skills'] = array_filter(array_map('trim', $cleaned['skills']));
        
        // Clean up certifications array
        $cleaned['certifications'] = array_filter(array_map('trim', $cleaned['certifications']));
        
        return $cleaned;
    }

    /**
     * Get fallback data when AI parsing fails.
     */
    protected function getFallbackData(string $content): array
    {
        return [
            'title' => 'Resume',
            'summary' => '',
            'skills' => $this->extractSkillsBasic($content),
            'experience' => $this->extractExperienceBasic($content),
            'education' => $this->extractEducationBasic($content),
            'certifications' => [],
            'contact_info' => $this->extractContactBasic($content),
            'experience_years' => 0,
            'job_titles' => [],
            'companies' => [],
            'key_achievements' => []
        ];
    }

    /**
     * Basic skill extraction using pattern matching.
     */
    protected function extractSkillsBasic(string $content): array
    {
        $skills = [];
        $commonSkills = [
            'JavaScript', 'Python', 'Java', 'PHP', 'React', 'Vue', 'Angular', 'Node.js',
            'Laravel', 'Django', 'Spring', 'SQL', 'MySQL', 'PostgreSQL', 'MongoDB',
            'AWS', 'Docker', 'Kubernetes', 'Git', 'HTML', 'CSS', 'TypeScript'
        ];
        
        foreach ($commonSkills as $skill) {
            if (stripos($content, $skill) !== false) {
                $skills[] = $skill;
            }
        }
        
        return array_unique($skills);
    }

    /**
     * Basic experience extraction.
     */
    protected function extractExperienceBasic(string $content): string
    {
        // Look for experience section
        $patterns = [
            '/experience[\s\S]*?(?=education|skills|$)/i',
            '/work history[\s\S]*?(?=education|skills|$)/i',
            '/employment[\s\S]*?(?=education|skills|$)/i'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content, $matches)) {
                return trim($matches[0]);
            }
        }
        
        return '';
    }

    /**
     * Basic education extraction.
     */
    protected function extractEducationBasic(string $content): string
    {
        // Look for education section
        $patterns = [
            '/education[\s\S]*?(?=experience|skills|$)/i',
            '/academic[\s\S]*?(?=experience|skills|$)/i'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content, $matches)) {
                return trim($matches[0]);
            }
        }
        
        return '';
    }

    /**
     * Basic contact information extraction.
     */
    protected function extractContactBasic(string $content): array
    {
        $contact = [];
        
        // Extract email
        if (preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $content, $matches)) {
            $contact['email'] = $matches[0];
        }
        
        // Extract phone (basic pattern)
        if (preg_match('/\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}/', $content, $matches)) {
            $contact['phone'] = $matches[0];
        }
        
        return $contact;
    }
}