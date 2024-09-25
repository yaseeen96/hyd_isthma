import ast
from configparser import RawConfigParser
from datetime import datetime
import json
import logging
import os
import pdb
import re
import shutil
import sys
import time
import yt_dlp

from moviepy.editor import concatenate_audioclips, AudioFileClip, VideoFileClip
from openai import OpenAI

from moviepy.video.io.ffmpeg_tools import ffmpeg_extract_subclip


def set_logger(log_level="DEBUG"):
    if log_level == "DEBUG":
        log_level = logging.DEBUG
    else:
        log_level = logging.INFO
    logger = logging.getLogger(__name__)
    logging.basicConfig(level=log_level)
    file_handler = logging.FileHandler(__file__.split(os.path.sep)[-1].split(".py")[0] + ".log")
    formatter = logging.Formatter("%(asctime)s - %(levelname)s - %(message)s")
    file_handler.setFormatter(formatter)
    logger.addHandler(file_handler)
    return logger

#Set logger
logger = set_logger(log_level="INFO")

def log(logger=logger, message=None):
    logger.info(message)
    print(message)

log(message="Processing starts")
if len(sys.argv) < 5:
    log(message=f"""Not enough arguments to process!\n usage: python translate_ur_to_other_languages.py translation_conf.cfg D:\JIH\SpeechToSpeech\code\version0.3\Psychology_Ameenul_Hasan.mp3 ma ta dir_prefix\n""")
    os._exit(1)

translation_conf = sys.argv[1]
if not (os.path.isfile(translation_conf)):
    log(message=f"Provided translation config file:{translation_conf} is not accessible, try again")
    os._exit(1)

input_resource = sys.argv[2]
directory_prefix = sys.argv[7]

#Check if provided input resource is a file or a youtube URL
youtube_flag = False
file_flag = False
if "youtube.com" in input_resource:
    log(message=f"Provided input resource :{input_resource} is a youtube URL")
    youtube_flag = True
elif os.path.isfile(input_resource):
    log(message=f"Provided input resource :{input_resource} is a file on the disk")
    file_flag = True
else:
    log(message=f"Provided input resource :{input_resource} is neither a youtube URL nor a file, try again")
    os._exit(1)

#Read configuration for translation service
config = RawConfigParser()
config.read(translation_conf)

inp_formats = config.get("translation_service_config", "input_formats")
supported_input_formats = inp_formats.split(",")

locale_to_language_map = ast.literal_eval(json.loads(config.get("translation_service_config", "locale_to_language_map")))

replacement_string = json.loads(config.get("translation_service_config", "replacement_string"))

speech_to_text_model = config.get("openai_model_config", "speech_to_text_model") #whisper-1
text_to_text_model = config.get("openai_model_config", "text_to_text_model") #gpt-3.5-turbo-0125
text_to_text_trans_threshold_in_bytes = config.get("openai_model_config", "text_to_text_trans_threshold_in_bytes") #3000
text_to_audio_model = config.get("openai_model_config", "text_to_audio_model") #tts-1
target_voice_bot = config.get("openai_model_config", "target_voice_bot") #onyx

target_locale1 = sys.argv[3]
target_locale2 = sys.argv[4]
target_locale3 = sys.argv[5]
target_locale4 = sys.argv[6]

supported_locales = locale_to_language_map.keys()
if not (target_locale1 in supported_locales and target_locale2 in supported_locales and target_locale3 in supported_locales and target_locale4 in supported_locales):
    log(message=f"Supported locales are: {','.join(supported_locales).strip(',')}, try again!")
    os._exit(1)

current_utc_time = datetime.utcnow().strftime("%d-%m-%Y-%H-%M-%S")
#input_dir = os.path.dirname(input_file)
process_dir = os.path.join(os.getcwd(), f"{directory_prefix}_process_{target_locale1}_{target_locale2}_{current_utc_time}")
log(message=f"All set, started processing {input_resource} in {process_dir}")
if os.path.exists(process_dir):
    #yes_or_no = input(f"Folder {process_dir} already exists, do you want to re-create it? Type Yes/No: ")
    yes_or_no = "no"
    if yes_or_no.lower() == "yes":
        os.remove(process_dir)
        os.mkdir(process_dir)
else:
    os.mkdir(process_dir)
os.chdir(process_dir)

def calculate_time(start_yt=None, operation_string=""):
    end_yt = time.time()
    time_taken_in_seconds = end_yt - start_yt
    minutes = int(time_taken_in_seconds / 60)
    seconds = int(time_taken_in_seconds % 60)
    log(message=f"Time taken to {operation_string} is {minutes} minutes & {seconds} seconds!")

def download_yt_resource(input_youtube_url):
    start_yt = time.time()
    url = yt_dlp.YoutubeDL().download(input_youtube_url)
    if url != 0:
        log(message=f"Unable to download provided youtube URL {input_youtube_url}!")
        os._exit(1)

    video = VideoFileClip(os.path.join(os.getcwd(), os.listdir()[0]))
    base_name = os.listdir()[0].encode("utf-8").decode("ascii", "ignore")
    base_name_fixed = ""
    if base_name.endswith("mp4"):
        base_name_fixed = base_name.replace(" ", "_").replace(".mp4", ".mp3")
    else:
        base_name_tmp = base_name.replace(" ", "_")
        base_name_fixed = base_name_tmp.replace(base_name_tmp.split(".")[-1], "mp3")

    input_file = os.path.join(os.getcwd(), base_name_fixed)
    video.audio.write_audiofile(input_file)

    calculate_time(start_yt, "download youtube video & convert into audio(mp3)")
    return input_file

base_name_fixed = None
if youtube_flag:
    base_name_fixed = download_yt_resource(input_resource)
if file_flag:
    base_name_fixed = input_resource

