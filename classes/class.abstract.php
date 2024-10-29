<?php
/**
 * Abstract class for basic functions in this plugin
 */
abstract class AlwdRts_AbstractFunctions
{
	/**
	 * Remove slashes on beginning and the end of a string
	 * @return string
	 */
    protected function removeSlashes($str)
    {
        // remove first slash
        if(substr($str, 0, 1) == '/') {
            $str = substr($str, 1, (strlen($str)-1));
        }
        
        // remove last slash
        if(substr($str, -1) == '/') {
            $str = substr($str, 0, (strlen($str)-1));
        }
        
        return $str;
    }
    /**
	 * Get allowed routes from wordpress option
	 * @return array
	 */
    protected function getAllowedRoutesCustom()
    {
        $routes = get_option('routes');
        if(!is_array($routes) || empty($routes)) {
            return array();
        }
        else {
            $routes = array_map('sanitize_text_field', $routes);
			$routes = array_map('base64_decode', $routes);
			
            // sanitize routes
            foreach($routes as $index => $route) {
                // remove trailing slashes
                if($route != '/' && $route != '/*' && $route != '/**') {
                    $routes[$index] = trim($route, '/');
                    
                    // remove route when sanitized route is empty
                    if(strlen($routes[$index]) == 0) unset($routes[$index]);
                }
                // change * to /*
                if($route == '*') {
                    $routes[$index] = '/*';
                }
                // change ** to /**
                if($route == '**') {
                    $routes[$index] = '/**';
                }
                // wildcard ** is only allowed at the end of the route
                if(stristr($route, '**') && substr($route, -2) != '**') {
                    unset($routes[$index]);
                }
            }
            return $routes;
        }
    }
    
	/**
	 * Debug variables with this little helper
	 * @return string
	 */
    protected function debug($var)
    {
        $str = '<pre>';
        ob_start();
        var_dump($var);
        $str .= ob_get_clean();			
        $str.= '</pre>';
        return $str;
    }

	/**
	 * Get allowed, obligate routes for backend urls
	 * @return array
	 */
    protected function getAllowedRoutesObligate()
    {
        $allowedRoutesObligate = array();
        $adminUrl = admin_url();
        $homeUrl = home_url();
        $adminRoute = str_replace($homeUrl, '', $adminUrl);
        $adminRoute = $this->removeSlashes($adminRoute);
        $allowedRoutesObligate[] = $adminRoute;
        $allowedRoutesObligate[] = $adminRoute.'/**';
        
        // check if there is a prefix for admin & login route
        $prefixPieces = explode('/', $adminRoute);
        if(count($prefixPieces) > 1) {
            array_pop($prefixPieces);
            $prefixGlued = implode('/',$prefixPieces);
            $prefixGlued = $this->removeSlashes($prefixGlued);
            $allowedRoutesObligate[] = $prefixGlued.'/admin';
            $allowedRoutesObligate[] = $prefixGlued.'/login';
        }
        else {
            $allowedRoutesObligate[] = 'admin';
            $allowedRoutesObligate[] = 'login';
        }
        return $allowedRoutesObligate;
    }

	/**
	 * Check all prerequisites for a working plugin
	 * @return array
	 */
	protected function getEnvErrors() {
		$errorArray = array();
		
		if(empty($_SERVER)) {
			$errorArray[] = '$_SERVER Variable is not present.';
		}
		
		if(empty($_SERVER['DOCUMENT_ROOT'])) {
			$errorArray[] = '$_SERVER DOCUMENT_ROOT Variable is not present.';
		}
		
		if(empty($_SERVER['REQUEST_URI'])) {
			$errorArray[] = '$_SERVER REQUEST_URI Variable is not present.';
		}
		
		return $errorArray;
	}
}        