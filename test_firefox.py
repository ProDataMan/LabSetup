from selenium import webdriver
from selenium.webdriver.firefox.options import Options
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
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
    logging.info("Navigating to YouTube livestream...")
    driver.get('https://www.youtube.com/live/EdPaNTT71Ag')
    logging.info("Navigated to YouTube livestream.")

    # Wait for the page to load and the play button to appear
    logging.info("Waiting for play button to appear...")
    WebDriverWait(driver, 30).until(
        EC.presence_of_element_located((By.CSS_SELECTOR, 'button.ytp-large-play-button'))
    )
    logging.info("Play button found.")

    # Click the play button if it appears
    play_button = driver.find_element(By.CSS_SELECTOR, 'button.ytp-large-play-button')
    logging.info("Clicking play button...")
    play_button.click()
    logging.info("Play button clicked. Video should be playing.")

    # Wait for a while to ensure the view is registered
    logging.info("Waiting for 5 minutes to ensure view is registered...")
    time.sleep(300)  # 300 seconds = 5 minutes
    logging.info("Waited for 5 minutes.")

except Exception as e:
    logging.error(f"An error occurred: {e}")
finally:
    # Close the browser
    logging.info("Closing Firefox driver...")
    driver.quit()
    logging.info("Firefox driver closed.")