file_ext = base_name_fixed.split(".")[-1]
if not(file_ext in supported_input_formats):
    log(message=f"provided input file type {file_ext} is not supported, supported types are: {supported_input_formats}!")
    os._exit(1)

#Fetch API Key to access OpenAI API
api_key = os.environ["OAI_API_KEY"]

log(message="Successfully fetched API Key from env variables")

client = OpenAI(api_key=api_key)

count = 1

src_duration = int(AudioFileClip(base_name_fixed).duration) #in seconds, the duration of the original audio
duration_of_clip = 40
total_clips = int(src_duration/duration_of_clip) #in seconds, duration of individual audio clip
start_smaller_audios = time.time()
for i in range(0, src_duration, duration_of_clip):
    sart_index = i+1
    closing_index = i + duration_of_clip
    ffmpeg_extract_subclip(base_name_fixed, sart_index, closing_index, targetname=f"audio_{count}.mp3")
    count += 1

calculate_time(start_smaller_audios, "Divide audio file into smaller ones")
eng_text_file = open("english_transcript.txt", "a")
start_ur_audio_eng_text = time.time()
transcripted_data = ""
for count in range(1, count):
    audio_file = open(f"audio_{count}.mp3", "rb")
    transcript = client.audio.translations.create(model=speech_to_text_model,file=audio_file)
    eng_text_file.write(transcript.text)
    transcripted_data = transcripted_data + " " + transcript.text
    audio_file.close()

eng_text_file.close()
calculate_time(start_ur_audio_eng_text, "Generate English translation from Urdu audio file <api call>")

log(message=f"Generated English text from input audo file")
size_in_bytes = sys.getsizeof(transcripted_data)
log(message=f"Size of transcripted input data in Bytes: {size_in_bytes}")

def translate_locale(target_locale=""):
    os.chdir(process_dir)
    target_lang = locale_to_language_map[target_locale]
    string_size = 0
    chunked_strings = []
    chunked_string = ""
    delimiter = ". "
    log(message=f"Translating the data into target locale {target_lang} into batches each of size {text_to_text_trans_threshold_in_bytes} Bytes")
    #pdb.set_trace()
    sentences = transcripted_data.split(delimiter)
    start_time = time.time()
    for index, sentence in enumerate(sentences):
        if chunked_string:
            chunked_string = chunked_string + delimiter + sentence
        else:
            chunked_string = chunked_string + sentence
        if sys.getsizeof(chunked_string) >= int(text_to_text_trans_threshold_in_bytes) or index == (len(sentences) - 1):
            #pdb.set_trace()
            result_string = chunked_string.strip(".").strip(" ") + "."
            chat_response = client.chat.completions.create(messages = [{"role": "system", "content": f"Please translate the following text into {target_lang}"}, {"role": "user", "content":result_string}], model = text_to_text_model)
            #, max_tokens = sys.getsizeof(chunked_string)
            result_string = chat_response.model_dump()["choices"][0]["message"]["content"]
            calculate_time(start_time, f"Generate {target_lang} text translation from English text(per threshold) <api call>")
            log(message=f"Translated String size: {sys.getsizeof(result_string)}, translated string: {result_string}")
            result_string = result_string.replace("(Error in recognition)", replacement_string).replace("(Continued)", replacement_string)
            chunked_strings.append(result_string + "\n")
            chunked_string = ""
            log(message=f"currently at index: {index}")
            start_time = time.time()
    target_lang_file = base_name_fixed.replace("." + file_ext, "_" + target_lang.lower() +".txt")
    target_trans_fd = open(target_lang_file, "w", encoding="utf-8")
    target_trans_fd.write("".join(chunked_strings))
    target_trans_fd.close()
    log(message=f"Written {target_lang} translation into the file {target_lang_file}")

    audio_clip_paths = []
    log(message="Text chunking/translation done, generating audio files now.")
    #pdb.set_trace()
    if target_locale == "ur":
        #Do not generate audio in case of Urdu
        return
    for index, chunked_translation in enumerate(chunked_strings):
        log(message=f"Generating audio file #{index}")
        start_time = time.time()
        onyx_audio_response = client.audio.speech.create(model=text_to_audio_model, voice=target_voice_bot, input=chunked_translation)
        chunked_file = base_name_fixed.replace("." + file_ext, f"_delete_{target_locale}" + str(index) + "." + file_ext)
        onyx_audio_response.write_to_file(chunked_file)
        calculate_time(start_time, f"Generate chunked audio from text for {target_lang}")
        log(message=f"Generated audio file: {chunked_file} #{index} <api call>")
        audio_clip_paths.append(chunked_file)

    log(message=f"Chunked audio files are: {audio_clip_paths}")
    log(message=f"Time to put it all together, merging audio files")
    start_time = time.time()
    clips = [AudioFileClip(c) for c in audio_clip_paths]
    final_clip = concatenate_audioclips(clips)
    #target_lang_audio_file_path = base_name.replace("." + file_ext, f"_{target_locale}." + file_ext)
    target_lang_audio_file_path = base_name_fixed.replace("." + file_ext, f"_{target_lang.lower()}." + file_ext)
    final_clip.write_audiofile(target_lang_audio_file_path)
    log(message=f"Audio file in target language {target_lang} has been generated at: {target_lang_audio_file_path}")
    calculate_time(start_time, f"Generate Final audio from text for {target_lang} <api call>")

translate_locale(target_locale="ur")
translate_locale(target_locale=target_locale1)
translate_locale(target_locale=target_locale2)
translate_locale(target_locale=target_locale3)
translate_locale(target_locale=target_locale4)

log(message="Deleting intermediary files")
for _file in os.listdir():
    if "delete" in _file:
        os.remove(_file)
log(message="Processing ends")
