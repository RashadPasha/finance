# RashadFinance

Premium financial blog, consulting and professional portfolio platform.

## Stack
- PHP 8.1+ (custom PHP, no framework dependency)
- MySQL 8 / MariaDB
- Responsive HTML/CSS/vanilla JS
- REST-style PHP endpoints
- Flutter mobile app (planned; Android + iOS share one codebase)

## Brand direction
Premium dark navy + white + charcoal, editorial typography, restrained gold/blue accents, clean consulting aesthetic.

## Modules
- Financial articles with images, links, categories, publishing status and statistics
- Nested categories with ordering
- YouTube video library
- Services, descriptions and prices
- Projects / case studies
- Portfolio / profile
- Contact and consultation requests
- AI assistant grounded in published RashadFinance content

## Initial setup
1. Create a MySQL database and database user in DirectAdmin.
2. Import `database/schema.sql`.
3. Copy `config/config.example.php` to `config/config.php` and enter database credentials.
4. Set a strong `ADMIN_PASSWORD_HASH` in the private config. Generate it with PHP:
   `php -r "echo password_hash('YOUR_STRONG_PASSWORD', PASSWORD_DEFAULT), PHP_EOL;"`
5. Point the domain document root to `public/` if hosting permits. Otherwise use the included deployment guidance before exposing the site.
6. Configure HTTPS before enabling admin login or the chatbot.

## Security
- Never commit `config/config.php`, API keys, database passwords or production secrets.
- The OpenAI API key belongs in server environment configuration only; never in browser JavaScript or the mobile app.
- Use HTTPS, strong admin credentials, backups and least-privilege database credentials.
- Public repository is intentional for source code only; do not put private data in it.

## Current status
Foundation and database schema are being established. Admin CRUD, public content pages, API authentication and Flutter clients are subsequent implementation milestones.
