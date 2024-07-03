FROM ubuntu:20.04

# Install dependencies
RUN apt-get update && \
    apt-get install -y firefox xvfb wget \
    python3-pip libgtk-3-0 libdbus-glib-1-2 && \
    pip3 install selenium

# Install Geckodriver
RUN wget https://github.com/mozilla/geckodriver/releases/download/v0.34.0/geckodriver-v0.34.0-linux64.tar.gz && \
    tar -xvzf geckodriver-v0.34.0-linux64.tar.gz && \
    mv geckodriver /usr/local/bin/

# Copy the test script
COPY test_firefox.py /test_firefox.py

# Run the test script using xvfb
CMD ["xvfb-run", "-a", "python3", "/test_firefox.py"]
