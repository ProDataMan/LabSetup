from selenium import webdriver
from selenium.webdriver.chrome.options import Options
import logging

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

    # Navigate to a simple URL
    driver.get("https://www.google.com")
    logging.info("Navigated to Google.")

    # Print the page title
    logging.info(f"Page title: {driver.title}")

except Exception as e:
    logging.error(f"Failed to initialize Chromium driver or navigate: {e}")

finally:
    if driver:
        driver.quit()
        logging.info("Chromium driver closed.")
