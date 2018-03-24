# LambdaPHP v0.01

Host your website on Aws Lambda with full PHP 7 support (i.e. pay by requests instead of paying a fixed monthly hosting fee). 

Now it's possible for you to host dynamic PHP files, static HTML files, css files on AWS Lambda (serverless) just like an Apache server running mod_php. Any files you put inside the `public` directory will be accessible as if they were hosted on an Apache server with 
`mod_php`. There are no handlers to write or config files to maintain.

For example, put two files, *index.php* and *deep/other.php* inside your `public` folder. The type `lambdaphp deploy`. Once deployed you should be able to access them online at https://yourdomain.com/index.php, https://yourdomain.com/deep/other.php, etc (details below). 

## But why should I care?

**The Difference is you don't have to pay any monthly hosting fees** because they're running on AWS Lambda which 
means you are billed only by the number of requests. This includes 1 million free requests per month and 400,000 GB-seconds 
of compute time per month ([details here](https://aws.amazon.com/lambda/pricing/)). 


## Installation

Installation is simple. All you need is PHP 7+ with [`composer`](https://getcomposer.org)

To install, just type this on your command line (terminal)

    composer create-project lambdaphp/lambdaphp <project-name>
    
This should create a *project-name* directory inside which there is a `public` directory. Any files,
including any PHP files you put in the `public` directory can be accessed directly from your web browser.

Once you're done putting files in the `public` folder, just type this on your command line to 
deploy your site on AWS Lambda:

    lambdaphp deploy -v

*You may need to enter your AWS credentials as [described here](http://docs.aws.amazon.com/cli/latest/userguide/cli-chap-getting-started.html) (same as aws-cli)*. 

If everything goes as expected, you should see this message:
            
    Website deployed! 
    To access your site visit:
    https://XXXX.execute-api.us-east-1.amazonaws.com/web/<any-file-in-public-folder><.php|css|js|etc>
    
*(If you get a "command not found" error, make sure you have `./vendor/bin` in your PATH)*

That's it! LambdaPHP will give you the URL using which you can access your site just like any other
site hosted on Apache. It is possible to use your **own custom domains** with https too \([details here](http://docs.aws.amazon.com/apigateway/latest/developerguide/how-to-custom-domains.html)\).

## Features

Using LambdaPHP you can now use AWS Lambda to:

- Instantly host your static (.css, .js, .png, etc) and dynamic files (.php)
- Most PHP functionality, GET, POST, SESSIONS, etc work seamlessly. 
- File operations incl `file_get_contents`, `file_put_contents`, etc works seamlessly with AWS S3 (using S3 stream wrapping) 
- `Sessions` and works right out of the box! (DynamoDB session wrapper under the hood)
- User authentication support using AWS Cognito (demo included)
- Cron support 

## Examples

 - Static site
 
   [Demo](https://www.lambdaphp.host) |  [Source code](https://github.com/san-kumar/lambdaphp/tree/master/public)

 - Handling POST data
 
   [Demo](https://www.lambdaphp.host/examples/post.php) |  [Source code](https://github.com/san-kumar/lambdaphp/blob/master/public/examples/post.php)
 
 - Creating sessions using AWS DynamoDB
 
   [Demo](https://www.lambdaphp.host/examples/session.php) |  [Source code](https://github.com/san-kumar/lambdaphp/blob/master/public/examples/session.php)
 
 - File IO on AWS lambda (using S3 wrapper)
 
   [Demo](https://www.lambdaphp.host/examples/guestbook.php) |  [Source code](https://github.com/san-kumar/lambdaphp/blob/master/public/examples/guestbook.php)
 
 - Simple File uploader to AWS S3
 
   [Demo](https://www.lambdaphp.host/examples/upload.php) |  [Source code](https://github.com/san-kumar/lambdaphp/blob/master/public/examples/upload.php)
 
 - User signup and login using AWS Cognito
 
   [Demo](https://www.lambdaphp.host/examples/auth/) |  [Source code](https://github.com/san-kumar/lambdaphp/tree/master/public/examples/auth)
    
 - Using Custom domains with https support
 
   [Demo](https://www.lambdaphp.host/) |  [How to guide](http://docs.aws.amazon.com/apigateway/latest/developerguide/how-to-custom-domains.html)

## Limitations

- Handling [cold starts](https://www.google.com/search?q=aws+lambda+startup+time)
- Website code size [limit](https://www.google.com/search?q=aws+lambda+code+size)

## FAQ 
 
- Which PHP version will my site be running?
   
   Currently it supports PHP 7.2.
   
- Is it really free to host my site this way?

  Yes, you get 1 million free requests per month and 400,000 GB-seconds of compute time per month.
  Currently lambdaphp runs very fast with 128MB RAM and has an average response time is 50 to 100ms. So using the [pricing calculator here](https://s3.amazonaws.com/lambda-tools/pricing-calculator.html),
  you can process about 1,000,000 (1M) requests per month free of cost. YMMV. Also remember every asset on your page creates a new request, so factor that in and bundle them together using Webpack.
  
- How do I update my site?

  Just run `lambdaphp deploy` again!
  
- My site is loading slowly?

  The average load time is 50ms (via pingdom).
  [Use Webpack](https://www.phase2technology.com/blog/bundle-your-front-end-with-webpack) to reduce the number of requests per page (bundle CSS, JS, Font files, etc into a single js file). This will also make sure to reduce the number of requests per load helping you stretch your 1M requests even more.  
  
- How much traffic can this handle?

  Theoretically speaking AWS Lambda scales very well so it should be able to handle a lot of traffic easily. It performs better with more traffic since that reduces the load time. But for anything above 20M requests / mo, you'll be missing the point of this whole thing because you can get a dedicated instance for $10/mo anyway ;)       

- How do I add `X` PHP extension?

  I've currently compiled php using the most popular PHP extensions. If you need to a custom extension, just using the 
  Dockerfile in Robert's project below. 
  
- What's the point of it anyway?

  It's a quick and dirty way to get simple PHP website online without paying any monthly hosting fees. Also a great resource if you wish to
  learn Amazon's amazing services like API Gateway, AWS Lambda, S3, DynamoDB, Cognito (which work together under the hood of lambdaphp) for your own sites.

- How to add cron jobs?

  Open (or create) file `lambdaphp.ini` and create a section called `crontab`. To add cron jobs just use the following format
  
  ```
  job_name = 'cron(rate_expression)',php_file.php,enabled|disabled
  ```
    
 So let's say you want to ping your site every 5 minutes. Here is how your `lambdaphp.ini` should look
 
 ```
 [crontab]
 ping = 'cron(*/5 * * * *)',ping.php,enabled
 ```

After you run `lambdaphp deploy`, this will create a cron job to run `ping.php` ()inside `public` directory) every 5 minutes!

To remove this cron job, just remove it from your `lambdaphp.ini` and run `lambdaphp deploy` again (alternatively you can mark last column as `disabled` to temporarily suspend a cron job without removing it)

The timing of your cron job is controlled by the [rate expressions](https://docs.aws.amazon.com/lambda/latest/dg/tutorial-scheduled-events-schedule-expressions.html) as described in the link.
  
## Need more features?

This was just a weekend project for my own amusement but I will definitely add more features 

- Please [star this project](https://github.com/san-kumar/lambdaphp) to show your interest. 
- Leave me some feedback on this [Hackernews thread](https://news.ycombinator.com/item?id=16552325) (please use *Issues* for bugs only).
- Contribute and send me PRs (I will add your name to credits/thanks).

  
## Thanks

* [Robert Anderson](https://github.com/ZeroSharp/serverless-php) for the original inspiration.
