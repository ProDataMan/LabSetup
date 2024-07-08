from selenium import webdriver
from selenium.webdriver.firefox.options import Options
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

driver = None

# Initialize the Firefox driver
try:
    logging.info("Initializing Firefox driver...")
    driver = webdriver.Firefox(options=options)
    logging.info("Firefox driver initialized successfully.")

    # Navigate to a simple URL
    driver.get("https://www.google.com")
    logging.info("Navigated to Google.")

    # Print the page title
    logging.info(f"Page title: {driver.title}")

except Exception as e:
    logging.error(f"Failed to initialize Firefox driver or navigate: {e}")

finally:
    if driver:
        driver.quit()
        logging.info("Firefox driver closed.")
