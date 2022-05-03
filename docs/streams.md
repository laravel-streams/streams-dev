---
title: Streams
category: reference
sort: 0
enabled: true
---

## Introduction

Streams represent the data structures within your application. Use the Streams API to manage data structures.

If you want to access the data within a configured stream, use the [Entries API](entries).

## List Streams

Display a paginated list of configured streams:

```bash
streams:list
    {--query= : Query constraints.}
    {--show= : Comma-seperated fields to display.}
    {--per-page=15 : Entries per page.}
    {--page= : Page to list.}
```

#### Response

```bash
php artisan streams:list --show=id,name --query=where:id,like,%docs%

Total: 5
Per Page: 15
Last Page: 1
Current Page: 1
+-----------+--------------------+
| id        | name               |
+-----------+--------------------+
| docs      | Laravel Streams    |
| docs_api  | Streams API        |
| docs_cli  | CLI Documentation  |
| docs_core | Core Documentation |
| docs_ui   | Streams UI         |
+-----------+--------------------+
```

## Create Stream

Create a new strean:

```bash
streams:create
    {input? : Query string formatted attributes.}
    {--update : Update if exists.}
```

#### Response

```bash
JSON OUTPUT
```

## Show Stream

Display a single entry for a configured stream:

```bash
entries:show
    {stream : The entry stream.}
    {entry : The entry identifier.}
    {--show= : Comma-seperated fields to show.}c
```


#### Response

```bash
php artisan entries:show docs api

+------------+---------------+
| Field      | Value         |
+------------+---------------+
| id         | api           |
| sort       | 20            |
| stage      | review        |
| title      | API Readiness |
| category   | core_concepts |
| enabled    | 1             |
| publish_at |               |
| body       |               |
+------------+---------------+
```
