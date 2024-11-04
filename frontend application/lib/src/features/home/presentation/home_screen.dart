import 'package:flutter/material.dart';
import 'package:flutter_native_splash/flutter_native_splash.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_inappwebview/flutter_inappwebview.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:jih_ijtema_app/src/utils/logger.dart';
import 'package:url_launcher/url_launcher.dart';

class HomeScreen extends ConsumerStatefulWidget {
  final String initialUrl;

  const HomeScreen({super.key, required this.initialUrl});

  @override
  ConsumerState<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends ConsumerState<HomeScreen> {
  late String? _fcmToken;
  bool _isReady = false;
  late InAppWebViewController _webViewController;

  @override
  void initState() {
    super.initState();
    fetchFcmToken();
    handleNotificationOpened();
    handleForegroundNotification();
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
        Navigator.of(context).pushReplacement(
          MaterialPageRoute(
            builder: (context) => HomeScreen(initialUrl: message.data['url']),
          ),
        );
      }
    });

    FirebaseMessaging.instance
        .getInitialMessage()
        .then((RemoteMessage? message) {
      if (message != null && message.data.containsKey('url')) {
        Navigator.of(context).pushReplacement(
          MaterialPageRoute(
            builder: (context) => HomeScreen(initialUrl: message.data['url']),
          ),
        );
      }
    });
  }

  void handleForegroundNotification() {
    FirebaseMessaging.onMessage.listen((RemoteMessage message) {
      logger.i("Foreground notification received!");

      if (message.data.containsKey('url')) {
        Navigator.of(context).pushReplacement(
          MaterialPageRoute(
            builder: (context) => HomeScreen(initialUrl: message.data['url']),
          ),
        );
      }
    });
  }

  Future<bool> _onWillPop() async {
    if (await _webViewController.canGoBack()) {
      _webViewController.goBack();
      return false;
    } else {
      return true;
    }
  }

  @override
  Widget build(BuildContext context) {
    return WillPopScope(
      onWillPop: _onWillPop,
      child: Scaffold(
        body: _isReady
            ? SafeArea(
                child: InAppWebView(
                  initialUrlRequest: URLRequest(url: WebUri(widget.initialUrl)),
                  initialSettings: InAppWebViewSettings(
                    javaScriptEnabled: true,
                    supportZoom: false,
                    javaScriptCanOpenWindowsAutomatically: true,
                    useHybridComposition: true,
                    allowFileAccessFromFileURLs: true,
                    allowFileAccess: true,
                    allowUniversalAccessFromFileURLs: true,
                    allowContentAccess: true,
                    mixedContentMode:
                        MixedContentMode.MIXED_CONTENT_ALWAYS_ALLOW,
                    useOnLoadResource: true,
                    selectionGranularity: SelectionGranularity.CHARACTER,
                  ),
                  onWebViewCreated: (controller) {
                    _webViewController = controller;
                  },
                  onLoadStop: (controller, url) async {
                    if (_fcmToken != null) {
                      await controller.evaluateJavascript(source: '''
                      localStorage.setItem("fcmtoken", "$_fcmToken");
                      ''');
                      FlutterNativeSplash.remove();
                    }
                  },
                  onReceivedError: (controller, request, error) {
                    logger.e(
                        "Failed to load ${request.url}: ${error.description}");
                  },
                  shouldOverrideUrlLoading:
                      (controller, navigationAction) async {
                    final uri = navigationAction.request.url;

                    if (uri != null) {
                      if (uri.scheme == 'mailto' || uri.scheme == 'tel') {
                        // Handle mailto and tel links by opening them in external applications
                        if (await canLaunchUrl(uri)) {
                          await launchUrl(uri,
                              mode: LaunchMode.externalApplication);
                          return NavigationActionPolicy
                              .CANCEL; // Prevent WebView from loading
                        }
                      }
                    }
                    return NavigationActionPolicy
                        .ALLOW; // Allow other URLs to load in WebView
                  },
                ),
              )
            : const Center(child: CircularProgressIndicator()),
      ),
    );
  }
}
