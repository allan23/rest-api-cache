WP REST API Cache
=============
This plugin simply utilizes the 'json_pre_dispatch' hook to retrieve or generate an object cache of endpoint results (GET only).

To rebuild the cache, send this query string to the URL: 'cache_bust=1'.