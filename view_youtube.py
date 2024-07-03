from selenium import webdriver
from selenium.webdriver.firefox.options import Options
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time
import logging

# Set up logging for debugging
from selenium.webdriver.remote.remote_connection import LOGGER
LOGGER.setLevel(logging.DEBUG)

# Set up Firefox options
options = Options()
options.headless = True

try:
    # Initialize the Firefox driver
    driver = webdriver.Firefox(options=options)
except Exception as e:
    print(f"Failed to initialize Firefox driver: {e}")
    exit(1)

try:
    # Navigate to the YouTube livestream
    driver.get('https://www.youtube.com/live/EdPaNTT71Ag')

    # Wait for the page to load and the play button to appear
    WebDriverWait(driver, 30).until(
        EC.presence_of_element_located((By.CSS_SELECTOR, 'button.ytp-large-play-button'))
    )

    # Click the play button if it appears
    play_button = driver.find_element(By.CSS_SELECTOR, 'button.ytp-large-play-button')
    play_button.click()

    # Wait for a while to ensure the view is registered
    time.sleep(300)  # 300 seconds = 5 minutes

except Exception as e:
    print(f"An error occurred: {e}")
finally:
    # Close the browser
    driver.quit()
