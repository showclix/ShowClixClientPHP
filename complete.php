<?php
    require_once("rest.php");
    $server = new Server(
                    array(
                        "protocol" => "https",
                        "clientcert" => <path to cert>
                        "clientkey" => <path to key>
                        )
                    );
    #Seller
    
    $seller_uri = $server->create_resource("Seller", array(
            "first_name"    => "Silas",
            "last_name"     => "Snider",
            "phone"         => "15555555555",
            "email"         => "noreply@showclix.com"
        ));
    
    var_dump($server->get_resource($seller_uri));
    
    $server->modify_resource($seller_uri, array(
            "address1"      => "1234 Main St",
            "city"          => "Pittsburgh",
            "state"         => "PA",
            "zip"           => "15201"
        ));
    
    var_dump($server->get_resource($seller_uri));
    $seller_id = $server->extract_from_uri($seller_uri);
    $seller_id = $seller_id[1];
    var_dump($seller_id);
    
    #Venue

    $venue_uri = $server->create_resource("Venue", array(
            "venue_name"    => "Test Venue"
        ));
    var_dump($server->get_resource($venue_uri));
    
    $venue_id = $server->extract_from_uri($venue_uri);
    $venue_id = $venue_id[1];
    
    var_dump($venue_id);
    
    #Event
    
    $event_uri = $server->create_resource('Event', array(
            "sales_open"    => '2010-01-01',
            "event_start"   => '2011-01-01',
            "event_type"    => '3',
            "status"        => '1',
            "price"         => "5",
            "price_label"   => "General Admission",
            "price_limit"   => "500",
            "venue_id"      => $venue_id,
            "seller_id"     => $seller_id
        ));
    
    var_dump($server->get_resource($event_uri));
    
    $event_id = $server->extract_from_uri($event_uri);
    $event_id = $event_id[1];
    
    $price_level_uri = $server->create_resource("Pricelevel", array(
            "event_id"      => $event_id,
            "price"         => "1000",
            "level"         => "Fancy-pants admission",
            "limit"         => "10"
        ));
    
    var_dump($server->get_resource($price_level_uri));
    var_dump($server->get_resource($event_uri . "price_levels"));
    var_dump($server->get_resource($event_uri . "all_levels"));
    
    $server->delete_resource($price_level_uri);
    $server->delete_resource($event_uri);
    $server->delete_resource($venue_uri);
    $server->delete_resource($seller_uri);
    
    var_dump($server->get_resource($event_uri));
    var_dump($server->get_resource($venue_uri));
    var_dump($server->get_resource($seller_uri));
    var_dump($server->get_resource($price_level_uri));
?>