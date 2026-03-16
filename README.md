# 📄 Laravel File AI Processor

> Upload a PDF, DOCX, or TXT file — get back an AI-processed summary or formatted result, ready to download as PDF or Word.

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat-square&logo=bootstrap&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

---

## ✨ Features

- 🔐 **Google OAuth login** via Laravel Socialite — no passwords
- 📁 **Upload PDF, DOCX, or TXT** files
- 🤖 **AI processing** via OpenRouter (GPT-4, Claude, Mistral, and more)
- 📥 **Export results** as PDF (mPDF / DomPDF) or Word (PhpWord)
- 🎨 **Responsive UI** built with Bootstrap 5

---

## 🛠 Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 11 |
| Authentication | Laravel Socialite (Google OAuth) |
| AI Integration | OpenRouter API |
| PDF Export | mPDF + Barryvdh DomPDF |
| Word Export | PhpWord |
| PDF Parsing | PdfParser + PdfToText |
| Frontend | Bootstrap 5 |

---

## ⚙️ Requirements

- PHP 8.2+
- Composer 2.x
- MySQL / PostgreSQL / SQLite
- Node.js 18+ & npm
- Google Cloud project with OAuth 2.0 credentials
- OpenRouter API key
- `poppler-utils` installed on the server (for PdfToText)

---

## 🚀 Installation

```bash
# 1. Clone the repo
git clone https://github.com/AbdullahWael-Dev/laravel-file-ai-processor.git
cd laravel-file-ai-processor

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies & build assets
npm install && npm run build

# 4. Set up environment
cp .env.example .env
php artisan key:generate

# 5. Run migrations
php artisan migrate

# 6. Link storage
php artisan storage:link

# 7. Start the dev server
php artisan serve
```

Visit `http://localhost:8000`

---

## 🔑 Environment Variables

Copy `.env.example` to `.env` and fill in the following:

```env
# App
APP_NAME=FileAIProcessor
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=file_ai
DB_USERNAME=root
DB_PASSWORD=

# Google OAuth
GOOGLE_CLIENT_ID=your-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"

# OpenRouter AI
OPENROUTER_API_KEY=sk-or-xxxx
OPENROUTER_MODEL=openai/gpt-4o

# Upload limit (MB)
MAX_UPLOAD_MB=20
```

---

## 🔐 Google OAuth Setup

1. Go to [console.cloud.google.com](https://console.cloud.google.com) and create a project.
2. Navigate to **APIs & Services → Credentials → Create Credentials → OAuth 2.0 Client ID**.
3. Set the Authorized redirect URI to:
   ```
   https://your-domain.com/auth/google/callback
   ```
4. Copy the **Client ID** and **Client Secret** into your `.env`.
5. Enable the **Google People API** from the API Library.

---

## 🤖 OpenRouter Setup

1. Sign up at [openrouter.ai](https://openrouter.ai) and generate an API key.
2. Set `OPENROUTER_API_KEY` in your `.env`.
3. Set `OPENROUTER_MODEL` to your preferred model, for example:

| Model | Notes |
|---|---|
| `openai/gpt-4o` | Best quality |
| `anthropic/claude-3-haiku` | Fast & affordable |
| `mistralai/mistral-7b-instruct` | Open-source option |

---

## 🔄 How It Works

```
User logs in via Google
        ↓
Uploads a PDF / DOCX / TXT file
        ↓
Text is extracted (PdfParser / PhpWord / plain read)
        ↓
Text is sent to OpenRouter AI with a system prompt
        ↓
AI response is displayed for preview
        ↓
User downloads the result as PDF or Word
```

---

## 📁 Project Structure

```
app/
├── Http/Controllers/
│   ├── Auth/GoogleController.php     # OAuth callback
│   ├── FileController.php            # Upload & processing
│   └── ExportController.php          # PDF / Word export
├── Services/
│   ├── TextExtractorService.php      # Routes to correct parser
│   ├── OpenRouterService.php         # API calls to OpenRouter
│   ├── PdfExportService.php          # mPDF / DomPDF wrappers
│   └── WordExportService.php         # PhpWord wrapper
resources/views/
├── layouts/app.blade.php
├── files/upload.blade.php
└── files/result.blade.php
routes/web.php                        # All application routes
```

## 🤝 Contributing

Pull requests are welcome! For major changes, please open an issue first to discuss what you'd like to change. Make sure to follow PSR-12 coding standards.

---

## 📄 License

This project is licensed under the [MIT License](LICENSE).
