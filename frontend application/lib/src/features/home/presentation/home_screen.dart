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
                  onDownloadStartRequest: (controller, url) async {
                    // Use url_launcher to open file links externally
                    if (await canLaunchUrl(url.url)) {
                      await launchUrl(
                        url.url,
                        mode: LaunchMode.externalApplication,
                      );
                    } else {
                      logger.e("Could not launch ${url.url}");
                    }
                  },
                  shouldOverrideUrlLoading:
                      (controller, navigationAction) async {
                    final uri = navigationAction.request.url;

                    if (uri != null) {
                      logger.i("Attempting to load URL: $uri");

                      // Handle intent scheme for Android-specific URLs
                      if (uri.scheme == 'intent') {
                        final fallbackUrl = Uri.parse(uri
                                .queryParameters['S.browser_fallback_url'] ??
                            'https://play.google.com/store/apps/details?id=com.google.android.apps.maps');

                        if (await canLaunchUrl(fallbackUrl)) {
                          logger.i(
                              "Opening intent URL in external browser: $fallbackUrl");
                          await launchUrl(fallbackUrl,
                              mode: LaunchMode.externalApplication);
                          return NavigationActionPolicy.CANCEL;
                        } else {
                          logger.e("Failed to open fallback URL: $fallbackUrl");
                        }
                      }

                      // Define downloadable file types
                      final downloadableExtensions = [
                        '.pdf',
                        '.zip',
                        '.docx',
                        '.xlsx'
                      ];

                      // Check if URL is a downloadable link based on extension
                      final isDownloadableLink = downloadableExtensions
                          .any((ext) => uri.path.endsWith(ext));

                      // If URL is a downloadable link or requires external handling, open it externally
                      if (isDownloadableLink) {
                        if (await canLaunchUrl(uri)) {
                          logger.i("Opening in external browser: $uri");
                          await launchUrl(uri,
                              mode: LaunchMode.externalApplication);
                          return NavigationActionPolicy.CANCEL;
                        } else {
                          logger.e("Failed to open downloadable URL: $uri");
                        }
                      }

                      // External link handling for specific schemes and domains
                      final isExternalLink = uri.scheme == 'mailto' ||
                          uri.scheme == 'tel' ||
                          uri.host == 'api.whatsapp.com' ||
                          uri.host == 'maps.google.com' ||
                          uri.scheme == 'geo';

                      if (isExternalLink) {
                        if (await canLaunchUrl(uri)) {
                          logger.i("Opening in external browser: $uri");
                          await launchUrl(uri,
                              mode: LaunchMode.externalApplication);
                          return NavigationActionPolicy.CANCEL;
                        } else {
                          logger.e("Failed to open external URL: $uri");
                        }
                      }
                    }

                    return NavigationActionPolicy.ALLOW;
                  },
                ),
              )
            : const Center(child: CircularProgressIndicator()),
      ),
    );
  }
}
