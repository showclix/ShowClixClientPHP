Examples of RESTful API
=======================

These are examples using the php client library written by ShowClix.  Many
languages include their own API for HTTP (e.g. Java).

Note: currently only support JSON representations and the
Content-Type used is text/javascript rather than application/json
for compatibility reasons
Note: ETag Versioning is supporting for caching and versioning,
however these tags are hashes rather than continuous serial numbers
Note: Hyperlinking is promoted in the GET representations for exposing
relationships between the resources (e.g. the Seller representation below
includes an 'events' attribute that includes a URI to a representation of
that sellers events).

**Note:** all examples below depend on the following code segment:

    <?php
        require_once('rest.php');
        $server = new Server(array("protocol" => "https", "clientcert" => <path to cert>, "clientkey" => <path to key>));
    ?>


Get information for a seller:
-----------------------------

    <?php
        $output = $server->get_resource(array("entity" => "Seller", "id" => "1"));
        var_dump($output);
    ?>



Get the same Seller via an indirect URI (e.g. thru one of their events Event)
-----------------------------------------------------------------------------

    <?php
        //Create a curl resource, passing in the url
        $output = $server->get_resource('https://www.showclix.com/rest.api/Event/4044/seller');
        var_dump($output);
    ?>



Get options for an Event instance
---------------------------------

    <?php
        //Create a curl resource, passing in the url
        $ch = curl_init('https://www.showclix.com/rest.api/Event/4044/');
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //Give us the headers as part of the output
        curl_setopt($ch, CURLOPT_HEADER, 1);
        //Use the 'OPTIONS' HTTP Request Method
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'OPTIONS');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLCERT, $server->clientcert);
        curl_setopt($ch, CURLOPT_SSLKEY, $server->clientkey);
        //$output holds the the JSON result
        $output = curl_exec($ch);
        var_dump($output);
        //Will be similar to:
        /*
        HTTP/1.1 200 OK
        Date: Thu, 24 Sep 2009 15:52:24 GMT
        Server: Apache/2.0.63 (Unix) mod_ssl/2.0.63 OpenSSL/0.9.8e-fips-rhel5 DAV/2 mod_bwlimited/1.4 PHP/5.2.8
        X-Powered-By: PHP/5.2.8
        ETag: dcca48101505dd86b703689a604fe3c4
        Allow: GET,PUT,DELETE
        Content-Length: 0
        Content-Type: text/plain
        */
        curl_close($ch);
    ?>



Add a new seller
----------------

    <?php
        $new_seller = $server->create_resource('Seller', array("first_name" => "Nathan", "last_name" => "Good", "organization" => "ShowClix", "phone" => "5555555555", "email" => "noreply@showclix.com"));
        var_dump($new_seller);
    ?>



Update the seller we just created (note supports partial updates)
-----------------------------------------------------------------

    <?php
        $output = $server->modify_resource($new_seller, array("first_name" => "Nate"));
        var_dump($output);
    ?>


Now Delete that seller
----------------------

    <?php
        $output = $server->delete_resource($new_seller);
        var_dump($output);
    ?>



Verify that this seller is no longer here (404)
-----------------------------------------------

**Note: We don't support 410 Gone for HTTP die hards out there**

    <?php
        //Create a curl resource, passing in the url
        $ch = curl_init($new_seller);
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //Give us the headers as part of the output
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLCERT, $server->clientcert);
        curl_setopt($ch, CURLOPT_SSLKEY, $server->clientkey);
        //$output holds the the JSON result
        $output = curl_exec($ch);
        var_dump($output);
        curl_close($ch);
    ?>