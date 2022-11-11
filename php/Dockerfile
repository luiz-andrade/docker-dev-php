FROM php:7.4-fpm
RUN docker-php-ext-install mysqli pdo_mysql

# Install packages
RUN apt-get update
RUN apt-get install -y unzip
RUN apt-get install -y libaio1

# Oracle instantclient
ADD instantclient-basic-linux.x64-11.2.0.4.0.zip /tmp/
ADD instantclient-sdk-linux.x64-11.2.0.4.0.zip /tmp/
RUN unzip /tmp/instantclient-basic-linux.x64-11.2.0.4.0.zip -d /usr/local/
RUN unzip /tmp/instantclient-sdk-linux.x64-11.2.0.4.0.zip -d /usr/local/
RUN mv /usr/local/instantclient_11_2 /usr/local/instantclient
RUN ln -s /usr/local/instantclient/libclntsh.so.11.1 /usr/local/instantclient/libclntsh.so
RUN ln -s /usr/local/instantclient/libocci.so.11.1 /usr/local/instantclient/libocci.so

ENV LD_LIBRARY_PATH=/usr/local/instantclient
RUN echo 'instantclient,/usr/local/instantclient' | pecl install oci8-2.2.0

RUN docker-php-ext-configure pdo_oci --with-pdo-oci=instantclient,/usr/local/instantclient
RUN docker-php-ext-install pdo_oci
RUN docker-php-ext-enable oci8

## Composer
# RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
# RUN php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
# RUN php composer-setup.php
# RUN php -r "unlink('composer-setup.php');"
# RUN mv composer.phar /usr/local/bin/composer
# RUN composer -V
# RUN composer install