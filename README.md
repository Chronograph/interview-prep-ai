# Hire Camp

Hire Camp is an AI-powered interview preparation platform designed to help job seekers master their interview skills and land their dream jobs.

## Features

- **AI-Powered Practice Sessions**: Practice with intelligent, personalized interview questions
- **Real-Time Feedback**: Get instant feedback on your responses and performance
- **Comprehensive Analytics**: Track your progress and identify areas for improvement
- **Resume Analysis**: AI-powered resume optimization and suggestions
- **Company Research**: Detailed briefings on target companies and roles
- **Cheat Sheets**: Quick reference guides for common interview topics

## Technology Stack

- **Backend**: Laravel 11 (PHP)
- **Frontend**: Vue.js 3 with Inertia.js
- **Styling**: Tailwind CSS
- **Database**: SQLite (development) / MySQL/PostgreSQL (production)
- **AI Integration**: OpenAI API for intelligent question generation and feedback

## Getting Started

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18 or higher
- npm or yarn

### Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd hire-camp
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install JavaScript dependencies:
```bash
npm install
```

4. Copy the environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Run database migrations:
```bash
php artisan migrate
```

7. Seed the database:
```bash
php artisan db:seed
```

8. Build assets:
```bash
npm run build
```

9. Start the development server:
```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## Configuration

### Environment Variables

Key environment variables to configure:

- `APP_NAME`: Application name (default: "Hire Camp")
- `APP_URL`: Application URL
- `OPENAI_API_KEY`: OpenAI API key for AI features
- `DB_CONNECTION`: Database connection type
- `DB_DATABASE`: Database name

### AI Services

To enable AI-powered features, you'll need to:

1. Obtain an OpenAI API key
2. Add it to your `.env` file as `OPENAI_API_KEY`
3. Configure the AI services in `config/services.php`

## Testing

Run the test suite:

```bash
php artisan test
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support and questions, please open an issue in the GitHub repository.
