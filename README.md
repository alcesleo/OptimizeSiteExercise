# Optimization exercise

This is an exercise in optimization. The first commit (`Original`) contains
a ***very*** badly written website, the improvements I make are tested and
documented below.

**All tests are...**

- ...run on an external server.
- ...run on the same computer in the same browser (Chrome)
- ...run at least 3 times.
- ...run with caching disabled in the browser.
- ...run in order, one listed change also includes all the previous.
- ...marked by a release-tag.

There are two users with `username/password`:

- `admin/admin`
- `user/user`

## Baseline

These are the results with the original codebase, values are from the `Network`-tag
in Chrome dev tools and are taken from three consecutive requests, **after** login
has been completed.

`18 requests  ❘  2.4 MB transferred  ❘  3.49 s (load: 3.60 s, DOMContentLoaded: 3.53 s)`
`18 requests  ❘  2.4 MB transferred  ❘  3.27 s (load: 3.40 s, DOMContentLoaded: 3.32 s)`
`18 requests  ❘  2.4 MB transferred  ❘  2.91 s (load: 3.04 s, DOMContentLoaded: 2.96 s)`

## Optimization #1: CSS

### Theory

According [High Performance Web Sites (Rule 5)][HPWS] styles should ideally be
included with `<link>` tags at the top of the page, for progressive rendering,
but also to keep the PHP-pages cleaner.

### Changes

I've moved all CSS from `style`-tags to `main.css`. Ordered `link` tags to be in
the head, before any JS.

### Observations

`19 requests  ❘  2.4 MB transferred  ❘  2.30 s (load: 2.44 s, DOMContentLoaded: 2.44 s)`
`19 requests  ❘  2.4 MB transferred  ❘  2.59 s (load: 2.74 s, DOMContentLoaded: 2.74 s)`
`19 requests  ❘  2.4 MB transferred  ❘  1.78 s (load: 1.81 s, DOMContentLoaded: 1.55 s)`

Impressively big difference. There is one extra request (the new `main.css` file) but
it still loads significantly faster.

### Reflections

This had a much bigger impact than I had thought. It seems like common sense
to include the stylesheets in link-tags out of purely orginazational purposes,
but I did not think it would have this much impact on performance.

## Optimization #2: JavaScript

### Theory

Contrary to stylesheets that make the page render progressively, scripts block
the page from rendering while they are downloaded. Scripts are ideally placed
at the bottom of the page to allow visible components to be displayed as early
as possible. This should make the page **feel** faster, but I believe the actual
performance will be roughly the same.

### Changes

- Moved all script tags to the bottom
- Moved all JS in script tags to a separate file
- Removed script tags that led to a 404
- (Closed forgotten div-tags, a couple fewer validation errors...)

**No changes has been made to the JS-code. Location, location, location.**

### Observations

`18 requests  ❘  2.4 MB transferred  ❘  2.21 s (load: 2.37 s, DOMContentLoaded: 2.29 s)`
`18 requests  ❘  2.4 MB transferred  ❘  3.43 s (load: 3.55 s, DOMContentLoaded: 3.48 s)`
`18 requests  ❘  2.4 MB transferred  ❘  3.15 s (load: 3.30 s, DOMContentLoaded: 3.22 s)`

Very surprisingly, the performance seems to have decreased a bit. Although the
numbers are not expected, the site still **feels** like it's loading faster.
There is noticably less delay before the site appears fully loaded.

### Reflections

I didn't expect the numbers to change this much, but it could just mean that the
server is under heavier load this time. Even though the numbers went down, the
overall feel of the page has improved, it feels snappier and _appears_ to load
quicker. It also makes the code so much cleaner and nicer to work with.

## Optimization #3: gzip

### Theory

Compressed data means less traffic, this should reduce load times.

### Changes

Enabled gzip for HTML, CSS and JS using the `.htaccess`-file.

### Observations

`18 requests  ❘  2.1 MB transferred  ❘  3.16 s (load: 3.27 s, DOMContentLoaded: 3.20 s)`
`18 requests  ❘  2.1 MB transferred  ❘  4.06 s (load: 4.14 s, DOMContentLoaded: 4.05 s)`
`18 requests  ❘  2.1 MB transferred  ❘  3.47 s (load: 3.58 s, DOMContentLoaded: 3.50 s)`

Less data is transfered, but the load times seem to be even worse.

### Reflections

The amount of data transfered was decreased, as expected, but it did not yield
better response times. If anything, it made it worse. Maybe it is because I'm
on a very good internet connection and the time it takes to zip and unzip the
files is actually more expensive than to send them as is. Hopefully this makes
more difference on a slower machine.

I'll keep this change for now, but I might disable it later.

## Optimization #4: Minify JS

### Theory

The site has almost 20 HTTP-requests as it is, this needs to go **way** down.

### Changes

Using [Grunt](http://gruntjs.com/) to concatenate and minify all JS files into one.

    # install grunt
    npm install

    # minify the js-files
    grunt

### Observations

`15 requests  ❘  2.1 MB transferred  ❘  3.46 s (load: 3.46 s, DOMContentLoaded: 2.90 s)`
`15 requests  ❘  2.1 MB transferred  ❘  3.46 s (load: 3.62 s, DOMContentLoaded: 3.54 s)`
`15 requests  ❘  2.1 MB transferred  ❘  2.55 s (load: 2.55 s, DOMContentLoaded: 2.18 s)`

Less requests, slightly faster loading.

### Reflections

It can be a bit of a hassle to get these build-scripts up and running, but
it makes it possible to keep the JS nice and modular without increasing the
number of requests.

## Optimization #5: CDN

### Theory

CDN:s allow for better caching, and you don't need to hosts the files yourself.

### Changes

Using the minified version of Bootstrap from a CDN.

### Observations

`15 requests  ❘  2.1 MB transferred  ❘  2.78 s (load: 2.97 s, DOMContentLoaded: 2.89 s)`
`15 requests  ❘  2.1 MB transferred  ❘  3.10 s (load: 3.27 s, DOMContentLoaded: 3.19 s)`
`15 requests  ❘  2.1 MB transferred  ❘  3.05 s (load: 3.23 s, DOMContentLoaded: 3.15 s)`

No noticable difference.

### Reflection

Didn't help much, or at all. This might be because I'm serving the page from
Swedish servers to a client in Sweden, the CDN might not be as close.
