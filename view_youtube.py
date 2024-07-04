import os
from selenium import webdriver
from selenium.webdriver.firefox.options import Options
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time
import logging

# Ensure the log directory exists
log_dir = "/logs"
os.makedirs(log_dir, exist_ok=True)

# Set up logging for debugging
logging.basicConfig(
    level=logging.DEBUG,
    filename=os.path.join(log_dir, "view_youtube.log"),
    filemode='w',
    format='%(asctime)s - %(levelname)s - %(message)s'
)

options = Options()
options.headless = True

# Initialize the Firefox driver
try:
    logging.info("Initializing Firefox driver...")
    print("Initializing Firefox driver...")
    driver = webdriver.Firefox(options=options)
    logging.info("Firefox driver initialized successfully.")
    print("Firefox driver initialized successfully.")
except Exception as e:
    logging.error(f"Failed to initialize Firefox driver: {e}")
    print(f"Failed to initialize Firefox driver: {e}")
    exit(1)

try:
    # Navigate to the YouTube livestream
    livestream_url = 'https://youtube.com/live/dSxEbHUnG3A'  # New livestream URL
    logging.info(f"Navigating to YouTube livestream: {livestream_url}...")
    print(f"Navigating to YouTube livestream: {livestream_url}...")
    driver.get(livestream_url)
    logging.info("Navigated to YouTube livestream.")
    print("Navigated to YouTube livestream.")

    # Wait until the video player is loaded
    logging.info("Waiting for video player to load...")
    print("Waiting for video player to load...")
    WebDriverWait(driver, 30).until(
        EC.presence_of_element_located((By.CSS_SELECTOR, 'video.html5-main-video'))
    )
    logging.info("Video player loaded.")
    print("Video player loaded.")

    # Scroll the video element into view
    logging.info("Scrolling video element into view...")
    print("Scrolling video element into view...")
    video = driver.find_element(By.CSS_SELECTOR, 'video.html5-main-video')
    driver.execute_script("arguments[0].scrollIntoView(true);", video)

    # Play the video if it's not already playing
    if video.get_attribute("paused") == "true":
        logging.info("Video is paused, clicking play...")
        print("Video is paused, clicking play...")
        driver.execute_script("arguments[0].click();", video)
    else:
        logging.info("Video is already playing.")
        print("Video is already playing.")

    # Wait for 2 minutes to ensure the view is registered
    logging.info("Waiting for 2 minutes to ensure view is registered...")
    print("Waiting for 2 minutes to ensure view is registered...")
    time.sleep(120)  # 120 seconds = 2 minutes
    logging.info("Waited for 2 minutes.")
    print("Waited for 2 minutes.")

except Exception as e:
    logging.error(f"An error occurred: {e}")
    print(f"An error occurred: {e}")
finally:
    # Close the browser
    logging.info("Closing Firefox driver...")
    print("Closing Firefox driver...")
    driver.quit()
    logging.info("Firefox driver closed.")
    print("Firefox driver closed.")
