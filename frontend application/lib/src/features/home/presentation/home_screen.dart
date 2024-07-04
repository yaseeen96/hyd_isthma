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
  String initialUrl = "https://ijtema.jihhrd.com/";

  @override
  void initState() {
    super.initState();
    fetchFcmToken();
    handleNotificationOpened();
  }

  Future<void> fetchFcmToken() async {
    _fcmToken = await FirebaseMessaging.instance.getToken();
    logger.i("FCM token: $_fcmToken");
    setState(() {
      _isReady = true;
    });
  }

  void handleNotificationOpened() {
    FirebaseMessaging.onMessageOpenedApp.listen((RemoteMessage message) {
      logger.i("Notification clicked!");

      if (message.data.containsKey('url')) {
        setState(() {
          initialUrl = message.data['url'];
        });
      }
    });

    FirebaseMessaging.instance
        .getInitialMessage()
        .then((RemoteMessage? message) {
      if (message != null && message.data.containsKey('url')) {
        setState(() {
          initialUrl = message.data['url'];
        });
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: _isReady
          ? InAppWebView(
              initialUrlRequest: URLRequest(url: WebUri(initialUrl)),
              initialSettings: InAppWebViewSettings(
                javaScriptEnabled: true,
                supportZoom: false,
                javaScriptCanOpenWindowsAutomatically: true,
                useHybridComposition: false,
              ),
              onLoadStop: (controller, url) async {
                if (_fcmToken != null) {
                  await controller.evaluateJavascript(source: '''
                    localStorage.setItem("fcmtoken", "$_fcmToken");
                  ''');
                }
              },
              onReceivedError: (controller, request, error) {
                logger.e("Failed to load ${request.url}: ${error.description}");
              },
            )
          : const Center(child: CircularProgressIndicator()),
    );
  }
}
