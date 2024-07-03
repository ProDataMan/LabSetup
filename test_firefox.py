from selenium import webdriver
from selenium.webdriver.firefox.options import Options

options = Options()
options.headless = True

try:
    driver = webdriver.Firefox(options=options)
    driver.get("http://www.google.com")
    print(driver.title)
    driver.quit()
except Exception as e:
    print(f"Failed to initialize Firefox driver: {e}")
    exit(1)
