#!/bin/bash
set -e

# Run the Python script using xvfb-run
xvfb-run -a python3 /view_youtube.py

# Exit the script
exit 0
