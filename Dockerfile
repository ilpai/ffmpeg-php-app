FROM jrottenberg/ffmpeg:4.4-ubuntu

WORKDIR /app
RUN apt-get update && apt-get install -y php php-cli curl && apt-get clean
COPY index.php /app/
EXPOSE 80
CMD ["php", "-S", "0.0.0.0:80", "-t", "/app"]
