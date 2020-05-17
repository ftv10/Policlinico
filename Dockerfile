FROM php:7.0-apache

#WORKDIR /app

#COPY package.json ./

#RUN npm install

COPY . .

#CMD ["node", "index.js"]
EXPOSE 80