# magemaclean/core

## How to install & upgrade MageMaclean_Core

### 1. Install via composer (recommend)

We recommend you to install MageMaclean_Core module via composer. It is easy to install, update and maintain.
Run the following commands in your Magento 2 root folder.

#### 1.1 Install

```
composer require magemaclean/m2-core
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```

#### 1.2 Upgrade

```
composer update magemaclean/m2-core
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```

Run compile if your store is in Product mode:

```
php bin/magento setup:di:compile
```

### 2. Copy and paste

If you don't want to install via composer, you can download and install it this way. 

- Download from (https://github.com/magemaclean/m2-core/releases/) 
- Create the directory path `app/code/MageMaclean/Core` if it does not already exist.
- Extract `master.zip` file to `app/code/MageMaclean/Core` ;
- Go to your Magento 2 root folder and run the following commands to install or upgrade `MageMaclean_Core`:

```
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```