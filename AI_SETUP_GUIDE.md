# AI Service Setup Guide

This guide explains how to enable LLM-generated interview questions based on job descriptions and resumes.

## Current Status

The AI service is currently **disabled** and using fallback questions to prevent timeout issues. The system supports multiple AI providers through the Prism package.

## Quick Setup (OpenAI - Recommended)

### 1. Get OpenAI API Key
1. Go to [OpenAI Platform](https://platform.openai.com/api-keys)
2. Create a new API key
3. Copy the key (starts with `sk-`)

### 2. Configure Environment Variables
Add these to your `.env` file:

```env
# Enable AI service
AI_ENABLED=true

# OpenAI Configuration
PRISM_DEFAULT_PROVIDER=openai
PRISM_OPENAI_API_KEY=sk-your-api-key-here
PRISM_OPENAI_MODEL=gpt-4
PRISM_REQUEST_TIMEOUT=60
PRISM_CONNECT_TIMEOUT=10
PRISM_MAX_TOKENS=2000
PRISM_TEMPERATURE=0.7
```

### 3. Test the Setup
Run this command to test the AI service:

```bash
php artisan tinker
```

Then test:
```php
$aiService = app(\App\Services\AIService::class);
$questions = $aiService->generateInterviewQuestions(
    'Software Engineer role at tech startup',
    'Experienced developer with 5+ years in React and Node.js',
    'technical'
);
dd($questions);
```

## Alternative Providers

### Anthropic Claude
```env
AI_ENABLED=true
PRISM_DEFAULT_PROVIDER=anthropic
PRISM_ANTHROPIC_API_KEY=your-claude-api-key
PRISM_ANTHROPIC_MODEL=claude-3-5-sonnet-20241022
```

### Local AI (LM Studio)
```env
AI_ENABLED=true
PRISM_DEFAULT_PROVIDER=lmstudio
PRISM_LMSTUDIO_URL=http://localhost:1234/v1
PRISM_LMSTUDIO_API_KEY=lm-studio
PRISM_LMSTUDIO_MODEL=your-local-model
```

### Ollama (Local)
```env
AI_ENABLED=true
PRISM_DEFAULT_PROVIDER=ollama
PRISM_OLLAMA_URL=http://localhost:11434
PRISM_OLLAMA_MODEL=llama3.2
```

## How It Works

When AI is enabled, the system will:

1. **Extract job posting details** from the URL or text
2. **Parse resume information** from uploaded files
3. **Generate contextual questions** based on:
   - Job requirements and responsibilities
   - Candidate's experience and skills
   - Interview type (behavioral, technical, case study, company-specific)
   - Difficulty level (5/10/15 questions)

4. **Return structured questions** with:
   - Question text
   - Category and difficulty
   - Expected answer points
   - Question style

## Troubleshooting

### Timeout Issues
If you experience timeouts:
1. Increase timeout values:
   ```env
   PRISM_REQUEST_TIMEOUT=120
   PRISM_CONNECT_TIMEOUT=15
   ```

2. Check your internet connection
3. Verify API key is valid and has credits

### Fallback Mode
If AI service fails, the system automatically falls back to pre-defined questions based on the interview type.

### Cost Considerations
- **OpenAI GPT-4**: ~$0.03 per request
- **Anthropic Claude**: ~$0.015 per request
- **Local models**: Free but requires local setup

## Testing

Create a test interview session to verify AI generation:

1. Go to Practice Sessions
2. Click "Add New Interview"
3. Enter a job posting URL
4. Select a resume
5. Choose difficulty level
6. Start the session

If AI is working, you'll see questions specifically tailored to the job and resume. If not, you'll see generic fallback questions.

## Production Considerations

- **Rate limiting**: Consider implementing rate limits for AI API calls
- **Caching**: Questions could be cached for identical job/resume combinations
- **Error handling**: System gracefully falls back to static questions
- **Cost monitoring**: Track API usage and costs
- **Queue processing**: Consider moving AI generation to background jobs for better UX
