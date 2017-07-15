for((i=6 ; i<=83 ; ++i))
do
	echo "<div class=\"content-container type-1\">
    <div class=\"title\"></div>
    <div class=\"subtitle\"></div>
    <div class=\"hint-title\">English Meaning:</div>
    <div class=\"hint-content\"></div>
    <div class=\"audio\">
        <video width=\"100%\" height=\"50\" autoplay controls controlsList=\"nodownload\">
            <source src=\"../res/1. Vocabulary Learning/Vocabulary Learning [兼容模$((i+256)).wav\" type=\"video/ogg\">
        </video>
    </div>
</div>" > 1-1-$i.html
done