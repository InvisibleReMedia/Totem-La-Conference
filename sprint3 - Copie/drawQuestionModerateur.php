<div
	class="question<?php if ($selected) echo " selected"?>"
	name="aQuestion"
	data-evqust="<?php echo $id?>"
	><input type="checkbox" name="moderated" data-evqust="<?php echo $id?>" checked="false"/><em
		style="color:red"
		><?php if ($moderated) echo "VISIBLE"; else echo "HIDDEN";?></em
	><br/><span
		name="questionContent"
		data-evqust="<?php echo $id?>"
		contenteditable="true"
		><?php echo htmlspecialchars($content)?></span
	><img class="heart" tabindex="-1" draggable="false" data-evqust="<?php echo $id?>" src="images/heart.png" alt="Likez" onclick="addLikes(<?php echo $id?>)"/><span
		name="likes"
		class="counter"
		data-evqust="<?php echo $id?>"
		><?php echo $likes?></span
	><br/><img class="trash" tabIndex="-1" draggable="false" data-evqust="<?php echo $id?>" src="images/trash.png" alt="Supprimer" onclick="removeQuestion(<?php echo $id?>)"/><img class="modify" tabIndex="-1" draggable="false" data-evqust="<?php echo $id?>" src="images/modify.png" alt="Modifier" onclick="modifyQuestion(<?php echo $id?>)"/><img class="push" tabIndex="-1" draggable="false" data-evqust="<?php echo $id?>" src="images/push.png" alt="Push" onclick="pushQuestion(<?php echo $id?>)"/></div
>