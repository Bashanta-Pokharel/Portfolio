#!/bin/bash
PLIST_SRC="/Users/bashantapokharel/Desktop/Bashanta/Portfolio/scripts/com.bashanta.dailycode.plist"
PLIST_DEST="$HOME/Library/LaunchAgents/com.bashanta.dailycode.plist"

mkdir -p "$HOME/Library/LaunchAgents"
cp "$PLIST_SRC" "$PLIST_DEST"
launchctl unload "$PLIST_DEST" 2>/dev/null
launchctl load "$PLIST_DEST"

echo "=========================================================="
echo "✅ macOS Background LaunchAgent successfully installed!"
echo "⏰ Runs automatically every day at 9:00 AM."
echo "=========================================================="
