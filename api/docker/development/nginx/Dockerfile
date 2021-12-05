FROM nginx:1.19-alpine

RUN apk add --no-cache curl

RUN apk add --no-cache tzdata
ENV TZ Asia/Oral
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

COPY ./common/nginx/conf.d /etc/nginx/conf.d

WORKDIR /var/www