import 'package:flutter/material.dart';
const navy = Color(0xFF102B4E);
const navyLight = Color(0xFF1D456B);
const ink = Color(0xFF152638);
const muted = Color(0xFF718091);
const canvas = Color(0xFFF5F7F9);

ThemeData buildAppTheme() {
  final scheme = ColorScheme.fromSeed(seedColor: navy, brightness: Brightness.light).copyWith(primary: navy, secondary: navyLight, surface: Colors.white);
  return ThemeData(
    useMaterial3: true,
    colorScheme: scheme,
    scaffoldBackgroundColor: canvas,
    appBarTheme: const AppBarTheme(backgroundColor: Colors.white, foregroundColor: ink, elevation: 0, centerTitle: false, titleTextStyle: TextStyle(color: ink, fontSize: 19, fontWeight: FontWeight.w800)),
    cardTheme: CardThemeData(color: Colors.white, elevation: 0, margin: EdgeInsets.zero, shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16), side: const BorderSide(color: Color(0xFFE5EAF0)))),
    inputDecorationTheme: InputDecorationTheme(filled: true, fillColor: Colors.white, border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: Color(0xFFDCE3E9))), enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: Color(0xFFDCE3E9))), contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 14)),
    navigationBarTheme: NavigationBarThemeData(backgroundColor: Colors.white, indicatorColor: const Color(0xFFE8EEF4), labelTextStyle: WidgetStateProperty.all(const TextStyle(fontSize: 11, fontWeight: FontWeight.w600))),
    floatingActionButtonTheme: const FloatingActionButtonThemeData(backgroundColor: navy, foregroundColor: Colors.white),
  );
}
