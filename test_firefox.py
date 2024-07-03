from selenium import webdriver
from selenium.webdriver.firefox.options import Options
from selenium.webdriver.firefox.service import Service
import time
import logging

# Set up logging for debugging
from selenium.webdriver.remote.remote_connection import LOGGER
LOGGER.setLevel(logging.DEBUG)

# Set up Firefox options
options = Options()
options.headless = True

# Define Firefox service with explicit Marionette port and log path
service = Service(log_path='/tmp/geckodriver.log', service_args=['--marionette-port', '2828'])

try:
    # Initialize the Firefox driver with the specified service
    driver = webdriver.Firefox(service=service, options=options)
    driver.get("http://www.google.com")
    print(driver.title)
    driver.quit()
except Exception as e:
    print(f"Failed to initialize Firefox driver: {e}")
    exit(1)
