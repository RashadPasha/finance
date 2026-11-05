import 'dart:convert';
import 'package:http/http.dart' as http;

class ApiService {
  ApiService._();
  static final ApiService instance = ApiService._();
  static const String _configuredBase = String.fromEnvironment(
    'API_BASE_URL',
    defaultValue: 'https://YOUR-DOMAIN.com',
  );
  String get _base => _configuredBase.replaceFirst(RegExp(r'/+$'), '');

  Uri _uri(String path, [Map<String, String>? query]) {
    return Uri.parse(_base + '/' + path).replace(queryParameters: query);
  }

  Future<dynamic> get(String resource, [Map<String, String>? query]) async {
    final params = <String, String>{'resource': resource, ...?query};
    final response = await http.get(_uri('api/mobile.php', params),
        headers: {'Accept': 'application/json'}).timeout(const Duration(seconds: 20));
    final decoded = _decode(response);
    if (response.statusCode < 200 || response.statusCode >= 300) {
      throw ApiException(_message(decoded, 'Məlumat yüklənmədi.'));
    }
    if (decoded is Map && decoded['ok'] == false) {
      throw ApiException(_message(decoded, 'Sorğu uğursuz oldu.'));
    }
    return decoded is Map ? decoded['data'] : decoded;
  }

  Future<Map<String, dynamic>> postInquiry({required String fullName, required String email, required String phone, required String message}) async {
    final response = await http.post(_uri('api/mobile.php', {'resource': 'inquiry'}),
      headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
      body: jsonEncode({'full_name': fullName, 'email': email, 'phone': phone, 'message': message}))
      .timeout(const Duration(seconds: 20));
    final decoded = _decode(response);
    if (response.statusCode < 200 || response.statusCode >= 300 || decoded is! Map || decoded['ok'] != true) {
      throw ApiException(_message(decoded, 'Müraciət göndərilmədi.'));
    }
    return Map<String, dynamic>.from(decoded);
  }

  Future<String> askAssistant(String message, List<Map<String, String>> history) async {
    final response = await http.post(_uri('api/chat.php'),
      headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
      body: jsonEncode({'message': message, 'history': history}))
      .timeout(const Duration(seconds: 45));
    final decoded = _decode(response);
    if (response.statusCode < 200 || response.statusCode >= 300 || decoded is! Map || decoded['answer'] == null) {
      throw ApiException(_message(decoded, 'AI köməkçisi hazırda cavab verə bilmir.'));
    }
    return decoded['answer'].toString();
  }

  dynamic _decode(http.Response response) {
    try { return jsonDecode(utf8.decode(response.bodyBytes)); }
    catch (_) { throw ApiException('Serverdən düzgün cavab alınmadı (' + response.statusCode.toString() + ').'); }
  }
  String _message(dynamic value, String fallback) {
    if (value is Map && value['message'] != null) return value['message'].toString();
    return fallback;
  }
}
class ApiException implements Exception {
  ApiException(this.message);
  final String message;
  @override
  String toString() => message;
}
