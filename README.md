# Magento2

## Ubuntu + Apache2 + PHP 7.1 

Follow from step 1 to step 5 : [https://www.mageplaza.com/devdocs/how-install-magento-2-ubuntu.html](https://www.mageplaza.com/devdocs/how-install-magento-2-ubuntu.html) 

Step 6 : git clone 

    clone repo to /var/www/html 

```bash
sudo chown -R www-data:www-data /var/www/html/magento2/
sudo chmod -R 755 /var/www/html/magento2/
```

Step 7 : Install Magento2

 .....
 
 
-------------------------------------------------------------------------------------------------------------------------

Download magento2 with composer :

    cd /var/www/html
    
    composer create-project --repository=https://repo.magento.com/ magento/project-community-edition magento2

    username : 978037938a10c9d8856cf7e47cbf9497
    
    password : 97955d6e74e0a09e2ee789fd5f7e0c0f 
