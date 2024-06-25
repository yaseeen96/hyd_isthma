import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_inappwebview/flutter_inappwebview.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:jih_ijtema_app/src/utils/logger.dart';

class HomeScreen extends ConsumerStatefulWidget {
  const HomeScreen({super.key});

  @override
  ConsumerState<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends ConsumerState<HomeScreen> {
  late String? _fcmToken;
  bool _isReady = false;

  @override
  void initState() {
    super.initState();
    // Retrieve the FCM token asynchronously
    fetchFcmToken();
  }

  Future<void> fetchFcmToken() async {
    _fcmToken = await FirebaseMessaging.instance.getToken();
    logger.i("FCM token: $_fcmToken");
    setState(() {
      _isReady = true;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: _isReady
          ? InAppWebView(
              initialUrlRequest:
                  URLRequest(url: WebUri("https://ijtema.jihhrd.com/")),
              initialSettings: InAppWebViewSettings(
                javaScriptEnabled: true,
                supportZoom: false,
              ),
              onLoadStop: (controller, url) async {
                if (_fcmToken != null) {
                  await controller.evaluateJavascript(source: '''
              localStorage.setItem("fcmtoken", "${_fcmToken}");
            ''');
                }
              },
              onReceivedError: (controller, request, error) {
                logger.e("Failed to load ${request.url}: ${error.description}");
              },
            )
          : Center(child: CircularProgressIndicator()),
    );
  }
}
