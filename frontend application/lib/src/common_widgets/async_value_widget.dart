import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:jih_ijtema_app/src/common_widgets/error_message_widget.dart';

class AsyncValueWidget<T> extends StatelessWidget {
  const AsyncValueWidget({
    super.key,
    required this.value,
    required this.data,
  });
  final AsyncValue<T> value;
  final Widget Function(T) data;

  @override
  Widget build(BuildContext context) {
    return value.when(
        data: data,
        error: (err, stk) {
          return Center(child: ErrorMessageWidget(err.toString()));
        },
        loading: () {
          return const Center(
            child: CircularProgressIndicator(),
          );
        });
  }
}
