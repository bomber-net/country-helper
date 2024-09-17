<?php

namespace BomberNet\CountryHelper;

use Locale;

class CountryHelper
	{
		public static function byLangCode (?string $langCode):?string
			{
				$langCode=trim ($langCode);
				return $langCode?Locale::getDisplayRegion ($langCode,'en'):null;
			}
		
		public static function byIP (string $ip):?string
			{
				$locale=trim (shell_exec ("geoiplookup $ip | grep -oE ':\s+\S+\,' | grep -oE '[[:alpha:]]+'"));
				return $locale?Locale::getDisplayRegion ($locale.'_'.$locale,'en'):null;
			}
		
		public static function filterName (?string $existsCountry)/*:?string*/
			{
				$countries=require __DIR__.'/countries.php';
				$existsCountry=mb_strtolower ($existsCountry);
				$similars=[];
				foreach ($countries as $name=>$country)
					{
						similar_text ($name,$existsCountry,$similar);
						if ($similar>80) $similars[]=compact ('country','similar');
					}
				usort ($similars,static fn (array $a,array $b)=>$a['similar']<=>$b['similar']);
				return (array_pop ($similars)??[])['country']??null;
			}
	}
