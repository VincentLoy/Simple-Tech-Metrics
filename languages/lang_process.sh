#!/bin/bash

# Script to manage language files for Simple Tech Metrics
# Usage:
#   ./lang_process.sh --compile            # Compile all .po files to .mo
#   ./lang_process.sh --compile fr_FR      # Compile only fr_FR.po to fr_FR.mo
#   ./lang_process.sh --update             # Update .pot file from PHP source files
#   ./lang_process.sh --update fr_FR       # Update and create fr_FR.po if it doesn't exist
#   ./lang_process.sh --help               # Display help

# Paths and filenames
LANG_DIR="$(dirname "$0")"
POT_FILE="$LANG_DIR/simple-tech-metrics.pot"
PHP_FILES=$(find .. -name "*.php")

# Functions
function compile_mo_files() {
    if [ -z "$1" ]; then
        echo "Compiling all .po files into .mo files..."
        for file in "$LANG_DIR"/*.po; do
            if [ -f "$file" ]; then
                mo_file="${file%.po}.mo"
                msgfmt "$file" -o "$mo_file"
                echo "Compiled: $file -> $mo_file"
            else
                echo "No .po files found to compile."
            fi
        done
    else
        local lang_code="$1"
        local po_file="$LANG_DIR/simple-tech-metrics-$lang_code.po"
        local mo_file="$LANG_DIR/simple-tech-metrics-$lang_code.mo"

        if [ -f "$po_file" ]; then
            msgfmt "$po_file" -o "$mo_file"
            echo "Compiled: $po_file -> $mo_file"
        else
            echo "Error: $po_file does not exist."
        fi
    fi
}

function update_pot_file() {
    echo "Updating .pot file from source PHP files..."

    xgettext --language=PHP --keyword=__ --keyword=_e --from-code=UTF-8 \
        --output="$POT_FILE" \
        --add-comments=TRANSLATORS \
        --package-name="Simple Tech Metrics" \
        --package-version="1.0.0" \
        --msgid-bugs-address="support@example.com" \
        --copyright-holder="Simple Tech Metrics Contributors" \
        --no-wrap $PHP_FILES

    sed -i "1,2s|^# SOME DESCRIPTIVE TITLE.|# SIMPLE TECH METRICS\n# Copyright (C) $(date +%Y) Simple Tech Metrics Contributors|" "$POT_FILE"

    echo "Updated: $POT_FILE"

    if [ -n "$1" ]; then
        local lang_code="$1"
        local po_file="$LANG_DIR/simple-tech-metrics-$lang_code.po"

        if [ ! -f "$po_file" ]; then
            echo "Creating new .po file for $lang_code..."
            msginit --locale="$lang_code" --input="$POT_FILE" --output="$po_file" --no-translator
            echo "Created: $po_file"
        else
            echo "$po_file already exists. No new file created."
        fi
    fi
}

function show_help() {
    echo "Usage: ./lang_process.sh [OPTIONS] [LANG_CODE]"
    echo ""
    echo "Options:"
    echo "  --compile [LANG_CODE]   Compile .po files into .mo files."
    echo "                          If LANG_CODE is provided, only that file will be compiled."
    echo ""
    echo "  --update [LANG_CODE]    Update the .pot file and optionally create a new .po file."
    echo "                          If LANG_CODE is provided, create or update that language's .po file."
    echo ""
    echo "  --help                  Show this help message."
    echo ""
    echo "Requirements:"
    echo "  This script relies on the 'gettext' package, which provides tools like 'msgfmt' and 'xgettext'."
    echo ""
    echo "Installation:"
    echo "  On Debian/Ubuntu, install gettext with:"
    echo "      sudo apt update && sudo apt install gettext"
    echo ""
    echo "Examples:"
    echo "  Compile all translations:"
    echo "      ./lang_process.sh --compile"
    echo ""
    echo "  Compile French only:"
    echo "      ./lang_process.sh --compile fr_FR"
    echo ""
    echo "  Update the .pot template:"
    echo "      ./lang_process.sh --update"
    echo ""
    echo "  Update and create French .po if needed:"
    echo "      ./lang_process.sh --update fr_FR"
}

# Argument parsing
case "$1" in
    --compile)
        compile_mo_files "$2"
        ;;
    --update)
        update_pot_file "$2"
        ;;
    --help | *)
        show_help
        ;;
esac
