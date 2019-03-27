FROM prashant10/ubuntu:16.04
MAINTAINER Prashant

ENV TZ America/Los_Angeles
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

ENV ENVIRONMENT local
ENV BASE_DIR /opt/pra
WORKDIR $BASE_DIR

# Install packages
ENV DEBIAN_FRONTEND noninteractive
RUN apt-get clean
RUN apt-get update
RUN apt-get -y install supervisor \
                      git \
                      apache2 \
                      curl \
                      php \
                      php-cli \
                      libapache2-mod-php \
                      mysql-server \
                      mysql-client \
                      php-apcu \
                      php-gd \
                      php-json \
                      php-ldap \
                      php-mbstring \
                      php-opcache \
                      php-pgsql \
                      php-sqlite3 \
                      php-xml \
                      php-xsl \
                      php-zip \
                      php-soap \
                      php-xdebug \
                      php-curl \
                      php-mcrypt \
                      php-intl  \
                      php7.0-mysql \
                      vim \
                      cron

# Redirect apache logs to stdout/err for docker
RUN ln -sf /dev/stdout /var/log/apache2/access.log && \
    ln -sf /dev/stderr /var/log/apache2/error.log

# Redirect mysql logs to stdout/err for docker
RUN ln -sf /dev/stdout /var/log/mysql/error.log

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf


COPY database*/* /tmp/

# Add image configuration and scripts
ADD lamp/start-apache2.sh .
ADD lamp/start-mysqld.sh .
ADD lamp/run.sh .
RUN chmod 755 ./*.sh
ADD lamp/my.cnf /etc/mysql/conf.d/my.cnf
ADD lamp/supervisord.conf /etc/supervisor/supervisord.conf
ADD lamp/supervisord-apache2.conf /etc/supervisor/conf.d/supervisord-apache2.conf
# mysql daemon disabled by default, run.sh enables
ADD lamp/supervisord-mysqld.conf /etc/supervisor/conf.d/supervisord-mysqld.conf.disabled

# Add MySQL utils
ADD lamp/local_db_setup.sh .
RUN chmod 755 ./*.sh

# config to enable .htaccess
ADD lamp/apache_default /etc/apache2/sites-available/000-default.confs

# Add volumes for MySQL
VOLUME  ["/etc/mysql", "/var/lib/mysql" ]

COPY ums/ /var/www/html

RUN chown -R www-data /var/www/html/

EXPOSE 80 3306
CMD ["./run.sh"]

#docker build -t pra-demo .
#docker run -t -p 8080:80 pra-demo
