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

## Product XPaths's

Currently, there are two places to store XPath information in the database. A field in a Supplier record and a field in
a Product record. The standard way is to put the XPath in a supplier record. Most websites have the price in the same
location in the DOM but on rare occasions this is not the case. If it's different (like Favskinshouse for example) we
store the XPath information in a product record. The ProductScraper service will first use the supplier xpath. If that
field is NULL than the service will use the XPath used in a product record.

Current list of sites that have or can have different XPath's:

* favskinshouse.com (id number)
* unitedpharmacies.md (depends on which table. Modify last number in xpath to the correct table. Default is 1)
* unitedpharmacies-uk.md (depends on which table. Modify last number in xpath to the correct table. Default is 1)
* SELECT * FROM `product` WHERE supplier_id IN (3, 4, 12);

## Product units

price/mg or bulk/mg is calculated and therefore not stored in the database. The way the back-end calculates this is by
dividing the price by the amount of "untis". The units is usually just the amount of milligrams but sometimes it can be
a different number depending on how the wiki displays or calculates price/unit. Is this stupid? Yes. Does it work? Also
yes.

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

| Option         | Description                                    |
|----------------|------------------------------------------------|
| -s, --supplier | Scrapes the front page for suppliers. Optional |
| url            | Url to product type page thing?                |

**Scrape product pages**

```
// dev environment
lando c scrape:products [options, comma seperated]
// production environment
php bin/console scrape:products [options, comma seperated]
```

| Option    | Description             |
|-----------|-------------------------|
| [options] | Option of product types |