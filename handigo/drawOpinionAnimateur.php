<div
	class="<?php if ($questionSource > 0) echo "question1"; else echo "question"; ?><?php if ($selected) echo " selected"?>"
	name="aQuestion"
	data-evqust="<?php echo $id?>"
	onclick="selectQuestion(<?php echo $id?>)"
	><span
		name="questionContent"
		data-evqust="<?php echo $id?>"
		><?php echo htmlspecialchars($content) ?></span
	><img class="goodbad" tabindex="-1" draggable="false" data-evqust="<?php echo $id?>" src="../images/good.png" alt="Good" onclick="addLikes(<?php echo $id?>);event.stopPropagation();"/><span
		name="likes"
		class="counter"
		data-evqust="<?php echo $id?>"
		data-source="<?php echo $questionSource ?>"
		><?php echo $likes ?></span
	><img class="goodbad" tabindex="-1" draggable="false" data-evqust="<?php echo $id?>" src="../images/bad.png" alt="Bad" onclick="addDisLikes(<?php echo $id?>);event.stopPropagation();"/><span
		name="dislikes"
		class="counter"
		data-evqust="<?php echo $id?>"
		data-source="<?php echo $questionSource ?>"
		><?php echo $dislikes?></span
	><br/><img class="trash" tabIndex="-1" draggable="false" data-evqust="<?php echo $id?>" src="../images/trash.png" alt="Supprimer" onclick="removeQuestion(<?php echo $id?>)"/></div
>
