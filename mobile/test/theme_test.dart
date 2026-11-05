import 'package:flutter_test/flutter_test.dart';
import 'package:rashadfinance/theme.dart';

void main() {
  test('RashadFinance theme uses the premium navy brand color', () {
    final theme = buildAppTheme();
    expect(theme.colorScheme.primary, navy);
    expect(theme.useMaterial3, isTrue);
  });
}
