GraphQL PHP Scalars
===================

## Installation

`composer require devcake/graphql-php-scalars`

## Usage

You can use the provided Scalars just like any other type in your schema definition. Don't forget to add them in your `schema.graphql`file. Like below

`scalar Price @scalar(class: "DEVcake\GraphQLScalars")`

## Simple scalars

### Price Scalar
A price string, formatted like 13.37


