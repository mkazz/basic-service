from phusion/baseimage

RUN apt-get update -y
RUN apt-get install php5-cli -y

EXPOSE 80

ADD . /app

CMD php -S 0.0.0.0:80 -t /app/src/webroot

