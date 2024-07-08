from selenium import webdriver
from selenium.webdriver.chrome.options import Options
import logging
import time

# Set up logging for debugging
logging.basicConfig(
    level=logging.DEBUG,
    filename="/home/ubuntu/LabSetup/logs/view_youtube_minimal.log",
    filemode='w',
    format='%(asctime)s - %(levelname)s - %(message)s'
)

options = Options()
options.headless = True
options.add_argument('--no-sandbox')
options.add_argument('--disable-dev-shm-usage')

driver = None

# Initialize the Chromium driver
try:
    logging.info("Initializing Chromium driver...")
    driver = webdriver.Chrome(options=options)
    logging.info("Chromium driver initialized successfully.")

    # Navigate to the YouTube livestream URL
    livestream_url = "https://youtu.be/g6lPeleTHiw"
    logging.info(f"Navigating to YouTube livestream: {livestream_url}")
    driver.get(livestream_url)
    logging.info("Navigated to YouTube livestream.")

    # Wait for the play button and click it if it exists
    time.sleep(5)  # Allow the page to load
    play_button = None
    try:
        play_button = driver.find_element("css selector", "button.ytp-large-play-button")
        if play_button:
            play_button.click()
            logging.info("Clicked the play button. If autoplay is enabled this will pause the video")
            play_button.click()
            logging.info("Clicked the play button again. if last click paused this click should play")
    except Exception as e:
        logging.info(f"No play button found: {e}")

    # Wait to ensure the view is registered
    wait_time = 1 * 60  # 2 minutes
    logging.info(f"Waiting for {wait_time} seconds to ensure view is registered...")
    time.sleep(wait_time)
    logging.info("Waited for 2 minutes.")

except Exception as e:
    logging.error(f"Failed to initialize Chromium driver or navigate: {e}")

finally:
    if driver:
        driver.quit()
        logging.info("Chromium driver closed.")
