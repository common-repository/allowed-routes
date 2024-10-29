<?php
/**
 * Routing Class 
 */
class AlwdRts_Router extends AlwdRts_AbstractFunctions
{
    protected $status = false;
    protected $message = 'no matching route';
    protected $usedRoute = false;
  
    public function __construct()
    {
        // check prerequisites
        // if there are errors, cancel routing
        if(!empty($this->getEnvErrors))
            return;
        
        // now run this thing
        return $this->run();
    }
    
    /**
     * Getter method for current used route
     * @return string
     */
    public function getUsedRoute()
    {
        return $this->usedRoute;
    }

    /**
     * Getter method for current status
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Getter method for current message
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * Getter method for current status
     * @return void
     */
    protected function run()
    {
        // === VALIDATION #01 - ADMIN LINKS ===
        
        // get admin url data
        $adminUrl = get_admin_url();
        $adminUrl = parse_url($adminUrl);
        $adminUrl = $this->removeSlashes($adminUrl['path']);
        $adminUrlArray = explode('/', $adminUrl);
        
        // get request url data
        $request = parse_url($this->getRequestURI());
        $request = $this->removeSlashes($request['path']);
        $requestArray = explode('/', $request);

        // validate request url with admin url data
        $isOk = true;
        foreach($adminUrlArray as $pos => $adminUrlPart) {
            if(empty($requestArray[$pos]) || $requestArray[$pos] != $adminUrlPart) {
                $isOk = false;
            }
        }
        
        // this is an admin route; validated.
        if(true === $isOk) {
            $this->status = true;
            $this->message = 'admin route (v1)';
            return;
        }
        
        $requestURIWithoutGETParamsNormalizedPieces = $requestArray;
        
        // === VALIDATION #02 - IS IT A LOCAL FILE? ===
        
        // identify if its a local file; this is always allowed.
        if(count($requestURIWithoutGETParamsNormalizedPieces) > 0) {
            $relPath = implode(DIRECTORY_SEPARATOR, $requestURIWithoutGETParamsNormalizedPieces);
            $localPath = untrailingslashit($_SERVER['DOCUMENT_ROOT']).DIRECTORY_SEPARATOR.$relPath;
            
            // allow this route when it's a local file
            if(is_file($localPath)) {
                $this->status = true;
                $this->message = 'local file';
                return;
            }
        }
        
        // === VALIDATION #03 - GET PREFIX / POSTFIX OF WORDPRESS INSTALLATION ===
        
        // get Uriprefix (http://www.example.com/myblog ==> array('myblog')) and unset them from $requestURIWithoutGETParamsNormalizedPieces for
        // proper route comparison
        $URIPrefix = $this->getURIPrefix();
        foreach($URIPrefix as $prefixPostion => $prefix) {
            if($requestURIWithoutGETParamsNormalizedPieces[$prefixPostion] == $prefix) {
                unset($requestURIWithoutGETParamsNormalizedPieces[$prefixPostion]);
            }
            else {
                break;
            }
        }
        $requestURIWithoutGETParamsNormalizedPieces = array_values($requestURIWithoutGETParamsNormalizedPieces);
        
        // === VALIDATION #04 - GET CUSTOM ROUTES AND OBLIGATE ROUTES AND VALIDATE  ===
        // (For security reasons validate admin-routes again (different approach))

        // merge custom and obligate routes
        $allowedRoutesArray = array_merge($this->getAllowedRoutesObligate(), $this->getAllowedRoutesCustom());
        
        $a = $this->getAllowedRoutesCustom();
        $b = $requestURIWithoutGETParamsNormalizedPieces;
        
        // check for allowed index site (e.g. just http://example.com)
        if(count(array_filter($requestURIWithoutGETParamsNormalizedPieces)) == 0 && in_array('/', $allowedRoutesArray)) {
            // allowed, go on
            $this->status = true;
            $this->message = 'index page';
            $this->usedRoute = '/';
            return;
        }
        
        // '*' route is not allowed (syntax rules; must be '/*')
        if (false !== $key = array_search('*', $allowedRoutesArray)) {
            unset($allowedRoutesArray[$key]);
        }
        
        // allowed routes must be entered
        if(empty($allowedRoutesArray)) {
            $isOk = false;
        }
        else  {
            // '/*' is allowed but must be computed as '*'
            if (false !== $key = array_search('/*', $allowedRoutesArray)) {
                $allowedRoutesArray[$key] = '*';
            }
            
            // '/**' is allowed but must be computed as '**'
            if (false !== $key = array_search('/**', $allowedRoutesArray)) {
                $allowedRoutesArray[$key] = '**';
            }
            
            foreach($allowedRoutesArray as $allowedRoute) {
                $allowedRoutePieces = explode('/', 	$allowedRoute);
                $isOk = true; // default
                $multiWildcard = false; //default
                $this->usedRoute = $allowedRoute; // set last used route
        
                foreach($allowedRoutePieces as $pos => $allowedReqPiece)	{
                    // wildcard *
                    // wildcard must be 1char minimum
                    if($allowedReqPiece == '*' && isset($requestURIWithoutGETParamsNormalizedPieces[$pos]) && strlen($requestURIWithoutGETParamsNormalizedPieces[$pos]) > 0) {
                        if(count($allowedRoutePieces) != count($requestURIWithoutGETParamsNormalizedPieces)) {
                            $isOk = false;
                            break;
                        }
                        continue;
                    }
        
                    // wildcard **
                    // everything beyond is ok for us
                    // wildcard must be 1char minimum
                    if($allowedReqPiece == '**' && isset($requestURIWithoutGETParamsNormalizedPieces[$pos]) && strlen($requestURIWithoutGETParamsNormalizedPieces[$pos]) > 0) {
                        $multiWildcard = true;
                        $this->message = 'wildcard ** route';
                        break 2;
                    }
                    
                    if(	!isset($requestURIWithoutGETParamsNormalizedPieces[$pos]) ||
                        $allowedReqPiece != $requestURIWithoutGETParamsNormalizedPieces[$pos]) {
                        $isOk = false;
                        break;
                    }
                }
                if($isOk === true) {
                    $this->message = 'valid route';
                    break;
                }
            }
            if(isset($isOk) && $isOk === true && $multiWildcard === false && count($requestURIWithoutGETParamsNormalizedPieces) != count($allowedRoutePieces)) {
                $this->message = 'no multiwildcard, term count do not match';
                $isOk = false;
            }
        }
        
        if(isset($isOk) && $isOk === false) {
            $this->status = false;
            $this->usedRoute = false; // no used route
            return;
        }
        else {
            // ok, go on
            $this->status = true;
            return;
        }
    }
    
    /**
     * Get request uri for validation
     * Use separat function to give the possibility to overload request uri in an extended class for unittests & co.
     * @return string
     */
    protected function getRequestURI()
    {   // use separat function give the possibility to overload request uri in an extended class
        // always urldecode request uri
        return urldecode($_SERVER['REQUEST_URI']);
    }
    
    /**
     * Get the prefix (or postfix) for wordpress installations for proper validation
     * http://localhost/def/wp-admin/ ==> array('def')
     * http://localhost/def/myblog/wp-admin/ ==> array('def', 'myblog')
     * @return array
     */
    protected function getURIPrefix()
    {
        $siteUrl = site_url();
        $parsedArray = parse_url($siteUrl);
        
        // no prefix
        if(!isset($parsedArray['path'])) {
            return array();
        }
        
        // get path and remove slashes
        $prefix = $this->removeSlashes($parsedArray['path']);
        
        // either no prefix
        if(empty($prefix)) {
            return array();
        }
        
        // get prefix as array and return
        $prefixPieces = explode('/', $prefix);
        return $prefixPieces;
    }
}