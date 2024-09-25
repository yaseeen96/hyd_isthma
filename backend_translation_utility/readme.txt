----*Urdu to other languages Translation service*----
Version 0.4-09252024
Platform: Windows/Linux (Tested on Windows only)

I) Setup instructions:
----------------------
1. Manually Install Python 3.11.2

2. Ensure python path is set in system variables.
If python path is: C:\Users\LENOVO\AppData\Local\Programs\Python\Python311, then ensure below python paths are added in the System variable "Path" under:
	e.g.: C:\Users\LENOVO\AppData\Local\Programs\Python\Python311
	e.g.: C:\Users\LENOVO\AppData\Local\Programs\Python\Python311\Scripts

3. Run the following command: 
	pip install -r requirements.txt

II) Run Instructions:
---------------------
1. Set OpenAI API Key as environment variable: 
	setx OAI_API_KEY "insert-actual-api-key-here"

2. Open command prompt and execute the below commmand:
	command example 1: python translate_ur_to_other_languages.py translation_conf.cfg https://www.youtube.com/watch?v=NbREvqysmnI ta ma be ka dir_prefix

	command example 2: python translate_ur_to_other_languages.py translation_conf.cfg <path-to-mp3-file-on-local> ta ma be ka dir_prefix

Note: The last two arguments are two target languages in which the program will translate the audio file. Use below map to provide the correct target locale:
target_language_locale_map --> {'mr': 'Marathi', 'te': 'Telugu', 'be':'Bengali', 'ta':'Tamil', 'ka':'Kannada', 'ma': 'Malayalam', 'ur':'Urdu'}


III) Output:
------------
1. output path: The output will be generated in a new directory for example, process_ka_mr_13-09-2024-09-58-29.

2. The directory will have original file downloaded from youtube(mp4), converted audio file(mp3), transcripted Urdu text file, translated English and text & audio files(mp3) for the target languages.