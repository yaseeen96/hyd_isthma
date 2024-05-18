# hyd_isthma_app

Event Management Solution for Hyderabad Isthma

## Getting Started

1. **Get the template**

   Create your project using this repo by either:

   - Clone this repo to your local machine, or
   - fork this repo to your own.

2. **Install dependencies**

   Run `flutter pub get` to fetch dependencies.

3. **Application Bundle Name**

   Now to change your app's package name/bundle identifier in both Android and iOS manifests, run `flutter pub run change_app_package_name:main <com.new.package.name>`.

   - This step uses [change_app_package_name](https://pub.dev/packages/change_app_package_name), go give the package some love.

4. **Application Name**

   Next, you'll need to change your app's user-readable label - the `CFBundleName` and/or `CFBundleDisplayName` within the `Info.plist` (for iOS) and `android:label` field in your application node in `AndroidManifest.xml` (for Android).

   ```
   path: android/app/src/main/AndroidManifest.xml
   ```

   I'm afraid this step is manual; it would be cool if `change_app_package_name` could do this for you.

   **NOTE**: You'll also need to change your package `name` and `description` within `pubspec.yaml`

5. **App Icons**

   Then we'll auto-generate your app launcher icons using the [flutter_launcher_icons](https://pub.dev/packages/flutter_launcher_icons) package.

   - Copy the image you want to make your launcher icons out of to `assets/icon/icon.png.`
   - Now run `flutter pub run flutter_launcher_icons`. This command will auto-generate Android and iOS launcher icons from the PNG file for the different DPIs and place them in their respective resource directories.

   **NOTE**: Check the [package documentation](https://pub.dev/packages/flutter_launcher_icons#book-guide) for more configuration options on generating launcher icons updating your `pubspec. yaml` accordingly.
   For example, you may want different icons for different platforms since Android allows you to use a transparent icon and iOS doesn't.
   However, the default configuration included in this template will be sufficient in most cases.

6. **Splash screen**

   We'll then generate native splash screens for both of our platforms which your app will display before loading is complete and for this, we'll use [flutter_native_splash](https://pub.dev/packages/flutter_native_splash).

   - Copy the image you want to be displayed at the center of your splash screen to `assets/splash/splash.png.`
   - To change the background color of your splash screen, go to your `pubspec.yaml` under `flutter_native_splash -> color` and put your preferred color code. The default is white.
   - Finally, run `flutter pub run flutter_native_splash:create` to generate your resources from the splash image and update your manifest files.

7. **Deploying**

   Before releasing your Android app, make sure to sign it by:

   [Generate a Keystore file](https://flutter.dev/docs/deployment/android#create-a-keystore) if you don't already have one. If you have one, ignore this step and go to the next.

   Go to `android/key.properties` and include your Keystore path, alias, and password.
