<div
	class="<?php if ($questionSource > 0) echo "question1"; else echo "question"; ?><?php if ($selected) echo " selected"?>"
	name="aQuestion"
	data-evqust="<?php echo $id?>"
	><input type="checkbox" name="moderated" data-evqust="<?php echo $id?>" checked="false"/><em
		style="color:red"
		><?php if ($moderated) echo "VISIBLE"; else echo "HIDDEN";?></em
	><br/><span
		name="questionContent"
		data-evqust="<?php echo $id?>"
		contenteditable="true"
		><?php echo htmlspecialchars($content) ?></span
	><span
		name="likes"
		class="counter"
		data-evqust="<?php echo $id?>"
		data-source="<?php echo $questionSource ?>"
		><?php echo $likes ?></span
	><img class="goodbad" tabindex="-1" draggable="false" data-evqust="<?php echo $id?>" src="../images/bad.png" alt="Bad" onclick="addDisLikes(<?php echo $id?>)"/><span
		name="dislikes"
		class="counter"
		data-evqust="<?php echo $id?>"
		data-source="<?php echo $questionSource ?>"
		><?php echo $dislikes?></span
	><br/><img class="trash" tabIndex="-1" draggable="false" data-evqust="<?php echo $id?>" src="../images/trash.png" alt="Supprimer" onclick="removeQuestion(<?php echo $id?>)"/><?php if (!isset($myResponse)) { echo '<img class="modify" tabIndex="-1" draggable="false" data-evqust="' . $id . '" src="../images/modify.png" alt="Modifier" onclick="modifyQuestion(' . $id . ')"/>'; } ?><img class="push" tabIndex="-1" draggable="false" data-evqust="<?php echo $id?>" src="../images/push.png" alt="Push" onclick="pushQuestion(<?php echo $id?>)"/></div
>
