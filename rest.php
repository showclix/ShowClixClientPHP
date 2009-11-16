<?php
    class Server {
        
        public $protocol = "http";
        public $base_url = "www.showclix.com/rest.api";
        public $clientcert = '';
        public $clientkey = '';
        public $verifypeer = TRUE;
        
        public function Server($args){
            if(isset($args['protocol'])){
                $this->protocol = $args['protocol'];
            }
            if(isset($args['base_url'])){
                $this->base_url = $args['base_url'];
            }
            if(isset($args['clientcert'])){
                $this->clientcert = $args['clientcert'];
            }
            if(isset($args['clientkey'])){
                $this->clientkey = $args['clientkey'];
            }
            if(isset($args['verifypeer'])){
                $this->verifypeer = $args['verifypeer'];
            }
        }
        
        private function curl_defaults($uri, $data=false){
            //Create a curl resource, passing in the url
            $ch = curl_init($uri);
            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            
            //Folow Redirects            
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            
            //Unless we're infinitely looping
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            if($data){
                //We've got data to send, so add it to curl
                $data = json_encode($data);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
            if ($this->protocol == "https"){
                //Set up client authentication through ssl
                if(!$this->verifypeer){
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                }
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
                curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
                curl_setopt($ch, CURLOPT_SSLCERT, $this->clientcert);
                curl_setopt($ch, CURLOPT_SSLKEY, $this->clientkey);
            }
            
            return $ch;
        }
        
        public function build_url($rest){
            return $this->protocol . "://" . $this->base_url . $rest;
        }
        
        public function get_resource($entityid_or_uri, $verbose=false){
            $uri = $this->build_uri($entityid_or_uri);
            $ch = $this->curl_defaults($uri);
            //$output holds the the JSON result
            $output = curl_exec($ch);
            if ($verbose){
                var_dump(curl_error($ch));
                var_dump(curl_getinfo($ch));
            }
            curl_close($ch);
            return json_decode($output);
        }
        
        public function delete_resource($entityid_or_uri, $verbose=false){
            $uri = $this->build_uri($entityid_or_uri);
            $ch = $this->curl_defaults($uri);
            //Use the 'DELETE' HTTP Request Method
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            //$output holds the the JSON result
            $output = curl_exec($ch);
            if ($verbose){
                var_dump(curl_error($ch));
                var_dump(curl_getinfo($ch));
            }
            curl_close($ch);
            return json_decode($output);
        }
        
        public function modify_resource($entityid_or_uri, $modifications, $verbose=false){
            $uri = $this->build_uri($entityid_or_uri);
            $ch = $this->curl_defaults($uri, $modifications);
            //Use the 'PUT' HTTP Request Method
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            //$output holds the the JSON result
            $output = curl_exec($ch);
            if ($verbose){
                var_dump(curl_error($ch));
                var_dump(curl_getinfo($ch));
            }
            curl_close($ch);
            return json_decode($output);
        }
        
        public function create_resource($entity, $initial, $verbose=false){
            $ch = $this->curl_defaults($this->build_url("/$entity"), $initial);
            //Use the 'POST' HTTP Request method
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            //Set the Expect header so that post data is sent normally
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
            //We're sending JSON -- set the content-type appropriately
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/javascript"));
            //Include Headers in our output.
            curl_setopt($ch, CURLOPT_HEADER, 1);
            //$output holds the the JSON result
            $output = curl_exec($ch);
            if ($verbose){
                var_dump(curl_error($ch));
                var_dump(curl_getinfo($ch));
            }
            curl_close($ch);
            preg_match('/Location:\s*?([^\s]+)/',$output,$matches);
            return $matches[1];
        }
        public function build_uri($info){
            if(!is_array($info)){
                return $info;
            }
            return $this->build_url("/".$info['entity']."/".$entity['id']);
        }
        public function extract_from_uri($info){
            $myinfo = array_filter(explode("/", $info));
            $ret = array_reverse(array(array_pop($myinfo), array_pop($myinfo)));
            return $ret;
        }
    }
?>