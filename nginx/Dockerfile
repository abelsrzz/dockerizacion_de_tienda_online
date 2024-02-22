FROM nginx:latest
RUN rm -rf /var/www/html
ADD nginx.conf /etc/nginx/nginx.conf
ADD conf.d/default.conf /etc/nginx/conf.d/default.conf
