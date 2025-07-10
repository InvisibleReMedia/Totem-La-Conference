<div
	class="question<?php if ($selected) echo " selected"?>"
	name="aQuestion"
	data-evqust="<?php echo $id?>"
	onclick="selectQuestion(<?php echo $id?>)"
	><span
		name="questionContent"
		data-evqust="<?php echo $id?>"
		><?php echo htmlspecialchars($content)?></span
	><img class="heart" tabindex="-1" draggable="false" data-evqust="<?php echo $id?>" src="images/heart.png" alt="Likez" onclick="addLikes(<?php echo $id?>);event.stopPropagation();"/><span
		name="likes"
		class="counter"
		data-evqust="<?php echo $id?>"
		><?php echo $likes?></span
	><br/><img class="trash" tabIndex="-1" draggable="false" data-evqust="<?php echo $id?>" src="images/trash.png" alt="Supprimer" onclick="removeQuestion(<?php echo $id?>)"/></div
>