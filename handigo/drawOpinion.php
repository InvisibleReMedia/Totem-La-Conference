<div
	class="<?php if ($questionSource > 0) echo "question1"; else echo "question"; ?><?php if ($selected) echo " selected"?>"
	name="aQuestion"
	data-evqust="<?php echo $id?>"
	onclick="addLikes(<?php echo $id?>)"
	><span
		name="questionContent"
		data-evqust="<?php echo $id?>"
		><?php echo htmlspecialchars($content) ?></span
	><img class="goodbad" tabindex="-1" draggable="false" data-evqust="<?php echo $id?>" src="../images/good.png" alt="Good" onclick="addLikes(<?php echo $id?>);event.stopPropagation();"/><img class="goodbad" tabindex="-1" draggable="false" data-evqust="<?php echo $id?>" src="../images/bad.png" alt="Bad" onclick="addDisLikes(<?php echo $id?>);event.stopPropagation();"/></div
>
