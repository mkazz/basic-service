from phusion/baseimage

RUN apt-get update -y
RUN apt-get install php5-cli -y
RUN apt-get install mysql-client -y
RUN apt-get install php5-mysql -y

RUN echo "display_errors=On" >> /etc/php5/cli/php.ini
RUN echo "log_errors=Off" >> /etc/php5/cli/php.ini
RUN extension=mysqli.so
RUN extension=mysql.so

EXPOSE 80

ADD . /app

WORKDIR /app
CMD /app/run

