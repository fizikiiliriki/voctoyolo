<?

// Convert files from VOC to YOLO
// Конвертируем файлы разметки из формата VOC в формат YOLO

define(XML_PATH, 'xml');
define(TXT_PATH, 'txt');

$labels=['dog'=>0, 'person'=>1];

function normalizeFloat ($float) {

	$float=substr($float, 0, 8);

	while (strlen($float)<8) {
		$float=$float.'0';
	}

	return $float;

}

$dh=opendir(XML_PATH);

while($file=readdir($dh)) {
	if ($file<>'.' && $file<>'..') {

		$xml=simplexml_load_file(XML_PATH.'/'.$file);

		$fh=fopen(TXT_PATH.'/'.pathinfo($file, PATHINFO_FILENAME).'.txt', 'w+');

		foreach ($xml->object as $object) {
			$x_center=normalizeFloat(($object->bndbox->xmin+($object->bndbox->xmax-$object->bndbox->xmin)/2)/$xml->size->width);
			$y_center=normalizeFloat(($object->bndbox->ymin+($object->bndbox->ymax-$object->bndbox->ymin)/2)/$xml->size->height);
			$width=normalizeFloat(($object->bndbox->xmax-$object->bndbox->xmin)/$xml->size->width);
			$height=normalizeFloat(($object->bndbox->ymax-$object->bndbox->ymin)/$xml->size->height);
			fwrite($fh, $labels[strval($object->name)].' '.$x_center.' '.$y_center.' '.$width.' '.$height."\n");
		}

		fclose($fh);
	}
}

closedir($dh);