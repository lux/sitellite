<?php

loader_import ('sitelinks.Item');
loader_import ('sitelinks.Filters');

$parameters['sitesearch'] = @file_exists ('inc/app/sitesearch/data/sitesearch.pid');

if (appconf ('category_screen') == 'default' || ! appconf ('item_pages')) {

	$i = new SiteLinks_Item;

	$parameters['list'] = $i->getByType ($parameters['type']);

	echo template_simple ('type.spt', $parameters);

} else {

	global $cgi;

	if (! isset ($cgi->offset)) {
		$cgi->offset = 0;
	}

	$item = new SiteLinks_Item;

	$list = $item->getByType ($parameters['type'], appconf ('limit'), $cgi->offset);

	loader_import ('saf.GUI.Pager');

	$pg = new Pager ($cgi->offset, appconf ('limit'), $item->total);
	$pg->setUrl ('%s/index/sitelinks-app?type=%s', site_prefix (), $parameters['type']);
	$pg->getInfo ();
	$parameters['pager'] = true;

	template_simple_register ('pager', $pg);
	template_simple_register ('cgi', $cgi);
	echo template_simple ('type_top.spt', $parameters);

	foreach (array_keys ($list) as $k) {
		if (@file_exists ('inc/app/sitelinks/html/summary/' . $list[$k]->ctype . '.spt')) {
			echo template_simple ('summary/' . $list[$k]->ctype . '.spt', $list[$k]);
		} else {
			echo template_simple ('summary/default.spt', $list[$k]);
		}
	}

}

?>