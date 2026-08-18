#!/bin/bash
# Installs a daily cron job that runs daily_code_generator.py every day at 09:00 AM automatically.

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PYTHON_EXEC="$(which python3)"
GENERATOR_SCRIPT="$SCRIPT_DIR/daily_code_generator.py"

if [ -z "$PYTHON_EXEC" ]; then
  echo "❌ Error: python3 not found in PATH."
  exit 1
fi

CRON_JOB="0 9 * * * $PYTHON_EXEC $GENERATOR_SCRIPT > /dev/null 2>&1"

# Add cron job if not already present
(crontab -l 2>/dev/null | grep -v "$GENERATOR_SCRIPT" ; echo "$CRON_JOB") | crontab -

echo "========================================================"
echo "✅ Automated Daily Code Generator successfully scheduled!"
echo "⏰ Runs every day at 09:00 AM automatically on your Mac."
echo "📄 Script: $GENERATOR_SCRIPT"
echo "========================================================"
