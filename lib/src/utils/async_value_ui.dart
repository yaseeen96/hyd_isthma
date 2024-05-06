import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:st_flutter_starter_template/src/common_widgets/alert_dialogs.dart';

extension AsyncValueUI on AsyncValue<void> {
  void showAlertDialogOnError(BuildContext context) {
    if (!isLoading && hasError) {
      showExceptionAlertDialog(
          context: context, title: "Error", exception: error);
    }
  }
}
