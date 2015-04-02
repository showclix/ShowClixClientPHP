# This process of authenticating is now deprecated. Please refer to the token authentication strategy discussed in [this documentation](https://github.com/ShowClix/ShowClixClientPHP/blob/master/README.markdown#authentication).

Generating Certificates for use with the ShowClix API
=====================================================

Step 1: Generate a private key
------------------------------

Open a terminal window and type the following

    openssl genrsa -out showclix_api.key

Step 2: Generate a signing request
----------------------------------

In the same terminal as before, type:

    openssl req -new -key showclix_api.key -out showclix_api.csr

Answer the questions as prompted. The only one that matters is the Common Name (CN),
which must be something that would identify your organization.

Step 3: Getting the key signed
------------------------------

Send your showclix\_api.csr file to it@showclix.com. Assuming that everything is correct,
we'll sign your csr and send you back a .crt file that contains your new client certificate
in PEM format.

Step 4: Use the API
-------------------

You now have everything you need in order to authenticate to the API. Use the key and crt file
with the api client library, curl, or whatever you need. While ShowClix can send you the signed
cert again if you lose it, we won't be able to send you your .key file since we never had a copy
of it.
