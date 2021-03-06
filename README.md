# Optimization exercise

This is an exercise in optimization. The first commit (`Original`) contains
a ***very*** badly written website, the improvements I make are tested and
documented below.

**All tests are...**

_(...when nothing else is specified...)_

- ...run on an external server.
- ...run on the same computer in the same browser (Chrome)
- ...run at least 3 times.
- ...run on the `mess.php` page, after a successful login.
- ...run with caching disabled in the browser.
- ...run in order, one listed change also includes all the previous.

_Every reported change is marked by a release tag_

There are two users with `username/password`:

- `admin/admin`
- `user/user`

The latest version is running [here](http://dev-php.alcesleo.com/sucky-php/)

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

## Optimization #6: Image

### Theory

The image on the front page is **way** larger than it is displayed as, in the
dev tools network-tab it looks like it takes a huge amount of the total load
time, reducing the filesize could mean a huge performance boost.

### Changes

Resized the image to be just as big as it is displayed.

### Observations

`15 requests  ❘  151 KB transferred  ❘  2.85 s (load: 2.97 s, DOMContentLoaded: 2.96 s)`
`15 requests  ❘  151 KB transferred  ❘  3.09 s (load: 3.21 s, DOMContentLoaded: 3.20 s)`
`15 requests  ❘  151 KB transferred  ❘  3.43 s (load: 3.54 s, DOMContentLoaded: 3.53 s)`

Absolutely huge difference in size, but not much impact on the loading time.

### Reflections

Even though the size was significantly reduced, sadly, the load times are still
very poor.

## Optimization #7: WTF

### Theory

There is a page called `middle.php`, hidden away in the `img/`-folder that
`check.php` redirects to instead of `mess.php`. Why you ask? Just to delay 2
seconds before redirecting to `mess.php`.

This assignment is starting to get to me.

### Changes

Removed the `middle.php` file and redirect directly to `mess.php`

### Observations

Obviously, exactly 2 seconds less delay after logging in.

### Reflections

I'm so sick and tired of this codebase.


## Optimization #8: Fonts

### Theory

The Google Font API is able to download multiple fonts in one request by separating
them with a `|`-sign. Less requests is good.

### Changes

Combined the two Google Font API requests to one.

### Observations

`14 requests  ❘  151 KB transferred  ❘  1.92 s (load: 2.03 s, DOMContentLoaded: 2.01 s)`
`14 requests  ❘  151 KB transferred  ❘  3.18 s (load: 3.31 s, DOMContentLoaded: 3.30 s)`
`14 requests  ❘  151 KB transferred  ❘  2.41 s (load: 2.53 s, DOMContentLoaded: 2.52 s)`

### Reflections

Much, much better results than I expected! Apparently the extra API-request was
more expensive than it seemed.

## Optimization #9: Optimize JS minification

### Theory

I discovered that I accidentally included more JS-libraries than the site is using.

### Changes

Pick out minification-targets manually.

### Observations

`14 requests  ❘  93.9 KB transferred  ❘  3.24 s (load: 3.32 s, DOMContentLoaded: 3.31 s)`
`14 requests  ❘  93.9 KB transferred  ❘  2.85 s (load: 2.88 s, DOMContentLoaded: 2.87 s)`
`14 requests  ❘  93.9 KB transferred  ❘  2.91 s (load: 2.94 s, DOMContentLoaded: 2.93 s)`

### Reflections

Over half of the remaining download size is gone! I should have done this the first
time, but I put it here to keep everything nice and in chronological order.

Filesize really doesn't affect the speed on this blazing network, but I'm still
satisfied.

## More optimizations...

Here are the optimizations that have been made working on later tasks of the assignment,
the performance is not measured for them, but I thought they should at least be mentioned
in this section.

- Working on another functionality I changed the `onclick` attributes to pure JS in commit `73f4264`.
- Getting all messages for a producer in a single request in commit `312dc47`.

## Security #1: Logout

### Security hole

The logout button is implemented with a JavaScript `window.location` (seriously).
You are never actually logged out, just redirected to the login page.

### Possible exploit

After logging out, you can simply enter the URL of the logged in page manually
and you'll still be authorized. This is just a really stupid button that does
something completely different from what it says.

### Harm

Of course you want your users to be able to log out, otherwise anyone can sit
down after the user has "logged out" and post in her name.

### Fix

Removed the JavaScript logout function, since it was stupid. Replaced it with
a slightly less stupid solution - a form that submits to the previously implemented
(but not used) logout function. I also made that logout function functional.

This is an ugly quickfix, but it plugged the security hole.

## Security #2: SQL Injections

### Security hole

**All** places where SQL is used is open to SQL injections.

### Possible exploit

You can use all functions that use the database to do anything with the database.

### Harm

Obviously we don't want anyone with basic knowledge of SQL to have full control
of our database.

### Fix

Parameterized all queries.

**NOTE**: I forgot some, they are fixed in commit: `5ea70df`.

## Security #3: Name

### Security hole

Any user can put in any name when posting.

### Possible exploit

Anyone can post in another users name.

### Harm

This is just a really stupid way of doing things.

### Fix

Quickfix, for now I just put the username in a hidden-field instead. You can
still change it if you know how but at least it is not a bleeping textbox.

## Security #4: XSS

### Security hole

The message box was completely open to insert scripts.

### Possible exploit

Any user could insert code that would be executed on for all other users.

### Harm

A user with malicious intents can do anything that JavaScript can do(which is a lot)
to all other users until the script is removed. Not good.

### Fix

Encoding all input with `htmlspecialchars`.

## Security...

Is still worthless, the passwords are stored in plaintext, you can still post
as any user via dev tools, you can probably do some damage due to that the
SQLite database (and the other PHP-files) are accessable if you know their name
- this should be fixed in `.htaccess`.

To pass the assignment, only 4 security fixes are needed, and frankly I don't
want to spend a second more than I have to working with this rubbish code.

## Improvement #1: Ajax

Messages now appear directly, without a refresh. I implemented it like this:

-   PHP function to get all messages in one request, instead of first making a request
    asking for all the ID:s, then making one request for every single message.
-   Made it available through `functions.php`
-   Adjusted the JS to use this function instead of making lots of requests.
-   Broke out the message getting into its own function.
-   Calling this function after a message is posted.

## Conclusion

There is **a lot** that is still wrong with this application. Amongst many others:

- Passwords are in plaintext.
- Docblocks are missing everywhere.
- Mixed return values are used.
- Standards, oh god the standards.
- Database design, fields are not in a good order etc...
- The "non-page" php-files can be accessed, displaying a blank page.
- Logout should be it's own page instead of having to use a query string...
- Empty messages can be entered.

The application was a steaming bag of dogshit, and now I've stomped out the fire.
I don't ever want to do work like this again.
