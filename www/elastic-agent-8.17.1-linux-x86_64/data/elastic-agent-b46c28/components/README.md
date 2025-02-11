# Welcome to Cloudbeat 8.17.1

Cloudbeat collects cloud compliance data and sends findings to ElasticSearch

## Getting Started

To get started with Cloudbeat, you need to set up Elasticsearch on
your localhost first. After that, start Cloudbeat with:

     ./cloudbeat -c cloudbeat.yml -e

This will start Cloudbeat and send the data to your Elasticsearch
instance. To load the dashboards for Cloudbeat into Kibana, run:

    ./cloudbeat setup -e

For further steps visit the
[Quick start](https://www.elastic.co/guide/en/beats/cloudbeat/master/cloudbeat-installation-configuration.html) guide.

## Documentation

Visit [Elastic.co Docs](https://www.elastic.co/guide/en/beats/cloudbeat/master/index.html)
for the full Cloudbeat documentation.

## Release notes

https://www.elastic.co/guide/en/beats/libbeat/master/release-notes-8.17.1.html
