import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:jih_ijtema_app/src/common_widgets/page_not_found.dart';
import 'package:jih_ijtema_app/src/features/home/presentation/home_screen.dart';

CustomTransitionPage buildPageWithDefaultTransition({
  required BuildContext context,
  required GoRouterState state,
  required Widget child,
}) {
  return CustomTransitionPage<void>(
    key: state.pageKey,
    child: child,
    transitionDuration: const Duration(milliseconds: 150),
    transitionsBuilder: (BuildContext context, Animation<double> animation,
        Animation<double> secondaryAnimation, Widget child) {
      // Change the opacity of the screen using a Curve based on the the animation's
      // value
      return FadeTransition(
        opacity: CurveTween(curve: Curves.easeInOut).animate(animation),
        child: child,
      );
    },
  );
}

final customRouter = GoRouter(
  initialLocation: "/",
  routes: [
    // GoRoute(
    //   path: "/",
    //   pageBuilder: (context, state) => buildPageWithDefaultTransition(
    //       context: context, state: state, child: const HomeScreen()),
    // )
  ],
  errorBuilder: (context, state) => const PageNotFound(),
);
