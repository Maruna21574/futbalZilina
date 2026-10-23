#!/bin/bash
# použitie: shot.sh <cesta> <sirka> <vyska> <vystup>
"/c/Program Files/Google/Chrome/Application/chrome.exe" --headless=new --blink-settings=scriptEnabled=false --disable-gpu --hide-scrollbars --force-device-scale-factor=1 --virtual-time-budget=4000 --window-size=$2,$3 --screenshot="C:/laragon/www/futbalZilina/tools/shots/$4.png" "http://127.0.0.1:8080/$1" >/dev/null 2>&1
