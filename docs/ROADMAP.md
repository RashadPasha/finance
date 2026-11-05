# RashadFinance delivery roadmap

## Phase 1 — foundation (committed)
- Premium public landing page (navy / white / charcoal)
- Initial MySQL schema for articles, nested categories, videos, services, projects, portfolio, inquiries, analytics and chatbot logs
- Consultation inquiry endpoint
- Protected admin sign-in and dashboard shell
- Secret-safe configuration template

## Phase 2 — admin content management
- Full CRUD for articles, category hierarchy and ordering
- Article image upload with validation, rich text and external links
- YouTube video management and safe embed rendering
- Service pricing and descriptions
- Projects / case studies
- Portfolio profile editor
- Inquiry pipeline and article analytics

## Phase 3 — AI assistant
- Server-side OpenAI integration (API key in environment only)
- Retrieval from published articles, services, projects and profile
- Rate limits, input limits, privacy notice and conversation logging
- Clear distinction between general AI output and information sourced from this site

## Phase 4 — mobile
- Flutter Android + iOS app
- Public API, auth where required, article/video/service/project screens
- Push notifications and deep links

## Release acceptance
- Production HTTPS and secure headers
- CSRF protection on all admin mutations
- Role-based access, upload MIME/size checks and randomized filenames
- Prepared SQL statements, rate limiting and audit logs
- Database backup and restore test
- Android APK/AAB build; iOS archive requires macOS/Xcode and Apple signing credentials
