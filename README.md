# diy.hrt webscraper and REST API

[diyhrt.cafe](https://diyhrt.cafe/index.php/Main_Page) is the largest directory of sources for life-saving transfeminine
medication. This wiki was made to help people undergo a medical transition for those who live in countries where it is
not available, have long waiting lists, want medical authonomy or for various other reasons. The wiki lists over 400
different products. Keeping all of these up to date with the latest pricing information for a team of small
administrators is nearly impossible. This is why I set out to create a scraper and API to provide an additional way to
access this data and to automatically keep the data up-to-date. This is written in the Symfony framework because the
wiki uses the MediaWiki webapp, which is also written in PHP.

## Installation

This repo is set up with [Lando](https://lando.dev/). Lando will automatically set up docket containers with the webapp
and the database. To set up this project run the following commands:

```
lando start
lando composer install
lando c doctrine:migrations:migrate
```

From there you can import the sql dump into the database.

If you use windows you can use WSL to set up a linux dev environment.

## API

### Authorization

Some endpoints need authorization with a token to access. Most notably the endpoints to create, update and delete
records. The User endpoint is also protected to keep user data private. API token keys can only be added by an
administrator.

## Commands

There are a few commands available to scrape data from both the wiki and from product pages.

**Scrape from wiki**
```
// dev environment
lando c scrape:wiki "{url}"
// production environment
php bin/console scrape:wiki "{url}"
```


| Option | Description |
| --- | --- |
| -s, --supplier | Scrapes the front page for suppliers. Optional |
| url | Url to product type page thing? |

**Scrape product pages**
```
// dev environment
lando c scrape:products [options, comma seperated]
// production environment
php bin/console scrape:products [options, comma seperated]
```

| Option | Description |
| --- | --- |
| [options] | Option of product types |