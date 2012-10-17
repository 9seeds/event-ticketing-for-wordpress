<?php
function nvp($nvp)
{
	$nvpStr = '';
	foreach($nvp as $k=>$v)
	{
		$nvpStr .= '&'.$k.'='.urlencode($v);
	}

	return substr($nvpStr, 1);
}
?>
