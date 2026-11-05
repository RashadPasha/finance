import 'package:flutter/material.dart';
import 'screens.dart';
import 'theme.dart';

void main() {
  WidgetsFlutterBinding.ensureInitialized();
  runApp(const RashadFinanceApp());
}
class RashadFinanceApp extends StatelessWidget {
  const RashadFinanceApp({super.key});
  @override
  Widget build(BuildContext context) => MaterialApp(
    title: 'RashadFinance',
    debugShowCheckedModeBanner: false,
    theme: buildAppTheme(),
    home: const MainShell(),
  );
}
class MainShell extends StatefulWidget {
  const MainShell({super.key});
  @override
  State<MainShell> createState() => _MainShellState();
}
class _MainShellState extends State<MainShell> {
  int _index = 0;
  final _pages = const [HomeScreen(), BlogScreen(), ServicesScreen(), ProfileScreen()];
  @override
  Widget build(BuildContext context) => Scaffold(
    body: IndexedStack(index: _index, children: _pages),
    floatingActionButton: _index == 0 ? FloatingActionButton.extended(
      onPressed: () => Navigator.of(context).push(MaterialPageRoute(builder: (_) => const AssistantScreen())),
      icon: const Icon(Icons.auto_awesome), label: const Text('AI köməkçi')) : null,
    bottomNavigationBar: NavigationBar(
      selectedIndex: _index,
      onDestinationSelected: (value) => setState(() => _index = value),
      destinations: const [
        NavigationDestination(icon: Icon(Icons.home_outlined), selectedIcon: Icon(Icons.home), label: 'Ana səhifə'),
        NavigationDestination(icon: Icon(Icons.article_outlined), selectedIcon: Icon(Icons.article), label: 'Bloq'),
        NavigationDestination(icon: Icon(Icons.work_outline), selectedIcon: Icon(Icons.work), label: 'Xidmətlər'),
        NavigationDestination(icon: Icon(Icons.person_outline), selectedIcon: Icon(Icons.person), label: 'Profil'),
      ],
    ),
  );
}
