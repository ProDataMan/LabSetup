from selenium import webdriver
from selenium.webdriver.firefox.options import Options
import time
import logging

# Set up logging for debugging
logging.basicConfig(level=logging.DEBUG)

options = Options()
options.headless = True

# Initialize the Firefox driver
try:
    logging.info("Initializing Firefox driver...")
    driver = webdriver.Firefox(options=options)
    logging.info("Firefox driver initialized successfully.")
except Exception as e:
    logging.error(f"Failed to initialize Firefox driver: {e}")
    exit(1)

try:
    # Navigate to the YouTube livestream
    livestream_url = 'https://www.youtube.com/live/mYLnPskPI1Y'  # New livestream URL
    logging.info(f"Navigating to YouTube livestream: {livestream_url}...")
    driver.get(livestream_url)
    logging.info("Navigated to YouTube livestream.")

    # Wait for 2 minutes to ensure the view is registered
    logging.info("Waiting for 2 minutes to ensure view is registered...")
    time.sleep(120)  # 120 seconds = 2 minutes
    logging.info("Waited for 2 minutes.")

except Exception as e:
    logging.error(f"An error occurred: {e}")
finally:
    # Close the browser
    logging.info("Closing Firefox driver...")
    driver.quit()
    logging.info("Firefox driver closed.")
