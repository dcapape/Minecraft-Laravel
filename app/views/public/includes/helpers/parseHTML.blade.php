<?php $bbCode = new \Genert\BBCode\BBCode();
$bbCode->addParser(
     'custom-listitem',
     '/\[\*\](.*?)\[\/\*\]/s',
     '<li>$1</li>',
     '$1'
);
$bbCode->addParser(
    'custom-quote',
    '/\[quote\=(.*?)\](.*?)\[\/quote\]/s',
    '<blockquote>$1:<br>$2</blockquote>',
    '$2'
);
$bbCode->addParser(
    'custom-mc',
    '/\[mc\](.*?)\[\/mc\]/s',
    '<span class="item item_$1"></span>',
    '$1'
);
$bbCode->addParser(
    'custom-color',
    '/\[color\=(.*?)\](.*?)\[\/color\]/s',
    '<span style="color:$1">$2</span>',
    '$2'
);
$bbCode->addParser(
     'custom-color',
     '/\[color\=(.*?)\](.*?)\[\/color\]/s',
     '<span style="color:$1">$2</span>',
     '$2'
);
$bbCode->addParser(
     'custom-size',
     '/\[size\=(.*?)\](.*?)\[\/size\]/s',
     '<font size="$1">$2</font>',
     '$2'
);
$bbCode->addParser(
     'custom-video',
     '/\[video\](.*?)\[\/video\]/s',
     '<iframe src="https://www.youtube.com/embed/$1" width="480" height="320" frameborder="0"></iframe>',
     '$2'
);

//<iframe src="https://www.youtube.com/embed/{SRC}" width="480" height="320" frameborder="0"></iframe>
 ?>
 {{$bbCode->convertToHtml(Convert::parseMCtoHTML($content))}}
