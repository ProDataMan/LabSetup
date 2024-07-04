FROM ubuntu:20.04

# Set environment variables to non-interactive
ENV DEBIAN_FRONTEND=noninteractive

# Set timezone environment variable
ENV TZ=America/New_York

# Install dependencies and set the timezone
RUN apt-get update && \
    apt-get install -y tzdata && \
    ln -fs /usr/share/zoneinfo/$TZ /etc/localtime && \
    dpkg-reconfigure --frontend noninteractive tzdata

# Install other dependencies and set the locale
RUN apt-get install -y firefox xvfb wget \
    python3-pip libgtk-3-0 libdbus-glib-1-2 locales && \
    locale-gen en_US.UTF-8 && \
    update-locale LANG=en_US.UTF-8

ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

# Install Selenium
RUN pip3 install selenium

# Install Geckodriver
RUN wget https://github.com/mozilla/geckodriver/releases/download/v0.34.0/geckodriver-v0.34.0-linux64.tar.gz && \
    tar -xvzf geckodriver-v0.34.0-linux64.tar.gz && \
    mv geckodriver /usr/local/bin/

# Copy the test script
COPY view_youtube.py /view_youtube.py

# Copy the entrypoint script
COPY entrypoint.sh /entrypoint.sh

# Make the entrypoint script executable
RUN chmod +x /entrypoint.sh

# Create a directory for logs
RUN mkdir /logs

# Set the entrypoint to the entrypoint script
ENTRYPOINT ["/entrypoint.sh"]
