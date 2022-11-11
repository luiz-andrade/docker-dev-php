FROM nginx:1.13

ADD ./nginx/blog etc/nginx/sites-available/blog
ADD ./www/blog /var/www/html
RUN chown -R www-data:www-data /var/www/html/blog/storage
RUN chown -R www-data:www-data /var/www/html/blog/bootstrap/cache

# RUN ln -s /etc/nginx/sites-available/blog /etc/nginx/sites-enabled/
# RUN systemctl reload nginx