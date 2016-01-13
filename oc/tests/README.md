# Automated Testing for open-eshop

## Instructions

1. Clone open-eshop and common:

    cd /var/www/
    git clone https://github.com/open-classifieds/open-eshop
    cd open-eshop/oc/
    git clone https://github.com/open-classifieds/common

2. Create a database named **openeshop**.

3. Install Open-Eshop:

	  admin email and password:
	  admin@eshop.lo
	  1234

4. Upload all the themes inside themes/ folder.

5. Download codecept.phar

    wget http://codeception.com/codecept.phar

6. Run tests:

    php codecept.phar run acceptance

or 

    php codecept.phar run acceptance --steps
