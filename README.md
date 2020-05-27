# Magento2

## Ubuntu + Apache2 + PHP 7.1 

Follow from step 1 to step 5 : [https://www.mageplaza.com/devdocs/how-install-magento-2-ubuntu.html](https://www.mageplaza.com/devdocs/how-install-magento-2-ubuntu.html) 

Step 6 : git clone 

    cd /var/www/html
    git clone https://github.com/huancnttuet/magento2.git

```bash
sudo chown -R www-data:www-data /var/www/html/magento2/
sudo chmod -R 777 /var/www/html/magento2/
```

Step 7 : Install Magento2

 .....
 
 
-------------------------------------------------------------------------------------------------------------------------

Download magento2 with composer :

    cd /var/www/html
    
    composer create-project --repository=https://repo.magento.com/ magento/project-community-edition magento2

    username : 978037938a10c9d8856cf7e47cbf9497
    
    password : 97955d6e74e0a09e2ee789fd5f7e0c0f 

admin 

    username : admin
    password : 1qaz2wsx3edc

-----------

### Create a UI Component

    php bin/magento module:enable <ComponentName>
    php bin/magento setup:upgrade
    
    php bin/magento setup:di:compile

#### Error

500 in /var/log/apache2
   
   [link](https://magento.stackexchange.com/questions/104380/500-after-install-class-magento-framework-app-resourceconnection-proxy-does-no)

find exception in folder /log 
    
    /log/exception.log


clean cache
    
    rm -rf pub/static/*
    php bin/magento cache:clean
