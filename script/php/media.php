<?php



header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Content-Type: text/html");

?>
<img src="site/file/pic<?php echo rand(1, 5);             ?>.jpg" onclick="sort(this)" width="100%" height="100%" onmouseover="visible(this,'command')" onmouseout="hide(this,'command')" title="Click To Change Image" />
<div id="command" style="visibility: hidden"  onmouseover="visible(this)" onmouseout="hide(this)">
    <a href="#" id="commandO" style="color: white;text-decoration: none;">Share</a>
</div>