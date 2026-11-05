# RashadFinance Flutter app

Android and iOS share one Flutter codebase in this folder.

## Features in this starter
- Premium navy/white design system
- Home dashboard backed by the PHP API
- Searchable and filterable finance articles
- Article details and view tracking
- Services and prices
- Projects and professional portfolio
- Contact / consultation form
- RashadFinance AI assistant through the existing server-side chat endpoint

## Requirements
- Flutter stable (Dart 3.3+)
- Android Studio / Android SDK for Android builds
- macOS + Xcode for iOS builds and signing

## Configure the API
The app uses the existing website backend. Set your public HTTPS domain at build/run time. Do not put database credentials or OpenAI keys in the app.

```bash
cd mobile
flutter create --project-name rashadfinance --org com.rashadfinance --platforms=android,ios .
flutter pub get
flutter run --dart-define=API_BASE_URL=https://YOUR-DOMAIN.com
```

The API endpoints are:
- `/api/mobile.php?resource=home`
- `/api/mobile.php?resource=articles`
- `/api/mobile.php?resource=article&slug=...`
- `/api/mobile.php?resource=services`
- `/api/mobile.php?resource=projects`
- `/api/mobile.php?resource=portfolio`
- `POST /api/mobile.php?resource=inquiry`
- `POST /api/chat.php`

## Build Android APK

```bash
flutter build apk --release --dart-define=API_BASE_URL=https://YOUR-DOMAIN.com
```

APK output:
`build/app/outputs/flutter-apk/app-release.apk`

## Build iOS

On macOS with Xcode:

```bash
flutter build ios --release --no-codesign --dart-define=API_BASE_URL=https://YOUR-DOMAIN.com
```

For App Store distribution, configure an Apple Developer account, bundle signing and provisioning profiles, then create a signed archive / IPA in Xcode or with Flutter's IPA build.

## Important
- The API server must be deployed over HTTPS and configured with the MySQL database.
- The OpenAI key stays on the PHP server as `OPENAI_API_KEY`; it is never bundled into this app.
- This repository currently contains the Flutter source and API integration. Native platform scaffolding and actual release builds must be generated with the Flutter SDK and verified on Android/iOS build environments.
