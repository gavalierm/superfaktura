<?php


	/**
	 * Libka pre superfa
	 */
	class SuperFa
	{
		private $endpoint = "http://wwwinfo.mfcr.cz/cgi-bin/ares/darv_bas.cgi";

		public function algo( $count = 100, $echo = true, $line_break = "<br>" ){
			$counter = 0;
			while ($counter < $count) {
			   $counter++;
			   if (!($counter % 3 == 0 OR $counter % 5 == 0)) {
			       if( $echo ) echo $counter . $line_break;
			       continue;
			   }
			   if ($counter % 3 == 0) {
			      if( $echo ) echo 'Super';
			   }
			   if ($counter % 5 == 0) {
			      if( $echo ) echo 'Faktura';
			   }
			   if( $echo ) echo $line_break;
			}
		}

		//netusim ci to je efektivne, vela som googlil, SQL nie je moja silna stranka / vacsinou googlim kym nenajdem nieco co sa mi paci zda sa byt efektivne a jednoducho citatelne
		public function getQuery( $echo = true ){
			//
			$query = "SELECT * FROM duplicates WHERE value IN (SELECT value FROM duplicates GROUP BY value HAVING COUNT(id) > 1)";
			if( $echo ) {
				echo $query; return;
			}

			return $query;

		}


		public getCompanyByICO($ico){
			//validacia ?
			$url = $this->endpoint . "?ico=" . $ico;
			//
		}
		private getUrl($url){
			//http://wwwinfo.mfcr.cz/ares/ares.html.cz
			// create a new cURL resource
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    		curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
			curl_setopt( $ch, CURLOPT_MAXREDIRS, 3 );

			// grab URL and pass it to the browser
			$content = curl_exec( $ch );
    		$response = curl_getinfo( $ch );

			// close cURL resource, and free up system resources
			curl_close($ch);

			if ($response['http_code'] == 301 || $response['http_code'] == 302){
		        ini_set("user_agent", "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");

		        if ( $headers = get_headers($response['url']) ){
		            foreach( $headers as $value ){
		                if ( substr( strtolower($value), 0, 9 ) == "location:" )
		                    return getUrl( trim( substr( $value, 9, strlen($value) ) ) );
		            }
		        }
		    }

			return array( $content, $response );
		}



	}