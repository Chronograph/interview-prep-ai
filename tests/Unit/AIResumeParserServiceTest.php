<?php

namespace Tests\Unit;

use App\Services\AIResumeParserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Mockery;

class AIResumeParserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $parserService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->parserService = new AIResumeParserService();
        Storage::fake('local');
    }

    public function test_can_extract_text_from_pdf_file()
    {
        // Create a mock PDF file
        $pdfFile = UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf');
        
        // Mock the PDF parser to return sample text
        $mockParser = Mockery::mock('overload:Smalot\\PdfParser\\Parser');
        $mockDocument = Mockery::mock();
        $mockDocument->shouldReceive('getText')->andReturn('John Doe\nSoftware Engineer\nPHP, Laravel, JavaScript');
        $mockParser->shouldReceive('parseFile')->andReturn($mockDocument);

        $extractedText = $this->parserService->extractTextFromFile($pdfFile);

        $this->assertIsString($extractedText);
        $this->assertStringContainsString('John Doe', $extractedText);
        $this->assertStringContainsString('Software Engineer', $extractedText);
    }

    public function test_can_extract_text_from_word_document()
    {
        // Create a mock Word document
        $docFile = UploadedFile::fake()->create('resume.docx', 100, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        
        // Mock PhpWord to return sample text
        $mockReader = Mockery::mock('overload:PhpOffice\\PhpWord\\IOFactory');
        $mockDocument = Mockery::mock();
        $mockSection = Mockery::mock();
        $mockElement = Mockery::mock();
        
        $mockElement->shouldReceive('getText')->andReturn('Jane Smith Senior Developer React, Node.js, Python');
        $mockSection->shouldReceive('getElements')->andReturn([$mockElement]);
        $mockDocument->shouldReceive('getSections')->andReturn([$mockSection]);
        $mockReader->shouldReceive('load')->andReturn($mockDocument);

        $extractedText = $this->parserService->extractTextFromFile($docFile);

        $this->assertIsString($extractedText);
    }

    public function test_can_parse_resume_with_ai()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'personal_info' => [
                                    'name' => 'John Doe',
                                    'email' => 'john.doe@example.com',
                                    'phone' => '+1-555-0123',
                                    'location' => 'San Francisco, CA'
                                ],
                                'professional_summary' => 'Experienced software engineer with 5+ years in web development',
                                'skills' => [
                                    'technical' => ['PHP', 'Laravel', 'JavaScript', 'React', 'MySQL'],
                                    'soft' => ['Leadership', 'Communication', 'Problem Solving']
                                ],
                                'experience' => [
                                    [
                                        'company' => 'Tech Corp',
                                        'position' => 'Senior Developer',
                                        'duration' => '2020-2023',
                                        'responsibilities' => ['Led development team', 'Built scalable applications']
                                    ]
                                ],
                                'education' => [
                                    [
                                        'institution' => 'University of Technology',
                                        'degree' => 'Bachelor of Computer Science',
                                        'year' => '2018'
                                    ]
                                ],
                                'certifications' => ['AWS Certified Developer'],
                                'projects' => [
                                    [
                                        'name' => 'E-commerce Platform',
                                        'description' => 'Built using Laravel and React',
                                        'technologies' => ['Laravel', 'React', 'MySQL']
                                    ]
                                ]
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        $resumeText = 'John Doe\nSoftware Engineer\nExperience: Tech Corp (2020-2023)\nSkills: PHP, Laravel, JavaScript';
        
        $parsedData = $this->parserService->parseWithAI($resumeText);

        $this->assertIsArray($parsedData);
        $this->assertArrayHasKey('personal_info', $parsedData);
        $this->assertArrayHasKey('skills', $parsedData);
        $this->assertArrayHasKey('experience', $parsedData);
        $this->assertArrayHasKey('education', $parsedData);
        
        $this->assertEquals('John Doe', $parsedData['personal_info']['name']);
        $this->assertContains('PHP', $parsedData['skills']['technical']);
        $this->assertContains('Laravel', $parsedData['skills']['technical']);
    }

    public function test_can_extract_skills_from_resume()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'technical_skills' => [
                                    'programming_languages' => ['PHP', 'JavaScript', 'Python'],
                                    'frameworks' => ['Laravel', 'React', 'Vue.js'],
                                    'databases' => ['MySQL', 'PostgreSQL', 'Redis'],
                                    'tools' => ['Git', 'Docker', 'AWS']
                                ],
                                'soft_skills' => [
                                    'communication',
                                    'leadership',
                                    'problem_solving',
                                    'teamwork'
                                ],
                                'skill_levels' => [
                                    'PHP' => 'expert',
                                    'Laravel' => 'expert',
                                    'JavaScript' => 'advanced',
                                    'React' => 'intermediate'
                                ],
                                'years_of_experience' => [
                                    'PHP' => 5,
                                    'Laravel' => 4,
                                    'JavaScript' => 6
                                ]
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        $resumeText = 'Senior PHP Developer with 5 years experience in Laravel, JavaScript, and React development';
        
        $skills = $this->parserService->extractSkills($resumeText);

        $this->assertIsArray($skills);
        $this->assertArrayHasKey('technical_skills', $skills);
        $this->assertArrayHasKey('soft_skills', $skills);
        $this->assertArrayHasKey('skill_levels', $skills);
        
        $this->assertContains('PHP', $skills['technical_skills']['programming_languages']);
        $this->assertContains('Laravel', $skills['technical_skills']['frameworks']);
        $this->assertEquals('expert', $skills['skill_levels']['PHP']);
    }

    public function test_can_extract_work_experience()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'work_experience' => [
                                    [
                                        'company' => 'Tech Solutions Inc.',
                                        'position' => 'Senior Software Engineer',
                                        'start_date' => '2020-01',
                                        'end_date' => '2023-12',
                                        'duration' => '4 years',
                                        'location' => 'San Francisco, CA',
                                        'responsibilities' => [
                                            'Led a team of 5 developers',
                                            'Architected scalable web applications',
                                            'Implemented CI/CD pipelines'
                                        ],
                                        'achievements' => [
                                            'Reduced deployment time by 60%',
                                            'Improved application performance by 40%'
                                        ],
                                        'technologies' => ['PHP', 'Laravel', 'React', 'AWS']
                                    ]
                                ],
                                'total_experience' => '4 years',
                                'career_progression' => 'steady_growth',
                                'industry_experience' => ['technology', 'fintech']
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        $resumeText = 'Senior Software Engineer at Tech Solutions Inc. (2020-2023)\nLed development team, built scalable applications';
        
        $experience = $this->parserService->extractWorkExperience($resumeText);

        $this->assertIsArray($experience);
        $this->assertArrayHasKey('work_experience', $experience);
        $this->assertArrayHasKey('total_experience', $experience);
        
        $firstJob = $experience['work_experience'][0];
        $this->assertEquals('Tech Solutions Inc.', $firstJob['company']);
        $this->assertEquals('Senior Software Engineer', $firstJob['position']);
        $this->assertIsArray($firstJob['responsibilities']);
        $this->assertIsArray($firstJob['technologies']);
    }

    public function test_can_extract_education_information()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'education' => [
                                    [
                                        'institution' => 'Stanford University',
                                        'degree' => 'Master of Science',
                                        'field_of_study' => 'Computer Science',
                                        'graduation_year' => '2019',
                                        'gpa' => '3.8',
                                        'relevant_coursework' => [
                                            'Data Structures and Algorithms',
                                            'Software Engineering',
                                            'Database Systems'
                                        ]
                                    ]
                                ],
                                'certifications' => [
                                    {
                                        'name' => 'AWS Certified Solutions Architect',
                                        'issuer' => 'Amazon Web Services',
                                        'date' => '2022-03',
                                        'expiry' => '2025-03'
                                    }
                                ],
                                'additional_training' => [
                                    'Agile Development Certification',
                                    'Docker and Kubernetes Workshop'
                                ]
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        $resumeText = 'Master of Science in Computer Science, Stanford University (2019)\nAWS Certified Solutions Architect';
        
        $education = $this->parserService->extractEducation($resumeText);

        $this->assertIsArray($education);
        $this->assertArrayHasKey('education', $education);
        $this->assertArrayHasKey('certifications', $education);
        
        $degree = $education['education'][0];
        $this->assertEquals('Stanford University', $degree['institution']);
        $this->assertEquals('Master of Science', $degree['degree']);
        $this->assertEquals('Computer Science', $degree['field_of_study']);
    }

    public function test_can_analyze_resume_quality()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'overall_score' => 85,
                                'section_scores' => [
                                    'personal_info' => 90,
                                    'professional_summary' => 80,
                                    'work_experience' => 85,
                                    'skills' => 88,
                                    'education' => 82
                                ],
                                'strengths' => [
                                    'Clear and concise professional summary',
                                    'Well-documented work experience',
                                    'Relevant technical skills'
                                ],
                                'areas_for_improvement' => [
                                    'Add more quantifiable achievements',
                                    'Include relevant certifications',
                                    'Improve formatting consistency'
                                ],
                                'recommendations' => [
                                    'Use action verbs to start bullet points',
                                    'Include metrics and numbers where possible',
                                    'Tailor skills section to job requirements'
                                ],
                                'ats_compatibility' => 78,
                                'keyword_optimization' => 82
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        $resumeText = 'John Doe\nSoftware Engineer with 5 years experience\nWorked at Tech Corp developing web applications';
        
        $analysis = $this->parserService->analyzeResumeQuality($resumeText);

        $this->assertIsArray($analysis);
        $this->assertArrayHasKey('overall_score', $analysis);
        $this->assertArrayHasKey('section_scores', $analysis);
        $this->assertArrayHasKey('strengths', $analysis);
        $this->assertArrayHasKey('areas_for_improvement', $analysis);
        $this->assertArrayHasKey('recommendations', $analysis);
        
        $this->assertBetween($analysis['overall_score'], 0, 100);
        $this->assertIsArray($analysis['strengths']);
        $this->assertIsArray($analysis['recommendations']);
    }

    public function test_can_suggest_resume_improvements()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'priority_improvements' => [
                                    [
                                        'category' => 'content',
                                        'suggestion' => 'Add quantifiable achievements with specific metrics',
                                        'impact' => 'high',
                                        'example' => 'Instead of "improved performance", use "improved application performance by 40%"'
                                    ],
                                    [
                                        'category' => 'formatting',
                                        'suggestion' => 'Use consistent bullet point formatting',
                                        'impact' => 'medium',
                                        'example' => 'Use either • or - consistently throughout'
                                    ]
                                ],
                                'keyword_suggestions' => [
                                    'Add industry-specific keywords',
                                    'Include relevant technology stack terms',
                                    'Use action verbs like "implemented", "optimized", "architected"'
                                ],
                                'structure_recommendations' => [
                                    'Move skills section higher',
                                    'Add a projects section',
                                    'Include relevant certifications'
                                ],
                                'ats_optimization' => [
                                    'Use standard section headings',
                                    'Avoid complex formatting',
                                    'Include relevant keywords naturally'
                                ]
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        $resumeText = 'Basic resume with minimal formatting and generic descriptions';
        
        $suggestions = $this->parserService->suggestImprovements($resumeText);

        $this->assertIsArray($suggestions);
        $this->assertArrayHasKey('priority_improvements', $suggestions);
        $this->assertArrayHasKey('keyword_suggestions', $suggestions);
        $this->assertArrayHasKey('structure_recommendations', $suggestions);
        $this->assertArrayHasKey('ats_optimization', $suggestions);
    }

    public function test_handles_unsupported_file_types()
    {
        $imageFile = UploadedFile::fake()->image('resume.jpg');
        
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported file type');
        
        $this->parserService->extractTextFromFile($imageFile);
    }

    public function test_handles_corrupted_files_gracefully()
    {
        $corruptedFile = UploadedFile::fake()->create('corrupted.pdf', 0, 'application/pdf');
        
        $result = $this->parserService->extractTextFromFile($corruptedFile);
        
        $this->assertIsString($result);
        $this->assertEmpty($result); // Should return empty string for corrupted files
    }

    public function test_handles_api_errors_gracefully()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([], 500)
        ]);

        $resumeText = 'Sample resume text';
        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to parse resume with AI');
        
        $this->parserService->parseWithAI($resumeText);
    }

    public function test_validates_input_parameters()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $this->parserService->parseWithAI(''); // Empty text should throw exception
    }

    public function test_can_extract_contact_information()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'contact_info' => [
                                    'email' => 'john.doe@example.com',
                                    'phone' => '+1-555-0123',
                                    'linkedin' => 'linkedin.com/in/johndoe',
                                    'github' => 'github.com/johndoe',
                                    'website' => 'johndoe.dev',
                                    'address' => 'San Francisco, CA'
                                ],
                                'social_profiles' => [
                                    'linkedin' => 'linkedin.com/in/johndoe',
                                    'github' => 'github.com/johndoe',
                                    'twitter' => '@johndoe_dev'
                                ]
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        $resumeText = 'John Doe\njohn.doe@example.com\n+1-555-0123\nlinkedin.com/in/johndoe';
        
        $contactInfo = $this->parserService->extractContactInformation($resumeText);

        $this->assertIsArray($contactInfo);
        $this->assertArrayHasKey('contact_info', $contactInfo);
        $this->assertEquals('john.doe@example.com', $contactInfo['contact_info']['email']);
        $this->assertEquals('+1-555-0123', $contactInfo['contact_info']['phone']);
    }

    public function test_can_match_resume_to_job_requirements()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'match_score' => 85,
                                'matching_skills' => ['PHP', 'Laravel', 'JavaScript'],
                                'missing_skills' => ['React', 'AWS'],
                                'experience_match' => 'strong',
                                'education_match' => 'adequate',
                                'overall_fit' => 'excellent',
                                'recommendations' => [
                                    'Highlight Laravel experience more prominently',
                                    'Add React projects to demonstrate frontend skills'
                                ]
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        $resumeText = 'PHP Developer with Laravel experience';
        $jobRequirements = 'Looking for PHP/Laravel developer with React knowledge';
        
        $match = $this->parserService->matchToJobRequirements($resumeText, $jobRequirements);

        $this->assertIsArray($match);
        $this->assertArrayHasKey('match_score', $match);
        $this->assertArrayHasKey('matching_skills', $match);
        $this->assertArrayHasKey('missing_skills', $match);
        $this->assertBetween($match['match_score'], 0, 100);
    }

    protected function assertBetween($value, $min, $max)
    {
        $this->assertGreaterThanOrEqual($min, $value);
        $this->assertLessThanOrEqual($max, $value);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}