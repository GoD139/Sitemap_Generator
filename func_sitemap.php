<?php


function sitemap_generator()
{
	global $db;	
	
	//initiate dom
	$dom = new DOMDocument('1.0', 'utf-8');
	
	
	//create urlset
	$urlset = $dom->createElement('urlset');
	
	//create and set attribute for urlset
	$domAttribute = $dom->createAttribute('xmlns');
	$domAttribute->value = 'http://www.sitemaps.org/schemas/sitemap/0.9';
	$urlset->appendChild($domAttribute);
	$dom->appendChild($urlset);
	
	
		//create index
		$url = $dom->createElement('url');

		$link = 'http://yoursite.com';
		$url->appendChild($dom->createElement('loc', htmlentities($link)));
		$url->appendChild($dom->createElement('changefreq', 'daily'));
		$urlset->appendChild($url);
	
	
		//create contact
		$url = $dom->createElement('url');

		$link = 'http://yoursite.com/contact.php';
		$url->appendChild($dom->createElement('loc', htmlentities($link)));
		$url->appendChild($dom->createElement('changefreq', 'yearly'));
		$urlset->appendChild($url);
	
	
		//post query
		$GetPosts = $db->prepare('SELECT * FROM posts WHERE Post_Pending = 0 AND Post_Privated = 0');
		$GetPosts->execute();
	
		//create all none pending and private posts
		foreach($GetPosts->fetchAll(PDO::FETCH_OBJ) as $Post)
		{
			//create url
			$url = $dom->createElement('url');
			//create loc
			$link = 'http://yoursite.com/post.php?PID=' . $Post->Post_Unique;
			$url->appendChild($dom->createElement('loc', htmlentities($link)));
			//create lastmod
			$url->appendChild($dom->createElement('changefreq', 'monthly'));
			$urlset->appendChild($url);
		}
		
	//create,structure & save xml
	$dom->appendChild($urlset);
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;
	$dom->save($_SERVER['DOCUMENT_ROOT'] .'/sitemap.xml');
}


