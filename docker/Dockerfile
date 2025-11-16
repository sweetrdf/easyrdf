FROM php:8.4-cli

RUN apt-get update && \
    apt-get install -y git graphviz libicu-dev libzip-dev make nano netcat-openbsd net-tools raptor2-utils unzip wget zip zlib1g-dev

RUN apt-get install -y openjdk-17-jre

RUN docker-php-ext-install intl zip && docker-php-ext-enable intl zip

# install composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# add custom PHP.ini settings
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY ./custom.ini /usr/local/etc/php/conf.d/custom.ini

RUN rm -rf /var/www/html/*
WORKDIR /var/www/html/

# adds user "easyrdf", adds him to group "www-data" and sets his home folder
# for more background information see:
# https://medium.com/@mccode/understanding-how-uid-and-gid-work-in-docker-containers-c37a01d01cf
RUN useradd -r --home /home/easyrdf -u 1000 easyrdf
RUN usermod -a -G www-data easyrdf
RUN mkdir /home/easyrdf
RUN chown easyrdf:www-data /home/easyrdf

# Download and set up Apache Jena Fuseki (triple store) inside home folder
ARG FUSEKI_VER="5.6.0"
RUN cd /home/easyrdf && \
    wget https://dlcdn.apache.org/jena/binaries/apache-jena-fuseki-${FUSEKI_VER}.zip && \
    unzip apache-jena-fuseki-${FUSEKI_VER}.zip && \
    rm apache-jena-fuseki-${FUSEKI_VER}.zip && \
    mkdir fuseki && \
    mv apache-jena-fuseki-${FUSEKI_VER}/* fuseki && \
    rm -rf apache-jena-fuseki-${FUSEKI_VER}
RUN mkdir -p /home/easyrdf/fuseki/databases/DB
# Change ownership to easyrdf user instead of root
RUN chown -R easyrdf:www-data /home/easyrdf

COPY run.sh /run.sh

RUN chmod +x /run.sh

CMD ["/run.sh"]
