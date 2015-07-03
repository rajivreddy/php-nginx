FROM rajivnix/nginx-fpm 

MAINTAINER John Fanjoy <jfanjoy@inetu.net>

ENV DESMAN_CONTAINERIZER docker

RUN apt-get update && \
    apt-get install -qq -y rsync libwww-curl-perl vim mysql-client
ADD . /var/www/repo

RUN /var/www/repo/.desman/deploy && \
    cp /var/www/repo/config/nginx.conf /etc/nginx/conf.d/default.conf && \
    cp /var/www/repo/config/w3tc-nginx.conf /etc/nginx/w3tc
WORKDIR /var/www
#need to add script here for creating database

CMD ["/var/www/repo/.desman/createdb"]
CMD ["/var/www/repo/.desman/start"]
