from ubuntu:18.04

RUN apt-get update -y
RUN apt-get install tzdata
RUN echo 'America/New_York' > /etc/timezone; \
    dpkg-reconfigure -f noninteractive tzdata
RUN apt-get install php php-dom \
    php-mbstring \
    php-zip \
    php-mysql \
    git-core \
    mysql-client -y

RUN echo "display_errors=On" >> /etc/php/7.2/cli/php.ini
RUN echo "log_errors=Off" >> /etc/php/7.2/cli/php.ini
RUN extension=mysqli.so
RUN extension=mysql.so

EXPOSE 80

ADD . /app

WORKDIR /app
CMD /app/run
