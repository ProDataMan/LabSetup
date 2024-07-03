from selenium import webdriver
from selenium.webdriver.firefox.options import Options

options = Options()
options.headless = True

driver = webdriver.Firefox(options=options)
driver.get("http://www.google.com")
print(driver.title)
driver.quit()
