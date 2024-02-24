# PHPの公式イメージをベースにする
FROM php:8.3.3-apache

# pdo_mysql拡張をインストール
RUN docker-php-ext-install pdo_mysql

RUN ln -s /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/rewrite.load