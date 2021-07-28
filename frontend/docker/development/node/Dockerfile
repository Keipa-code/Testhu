FROM node:16-alpine

RUN apk add --no-cache curl

RUN yarn global add @vue/cli -g

WORKDIR /var/www

COPY --chown=node:node . .